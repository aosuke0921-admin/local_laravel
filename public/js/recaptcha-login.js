// All javaScript
//----------------------------------------------------------------------------------------
document.addEventListener('DOMContentLoaded', () => {

  const form = document.getElementById('actionForm');
  if (!form) return;

  form.addEventListener('submit', async (e) => {
    e.preventDefault();

    const input = form.querySelector('.recaptcha_token');

    const safeSubmit = () => {
      form.submit();
    };

    if (typeof grecaptcha === "undefined") {
      safeSubmit();
      return;
    }

    try {
      await new Promise(resolve => grecaptcha.ready(resolve));

      const token = await grecaptcha.execute(
        window.RECAPTCHA_SITE_KEY,
        { action: 'login' }
      );

      if (input && token) {
        input.value = token;
      }

      safeSubmit();

    } catch (err) {
      console.error("reCAPTCHA error:", err);
      safeSubmit();
    }
  });
});