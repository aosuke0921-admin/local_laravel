// All javaScript
//----------------------------------------------------------------------------------------
//合計金額
window.total_amount = function () {

  let total = 0;

  document.querySelectorAll('.price').forEach(input => {

    const row = input.closest('tr');
    const dest = row?.querySelector('.hospital_select')?.value;

    const price = Number(input.value) || 0;

    if (!dest) return;

    total += price;
  });

  const target = document.querySelector('.total_amount');
  if (target) target.value = total;
};


//----------------------------------------------------------------------------------------
// 合計距離
window.total_distance = function () {

  let total = 0;

  document.querySelectorAll('[class^="d"]').forEach(el => {

    const val = Number(el.value);

    if (!isNaN(val)) {
      total += val;
    }
  });

  total = Math.round(total * 10) / 10;

  const target = document.querySelector('.total_distance');
  if (target) target.value = total;
};