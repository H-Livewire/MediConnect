<?php

namespace App\Livewire\Auth;

use App\Enums\UserRole;
use App\Models\User;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Password;
use Illuminate\Validation\Rules\Password as PasswordRule;
use Livewire\Attributes\Layout;
use Livewire\Attributes\Title;
use Livewire\Component;

#[Layout('layouts.auth')]
#[Title('Login')]
class Access extends Component
{
    public const MAX_ATTEMPTS = 5;

    public const LOCKOUT_SECONDS = 120;

    public string $panel = 'login';

    // Login
    public string $loginEmail = '';

    public string $loginPassword = '';

    public bool $remember = false;

    // Register
    public string $name = '';

    public string $email = '';

    public string $phone = '';

    public string $password = '';

    public string $password_confirmation = '';

    // Forgot password
    public string $forgotEmail = '';

    public ?string $status = null;

    public int $lockSeconds = 0;

    public function mount(): void
    {
        $this->panel = match (true) {
            request()->routeIs('register') => 'register',
            request()->routeIs('password.request') => 'forgot',
            default => 'login',
        };
    }

    public function show(string $panel): void
    {
        if (! in_array($panel, ['login', 'register', 'forgot'], true)) {
            return;
        }

        $this->panel = $panel;
        $this->status = null;
        $this->resetErrorBag();

        $url = match ($panel) {
            'register' => route('register', absolute: false),
            'forgot' => route('password.request', absolute: false),
            default => route('login', absolute: false),
        };

        $this->js("history.replaceState({}, '', ".json_encode($url).')');
    }

    public function login(): void
    {
        $this->status = null;

        $this->validate([
            'loginEmail' => ['required', 'email'],
            'loginPassword' => ['required', 'string'],
        ], [], ['loginEmail' => 'email', 'loginPassword' => 'password']);

        if ($this->refreshLock() > 0) {
            return;
        }

        if (! Auth::attempt(['email' => $this->loginEmail, 'password' => $this->loginPassword], $this->remember)) {
            $this->registerFailure();

            return;
        }

        $this->clearFailures();
        session()->regenerate();

        $this->redirectIntended(route('dashboard'));
    }

    public function register(): void
    {
        $this->status = null;

        $data = $this->validate([
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'email:rfc', 'max:255', 'unique:users,email'],
            'phone' => ['required', 'string', 'max:30'],
            'password' => ['required', 'confirmed', PasswordRule::min(8)],
        ]);

        $plain = $data['password'];

        User::create([...$data, 'role' => UserRole::Patient]);

        // Hand the new credentials to the login panel.
        $this->loginEmail = $data['email'];
        $this->loginPassword = $plain;
        $this->reset(['name', 'email', 'phone', 'password', 'password_confirmation']);
        $this->resetErrorBag();
        $this->show('login');
        $this->status = 'Account created successfully. Your details are filled in — press Login to continue.';
    }

    public function sendReset(): void
    {
        $this->status = null;

        $this->validate(['forgotEmail' => ['required', 'email']], [], ['forgotEmail' => 'email']);

        $result = Password::sendResetLink(['email' => $this->forgotEmail]);

        if ($result === Password::RESET_THROTTLED) {
            $this->addError('forgotEmail', __($result));

            return;
        }

        // Same answer whether or not the account exists (no user enumeration).
        $this->status = 'If that email exists, a reset link has been sent.';
    }

    /** Called by wire:poll while the account is locked. */
    public function tick(): void
    {
        if ($this->refreshLock() === 0) {
            $this->resetErrorBag();
        }
    }

    private function registerFailure(): void
    {
        $failKey = $this->key('fail');
        Cache::add($failKey, 0, now()->addMinutes(15));
        $count = Cache::increment($failKey);

        if ($count >= self::MAX_ATTEMPTS) {
            Cache::put($this->key('lock'), now()->addSeconds(self::LOCKOUT_SECONDS)->timestamp, self::LOCKOUT_SECONDS);
            Cache::forget($failKey);
            $this->loginPassword = '';
            $this->refreshLock();

            return;
        }

        $left = self::MAX_ATTEMPTS - $count;
        $this->addError('loginPassword', "Incorrect email or password. {$left} attempt".($left === 1 ? '' : 's').' remaining.');
    }

    private function refreshLock(): int
    {
        $until = (int) Cache::get($this->key('lock'), 0);
        $this->lockSeconds = max(0, $until - now()->timestamp);

        if ($this->lockSeconds === 0 && $until !== 0) {
            Cache::forget($this->key('lock'));
        }

        return $this->lockSeconds;
    }

    private function clearFailures(): void
    {
        Cache::forget($this->key('fail'));
        Cache::forget($this->key('lock'));
    }

    private function key(string $type): string
    {
        return 'login:'.$type.':'.sha1(mb_strtolower(trim($this->loginEmail)).'|'.request()->ip());
    }

    public function render()
    {
        if ($this->panel === 'login' && $this->loginEmail !== '') {
            $this->refreshLock();
        }

        return view('livewire.auth.access');
    }
}
