// All javaScript
/*-------------------------------------------------------------------------------------*/
/* ローディング・localStorage保存・復元
-------------------------------------------------------------------------------------*/
//document.addEventListener('DOMContentLoaded', () => { //HTMLだけ読み込み完了で発火なので少し早い・復元不安定
window.addEventListener('load', function () { // 全部終わってから発火・復元が安定
  // =========================================================
  // フォーム保存
  // =========================================================
  function saveForm() {
    const formData = {};

    document.querySelectorAll('input, select, textarea').forEach(el => {

      const name = el.name;
      if (!name) return;

      const type = el.type;
      let val = el.value;

      if (type === 'checkbox') {

        formData[name] = el.checked ? 1 : 0;

      } else if (type === 'time') {

        formData[name] = val || '';

        if (val) {
          const parts = val.split(':');
          if (parts.length >= 2) {
            val =
              parts[0].padStart(2, '0') + ':' +
              parts[1].slice(0, 2);
          }
        }

        formData[name] = val;

      } else {

        formData[name] = val;
      }
    });

    localStorage.setItem('post_form', JSON.stringify(formData));
  }

  // =========================================================
  // 自動保存（input）
  // =========================================================
  let timer;

  document.addEventListener('input', (e) => {

    if (['INPUT', 'TEXTAREA'].includes(e.target.tagName)) {

      clearTimeout(timer);

      timer = setTimeout(saveForm, 300);
    }
  });

  // =========================================================
  // changeイベント
  // =========================================================
  document.addEventListener('change', (e) => {

    const el = e.target;

    if (
      el.tagName === 'SELECT' ||
      el.type === 'checkbox' ||
      el.type === 'time'
    ) {
      saveForm();
    }
  });

  // =========================================================
  // 復元
  // =========================================================
  (function restoreForm() {

    const raw = localStorage.getItem('post_form');
    if (!raw) return;

    let data = {};

    try {
      data = JSON.parse(raw);
    } catch (e) {
      console.error('JSON error', e);
      return;
    }

    // 値復元
    document.querySelectorAll('input, select, textarea').forEach(el => {

      const name = el.name;
      if (!name || !(name in data)) return;

      const type = el.type;
      let val = data[name];

      if (type === 'checkbox') {

        el.checked = val == 1;

      } else if (type === 'time') {

        if (val) {
          const parts = val.split(':');
          if (parts.length >= 2) {
            val =
              parts[0].padStart(2, '0') + ':' +
              parts[1].slice(0, 2);
          }
        }

        el.value = val;

      } else {

        el.value = val;
      }
    });

    // select再適用（少し遅延）
    setTimeout(() => {

      document.querySelectorAll('select').forEach(el => {

        const name = el.name;
        if (!name || !(name in data)) return;

        el.value = data[name];

        el.dispatchEvent(new Event('change'));
      });

    }, 300);

  })();

  // =========================================================
  // 送信時クリア
  // =========================================================
  document.querySelectorAll('form').forEach(form => {

    form.addEventListener('submit', () => {
      localStorage.removeItem('post_form');
    });

  });
});
