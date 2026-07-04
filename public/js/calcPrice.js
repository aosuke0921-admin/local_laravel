let calcRunning = false;

function calcPrice($row, force = false) {

if (calcRunning && !force) return;

calcRunning = true;

let km = Number($row.find('.distance').val() || 0);

let basePrice = km < 5
    ? 200
    : 200 + Math.ceil(km - 5) * 60;

const $price = $row.find('.price');
const isShared = $row.find('.sharedRide').prop('checked');

// ★ここがポイント：必ず「ベースから計算」
let finalPrice = basePrice;

if (isShared) {
    finalPrice = Math.floor(basePrice / 2);
}

$price.val(finalPrice);

calcRunning = false;
}