<div class="mc-pass">
    <input id="{{ $id }}" type="password" name="{{ $name }}" placeholder="{{ $placeholder ?? '' }}" autocomplete="{{ $autocomplete ?? 'current-password' }}" required>
    <button type="button" class="mc-pass-toggle" data-pass-toggle aria-label="Show password" aria-pressed="false">
        <svg class="mc-eye" viewBox="0 0 24 24" width="20" height="20" fill="none" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true"><path d="M2 12s3.6-7 10-7 10 7 10 7-3.6 7-10 7S2 12 2 12z"/><circle cx="12" cy="12" r="3"/></svg>
        <svg class="mc-eye-off" viewBox="0 0 24 24" width="20" height="20" fill="none" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true"><path d="M17.9 17.9A10.1 10.1 0 0 1 12 19c-6.4 0-10-7-10-7a17.7 17.7 0 0 1 4.1-5M9.9 5.2A9.7 9.7 0 0 1 12 5c6.4 0 10 7 10 7a17.6 17.6 0 0 1-2.2 3.2M1 1l22 22M9.9 9.9a3 3 0 0 0 4.2 4.2"/></svg>
    </button>
</div>
