<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

class Hospital extends Model
{
    /**
     * @var list<string>
     */
    protected $fillable = [
        'admin_id', 'name', 'registration_number', 'slug', 'city', 'address', 'phone', 'email', 'website',
        'description', 'services', 'insurances', 'image', 'is_active',
        'verified_at', 'trial_ends_at', 'subscription_ends_at', 'suspended_reason',
    ];

    protected function casts(): array
    {
        return [
            'is_active' => 'boolean',
            'services' => 'array',
            'insurances' => 'array',
            'verified_at' => 'datetime',
            'trial_ends_at' => 'datetime',
            'subscription_ends_at' => 'datetime',
        ];
    }

    public function getRouteKeyName(): string
    {
        return 'slug';
    }

    public function admin(): BelongsTo
    {
        return $this->belongsTo(User::class, 'admin_id');
    }

    public function doctors(): HasMany
    {
        return $this->hasMany(Doctor::class);
    }

    public function appointments(): HasMany
    {
        return $this->hasMany(Appointment::class);
    }

    public static function uniqueSlug(string $name): string
    {
        $base = Str::slug($name) ?: 'hospital';
        $slug = $base;
        $i = 2;

        while (static::where('slug', $slug)->exists()) {
            $slug = $base.'-'.$i++;
        }

        return $slug;
    }

    public function imageUrl(): string
    {
        if ($this->image && str_contains($this->image, '/')) {
            return Storage::disk('public')->url($this->image);
        }

        return asset('mediplus/img/'.($this->image ?: 'pf1.jpg'));
    }

    // ---- Verification / trial / subscription ----

    public function isVerified(): bool
    {
        return $this->verified_at !== null;
    }

    public function subscriptionActive(): bool
    {
        return $this->subscription_ends_at?->isFuture() ?? false;
    }

    public function onTrial(): bool
    {
        return ! $this->subscriptionActive() && ($this->trial_ends_at?->isFuture() ?? false);
    }

    public function hasAccess(): bool
    {
        return $this->is_active && ($this->onTrial() || $this->subscriptionActive());
    }

    public function accessEndsAt(): ?\Illuminate\Support\Carbon
    {
        $dates = array_filter([$this->trial_ends_at, $this->subscription_ends_at]);

        return $dates ? collect($dates)->max() : null;
    }

    public function daysLeft(): int
    {
        $end = $this->subscriptionActive() ? $this->subscription_ends_at : $this->trial_ends_at;

        return $end && $end->isFuture() ? (int) ceil(($end->timestamp - now()->timestamp) / 86400) : 0;
    }

    /** suspended | pending | expired | trial | active */
    public function state(): string
    {
        return match (true) {
            ! $this->is_active => 'suspended',
            ! $this->isVerified() => 'pending',
            $this->subscriptionActive() => 'active',
            $this->onTrial() => 'trial',
            default => 'expired',
        };
    }

    public function stateLabel(): string
    {
        return match ($this->state()) {
            'suspended' => 'Suspended',
            'pending' => 'Pending review',
            'active' => 'Subscribed',
            'trial' => 'Trial · '.$this->daysLeft().'d left',
            default => 'Expired',
        };
    }

    /** Why the public cannot see this hospital (null when visible). */
    public function blockedReason(): ?string
    {
        return match ($this->state()) {
            'suspended' => $this->suspended_reason ?: 'Suspended by an administrator.',
            'pending' => 'Waiting for MediConnect to verify the hospital.',
            'expired' => 'Trial or subscription has ended.',
            default => null,
        };
    }

    /** Hospitals shown to patients: active, verified and within trial/subscription. */
    public function scopeVisible(Builder $query): Builder
    {
        return $query->where('is_active', true)
            ->whereNotNull('verified_at')
            ->where(fn (Builder $q) => $q->where('trial_ends_at', '>', now())->orWhere('subscription_ends_at', '>', now()));
    }

    public function isVisible(): bool
    {
        return $this->state() === 'trial' || $this->state() === 'active';
    }

    /**
     * @return list<array{label: string, done: bool, route: string}>
     */
    public function setupChecklist(): array
    {
        return [
            ['label' => 'Upload your hospital logo / photo', 'done' => (bool) $this->image, 'route' => 'hospital.profile'],
            ['label' => 'Write a description (at least 40 characters)', 'done' => mb_strlen((string) $this->description) >= 40, 'route' => 'hospital.profile'],
            ['label' => 'List the services you offer', 'done' => ! empty($this->services), 'route' => 'hospital.profile'],
            ['label' => 'Add your first doctor', 'done' => $this->doctors()->exists(), 'route' => 'hospital.doctors.create'],
        ];
    }
}
