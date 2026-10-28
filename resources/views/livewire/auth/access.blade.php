<div class="mc-panels">

    @if ($panel === 'login')
    <section class="mc-form-panel" wire:key="panel-login">
        <h1>Welcome back</h1>
        <p class="mc-auth-sub">Login to manage your appointments.</p>

        @if (session('status'))<div class="mc-alert mc-alert-success">{{ session('status') }}</div>@endif
        @if ($status)<div class="mc-alert mc-alert-success" role="status">{{ $status }}</div>@endif

        @if ($lockSeconds > 0)
            <div class="mc-alert mc-alert-danger" role="alert" wire:poll.1s="tick">
                <strong>Account temporarily locked.</strong>
                Too many failed attempts. Try again in
                <span class="mc-countdown">{{ sprintf('%d:%02d', intdiv($lockSeconds, 60), $lockSeconds % 60) }}</span>.
            </div>
        @endif

        <form wire:submit="login" class="mc-auth-form" novalidate>
            <div class="mc-field">
                <label for="login-email">Email</label>
                <input id="login-email" type="email" wire:model="loginEmail" placeholder="you@example.com" autocomplete="email" required>
                @error('loginEmail')<small class="mc-error">{{ $message }}</small>@enderror
            </div>
            <div class="mc-field">
                <label for="login-password">Password</label>
                @include('partials.password-input-lw', ['id' => 'login-password', 'model' => 'loginPassword', 'placeholder' => 'Your password', 'autocomplete' => 'current-password'])
                @error('loginPassword')<small class="mc-error">{{ $message }}</small>@enderror
            </div>
            <div class="mc-row-between">
                <label class="mc-check"><input type="checkbox" wire:model="remember"> Remember me</label>
                <a href="{{ route('password.request') }}" wire:click.prevent="show('forgot')">Forgot password?</a>
            </div>
            <button type="submit" class="btn mc-block mc-btn-loading" wire:loading.attr="disabled" wire:target="login" @disabled($lockSeconds > 0)>
                <span wire:loading.remove wire:target="login">Login</span>
                <span wire:loading wire:target="login"><i class="mc-spinner" aria-hidden="true"></i> Signing in…</span>
            </button>
        </form>
        <p class="mc-switch">Don't have an account? <a href="{{ route('register') }}" wire:click.prevent="show('register')">Register</a></p>
    </section>
    @endif

    @if ($panel === 'register')
    <section class="mc-form-panel" wire:key="panel-register">
        <h1>Create your account</h1>
        <p class="mc-auth-sub">Register as a patient to start booking.</p>
        <form wire:submit="register" class="mc-auth-form" novalidate>
            <div class="mc-field">
                <label for="reg-name">Full name</label>
                <input id="reg-name" type="text" wire:model="name" placeholder="Your full name" autocomplete="name" required>
                @error('name')<small class="mc-error">{{ $message }}</small>@enderror
            </div>
            <div class="mc-two">
                <div class="mc-field">
                    <label for="reg-email">Email</label>
                    <input id="reg-email" type="email" wire:model="email" placeholder="you@example.com" autocomplete="email" required>
                    @error('email')<small class="mc-error">{{ $message }}</small>@enderror
                </div>
                <div class="mc-field">
                    <label for="reg-phone">Phone</label>
                    <input id="reg-phone" type="text" wire:model="phone" placeholder="+255…" autocomplete="tel" required>
                    @error('phone')<small class="mc-error">{{ $message }}</small>@enderror
                </div>
            </div>
            <div class="mc-two">
                <div class="mc-field">
                    <label for="reg-password">Password</label>
                    @include('partials.password-input-lw', ['id' => 'reg-password', 'model' => 'password', 'placeholder' => 'Min 8 characters', 'autocomplete' => 'new-password'])
                    @error('password')<small class="mc-error">{{ $message }}</small>@enderror
                </div>
                <div class="mc-field">
                    <label for="reg-password2">Confirm password</label>
                    @include('partials.password-input-lw', ['id' => 'reg-password2', 'model' => 'password_confirmation', 'placeholder' => 'Repeat password', 'autocomplete' => 'new-password'])
                </div>
            </div>
            <button type="submit" class="btn mc-block mc-btn-loading" wire:loading.attr="disabled" wire:target="register">
                <span wire:loading.remove wire:target="register">Create account</span>
                <span wire:loading wire:target="register"><i class="mc-spinner" aria-hidden="true"></i> Creating account…</span>
            </button>
        </form>
        <p class="mc-switch">Already have an account? <a href="{{ route('login') }}" wire:click.prevent="show('login')">Login</a></p>
    </section>
    @endif

    @if ($panel === 'forgot')
    <section class="mc-form-panel" wire:key="panel-forgot">
        <h1>Forgot password?</h1>
        <p class="mc-auth-sub">Enter your email and we will send you a link to reset it.</p>
        @if ($status)<div class="mc-alert mc-alert-success" role="status">{{ $status }}</div>@endif
        <form wire:submit="sendReset" class="mc-auth-form" novalidate>
            <div class="mc-field">
                <label for="forgot-email">Email</label>
                <input id="forgot-email" type="email" wire:model="forgotEmail" placeholder="you@example.com" autocomplete="email" required>
                @error('forgotEmail')<small class="mc-error">{{ $message }}</small>@enderror
            </div>
            <button type="submit" class="btn mc-block mc-btn-loading" wire:loading.attr="disabled" wire:target="sendReset">
                <span wire:loading.remove wire:target="sendReset">Send reset link</span>
                <span wire:loading wire:target="sendReset"><i class="mc-spinner" aria-hidden="true"></i> Sending…</span>
            </button>
        </form>
        <p class="mc-switch"><a href="{{ route('login') }}" wire:click.prevent="show('login')"><i class="fa fa-angle-left"></i> Back to login</a></p>
    </section>
    @endif
</div>
