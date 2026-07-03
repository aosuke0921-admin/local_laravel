// All javaScript
//----------------------------------------------------------------------------------------
// SPページTOPボタン

function setupPageTopButton() {
  const btn = document.querySelector('.pagetop_btn');
  if (!btn) return;

  btn.addEventListener('click', () => {
    window.scrollTo({
      top: 0,
      behavior: 'smooth'
    });
  });
}

document.addEventListener('DOMContentLoaded', setupPageTopButton);