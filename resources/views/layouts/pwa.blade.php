<script>
window.VAPID_PUBLIC_KEY = "{{ env('VAPID_PUBLIC_KEY') }}";
</script>

<script src="{{ asset('js/pwa.js') }}?v={{ time() }}"></script>