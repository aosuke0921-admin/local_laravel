$(function(){
    $(document).on('change', '.hospital_select', function () {

      //if (isInit) return;
      if (window.isInit) return;


      const $row = $(this).closest('tr');

      const dest = $(this).val();
      const user = $row.find('.user_name_select').val();

      const $distance = $row.find('.distance');
      const $price = $row.find('.price');
      const $check = $row.find('.sharedRide');
      const classification = $row.find('.classification').val();

      let found = null;

      // ★ 念のため
      if (!window.js_array || window.js_array.length === 0) {
          //console.log('js_arrayまだ');
          return;
      }

      $.each(window.js_array, function (_, value) {

          if (value.user !== user) return;

          // =========================
          // 行き先2なし
          // =========================
          if (!value.pickup_location) {

              if (value.destination === dest) {
                  found = value;
                  return false;
              }

          // =========================
          // 行き先2あり
          // =========================
          } else {

              const fullDest = value.destination + '←→' + value.pickup_location;

              if (
                  dest === fullDest ||
                  dest === value.destination ||
                  dest === value.pickup_location
              ) {
                  found = value;
                  return false;
              }
          }
      });

      // =========================
      // 値セット
      // =========================
      if (found) {

          $distance.val(parseFloat(found.distance || 0));

          calcPrice($row); // ← 料金計算

      } else {

          $distance.val('');
          $price.val('');
      }

      // =========================
      // 乗合チェック制御
      // =========================
      if (!dest || classification === '保険外') {

          $check.prop('checked', false).prop('disabled', true);

      } else {

          $check.prop('disabled', false);
      }

      // =========================
      // 合計更新
      // =========================
      //total.js内の関数を実行▼
      total_amount();
      total_distance();
      //total.js内の関数を実行▲

  });
});