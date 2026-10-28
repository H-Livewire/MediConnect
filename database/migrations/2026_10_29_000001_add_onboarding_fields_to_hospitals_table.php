<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('hospitals', function (Blueprint $table) {
            $table->string('registration_number')->nullable()->after('name');
            $table->string('website')->nullable()->after('email');
            $table->json('services')->nullable()->after('description');
            $table->json('insurances')->nullable()->after('services');
            $table->timestamp('verified_at')->nullable();
            $table->timestamp('trial_ends_at')->nullable();
            $table->timestamp('subscription_ends_at')->nullable();
            $table->string('suspended_reason')->nullable();
        });

        Schema::table('doctors', function (Blueprint $table) {
            $table->date('away_until')->nullable()->after('is_active');
        });

        // Hospitals that already exist (seed/demo data) stay visible.
        DB::table('hospitals')->update([
            'verified_at' => now(),
            'subscription_ends_at' => now()->addYear(),
        ]);
    }

    public function down(): void
    {
        Schema::table('doctors', fn (Blueprint $table) => $table->dropColumn('away_until'));
        Schema::table('hospitals', fn (Blueprint $table) => $table->dropColumn([
            'registration_number', 'website', 'services', 'insurances',
            'verified_at', 'trial_ends_at', 'subscription_ends_at', 'suspended_reason',
        ]));
    }
};
