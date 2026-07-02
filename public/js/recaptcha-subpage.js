// All javaScript
//----------------------------------------------------------------------------------------
function bindEvents() {

  const form = document.querySelector("form");
  if (!form) return;

  form.addEventListener("submit", async (e) => {
    e.preventDefault();

    try {
      await new Promise(resolve => grecaptcha.ready(resolve));

      const token = await grecaptcha.execute(
        window.RECAPTCHA_SITE_KEY,
        { action: window.RECAPTCHA_PAGE }
      );

      const input = document.querySelector(".recaptcha_token");
      if (input) input.value = token;

      form.submit();

    } catch (err) {
      console.error("reCAPTCHA error:", err);
      alert("認証エラーが発生しました。再度お試しください。");
    }
  });
}