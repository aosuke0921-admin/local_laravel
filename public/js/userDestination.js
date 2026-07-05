$(function () {
window.js_array = [];

//if(fileName == "post" || fileName == "preview"){

  //--------------------------------------------------------------------------------

  fetch('/api/user-destinations')
  .then(res => res.json())
  .then(data => {

      window.js_array = Array.isArray(data) ? data : [];

      // -----------------------------
      // ★ ① 初期はまだロック状態
      // -----------------------------
      //isInit = true;
      window.isInit = true;
      // -----------------------------
      // ★ ② changeは必ず遅延させる
      // -----------------------------

      setTimeout(() => {

          // 初期change発火（まだロック中）
          $('.user_name_select').trigger('change');

          // -----------------------------
          // ★ original確定
          // -----------------------------
          $('.user_name_select').each(function(){

              let $row = $(this).closest('tr');
              let $checkbox = $row.find('.sharedRide');
              let $price = $row.find('.price');

              // 現在の価格を基準にする
              let original = Number($price.val()) || 0;

              $price.data('original', original);

          });

          // 表示
          $('.distance, .price').css('visibility', 'visible');

          // -----------------------------
          // ★ 最後に解除（最重要）
          // -----------------------------
          //isInit = false;
          window.isInit = false;

      }, 0);
      
  }); //非同期完了後に処理ここまで

  
  //--------------------------------------------------------------------------------

  $('.user_name_select').on('change', function () {

    //--------------------------------------------------------------------------------
    // 6.4追加
    const classification =
        $(this).find('option:selected').data('classification');

    // 値がセットされていたら実行
    if (classification) {
      $(this)
          .closest('tr')
          .find('.classification')
          .val(classification || '');
    }
    //--------------------------------------------------------------------------------

    const user_select = ($(this).val() || '').trim();   // ←ここ重要

    const targetSelect = $(this).closest('tr').find('.hospital_select');

    const currentChild = targetSelect.val();

    targetSelect.empty().append('<option value="">選択してください</option>');

    const seen = new Set();

    $.each(js_array, function (_, value) {

        if (value.user !== user_select) return;

        const label = value.pickup_location
            ? `${value.destination}←→${value.pickup_location}`
            : value.destination;

        // ★ 重複チェック
        if (seen.has(label)) return;
          seen.add(label);

          targetSelect.append(
              `<option value="${label}">${label}</option>`
        );
    });

    if (
            currentChild &&
            targetSelect.find(`option[value="${currentChild}"]`).length
        ) {
            targetSelect.val(currentChild);
        }
    });
//} // end post preview
});