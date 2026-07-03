// All javaScript
//----------------------------------------------------------------------------------------
// td 0非表示 / /month-archive

function markZeroCells() {
  document.querySelectorAll('.table_content td').forEach(td => {
    if (td.textContent.trim() === '0') {
      td.style.color = 'rgba(255,0,0,0)';
    }
  });
}

document.addEventListener('DOMContentLoaded', markZeroCells);