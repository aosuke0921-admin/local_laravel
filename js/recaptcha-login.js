document.addEventListener('DOMContentLoaded', function () {

    const form = document.getElementById('actionForm');
    if (!form) return;

    form.addEventListener('submit', function (e) {
        e.preventDefault();

        const input = form.querySelector('.recaptcha_token');

        const safeSubmit = () => {
            form.submit();
        };

        if (typeof grecaptcha === "undefined") {
            safeSubmit();
            return;
        }

        grecaptcha.ready(function () {

            grecaptcha.execute(window.RECAPTCHA_SITE_KEY, { action: 'login' })
                .then(function (token) {

                    if (input && token) {
                        input.value = token;
                    }

                    safeSubmit();
                })
                .catch(function () {
                    safeSubmit();
                });

        });

    });

});