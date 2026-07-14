$(function(){
//if(fileName != "dashboard" && fileName != "inspection_check"){

  //------------------------------------------------------------------------------------------------------------------------------

  $(document).on('change', '.classification', function(){

      let $row   = $(this).closest('tr');
      let $check = $row.find('.sharedRide');
      let val    = $(this).val();

      let user = $row.find('.user_name_select').val().trim();

      //if (val === '保険外') {
      //if (val === '保険外' && user !== 'カンエムディ イクラムルホーク') {
      // shared_ride.js / 保険外でも乗合許可の人を追記するファイル
        if (
            val === '保険外' &&
            !window.sharedRideAllowedUsers.includes(user)
        ) {

          // ① 乗合チェック外す＆無効化
          $check.prop('checked', false).prop('disabled', true);

          // ② 利用者・行き先を選択可能に戻す
          $row.find('.user_name_select, .hospital_select').prop('disabled', false);

          // ③ 金額を通常に戻す（←ここ重要）
          calcPrice($row);

        } else {

          // ④ 乗合チェックを有効化
          $check.prop('disabled', false);

          // ⑤ チェックされてなければ select も有効
          if (!$check.prop('checked')) {
              $row.find('.user_name_select, .hospital_select').prop('disabled', false);
          }
      }
      //total.js内の関数を実行▼
      total_amount();
      //total.js内の関数を実行▲
  });

  //------------------------------------------------------------------------------------------------------------------------------

//} // if(fileName != "dashboard" && fileName != "inspection_check"){ end

});