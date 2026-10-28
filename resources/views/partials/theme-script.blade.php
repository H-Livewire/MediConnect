<script>
(function () {
    document.querySelectorAll('[data-theme-toggle]').forEach(function (btn) {
        btn.addEventListener('click', function () {
            var next = document.documentElement.getAttribute('data-theme') === 'dark' ? 'light' : 'dark';
            document.documentElement.setAttribute('data-theme', next);
            try { localStorage.setItem('mc-theme', next); } catch (e) {}
        });
    });
    document.querySelectorAll('[data-pass-toggle]').forEach(function (btn) {
        btn.addEventListener('click', function () {
            var input = btn.parentElement.querySelector('input');
            var show = input.type === 'password';
            input.type = show ? 'text' : 'password';
            btn.setAttribute('aria-pressed', show ? 'true' : 'false');
            btn.setAttribute('aria-label', show ? 'Hide password' : 'Show password');
            btn.classList.toggle('is-visible', show);
        });
    });
})();
</script>
