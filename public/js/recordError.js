// All javaScript
//----------------------------------------------------------------------------------------
function recordError() {

  const rows = document.querySelectorAll('tr');

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