// All javaScript
//----------------------------------------------------------------------------------------
function recordError() {

  const rows = document.querySelectorAll('tr');

//----------------------------------------------------------------------------------------

  // 未反映・背景色
  document.querySelectorAll('.hanei2').forEach(el => {
    const tr = el.closest('tr');
    if (tr) {
      tr.style.backgroundColor = '#fff';
    }
  });

//----------------------------------------------------------------------------------------
  // 料金発生・背景色
  rows.forEach(row => {

    const text = row.textContent.trim();

    if (
      text.includes('現地3,000円発生') ||
      text.includes('当日1,500円発生')
    ) {
      row.style.backgroundColor = 'rgb(228, 239, 163)';

    } else if (text === '0') {
      row.style.backgroundColor = '#f9b4b4';
    }
  });
}

document.addEventListener('DOMContentLoaded', recordError);