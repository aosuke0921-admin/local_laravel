<script>
window.RECAPTCHA_SITE_KEY = "{{ config('recaptcha.site_key') }}";
window.RECAPTCHA_PAGE = "homepage";
</script>

<script src="https://www.google.com/recaptcha/api.js?render={{ config('recaptcha.site_key') }}" async defer></script>
<script src="{{ asset('js/recaptcha-subpage.js') }}?v={{ time() }}"></script>