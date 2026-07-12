function highlightErrorRows() {

  const rows = document.querySelectorAll('tr');

  // 未反映・背景色
  //---------------------------------------------------------------
  document.querySelectorAll('.hanei2').forEach(el => {
    const tr = el.closest('tr');
    if (tr) {
      tr.style.backgroundColor = '#fff';
    }
  });

  let errorMessages = [];


  // 料金発生・登録エラー背景色
  //---------------------------------------------------------------
  //rows.forEach(row => {
    rows.forEach((row, index) => {


    const text = row.textContent.trim();

    if (
      text.includes('現地3,000円発生') ||
      text.includes('当日1,500円発生')
    ) {
      row.style.backgroundColor = 'rgb(228, 239, 163)';
      return;
    }

    // 0 または NULLチェック
  //---------------------------------------------------------------
    const cells = row.querySelectorAll('td');
    const errorCells = row.querySelectorAll('.check-error');

    const isUpdatePage = location.pathname.includes('preview');

    if (cells.length < 5) {
      return;
    }

    const date = cells[0].textContent
      .replace(/[\(\（].*?[\)\）]/g, '')
      .trim();

    let user = '';

    if (isUpdatePage) {
      user = row.querySelector('.user_name_select')?.value.trim() ?? '';
    } else {
      user = cells[1].textContent.trim();
    }

    const departureTime = cells[2].textContent.trim();
    const arrivalTime = cells[3].textContent.trim();
    const goingBack = cells[4].textContent.trim();
    const destination = cells[5].textContent.trim();
    const shareRide = cells[7].textContent.trim();
    const shareRideText = shareRide === '-' ? '' : shareRide;
    const hasError = [...errorCells].some(cell => {

      const input = cell.querySelector('input');

      const value = input
        ? input.value.trim()
        : cell.textContent.trim();

      return (
        value === '0' ||
        value === '' ||
        value.toLowerCase() === 'null'
      );

    });

    if (hasError) {

      const tbody = row.closest('tbody');

      if (window.innerWidth <= 768 && isUpdatePage) {

        if (tbody) {
          tbody.style.backgroundColor = '#f7e2e2';
        }

      } else {

        row.querySelectorAll('td').forEach(td => {
          td.style.backgroundColor = '#f7d0d0';
        });
      }    

      if (isUpdatePage) {

        errorMessages.push(
          //`【${user}】利用者・行き先・距離・料金を確認してください`
          `【運行${index}：${user}】利用者・行き先・距離・料金を確認してください`
        );

      } else {
      
        errorMessages.push(
          `${date} / ${user} / ${departureTime}〜${arrivalTime} / ${goingBack} / ${destination}${shareRideText ? ' / ' + shareRideText : ''} / 距離・料金エラー`
        );
      }
    }
  });

  // 最後に1回だけ表示
  if (errorMessages.length > 0) {

    console.log(errorMessages);

    alert(errorMessages.join('\n'));
  }
}
document.addEventListener('DOMContentLoaded', highlightErrorRows);