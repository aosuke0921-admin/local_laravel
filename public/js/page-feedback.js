// jQuery + javaScript
//----------------------------------------------------------------------------------------
$(function(){
  //--------------------------------------------------------------------------------
  // URLのクエリ文字列を取得
  const queryString = window.location.search;
  // URLSearchParamsオブジェクトを作成してクエリ文字列を解析
  const params = new URLSearchParams(queryString);
  // 特定のパラメータの値を取得
  const success = params.get('success');
  const error = params.get('error');

  //--------------------------------------------------------------------------------
  // flashImage1
  // グローバル化・system.jsから見える
  window.flashImage = function(times, interval,image1,image2) {
      const img = $('.conf').children('img');
      for (let i = 0; i < times; i++) {
          setTimeout(() => {
              img.attr('src', i % 2 === 0 ? image1 : image2);
          }, i * interval);
      }
  }
  //--------------------------------------------------------------------------------
  // alertMessage
  // グローバル化・system.jsから見える
  window.alertMessage = function(text){
    $('#overflow').show();
    $('#overflow').children().children('p').html(text);
    //$('.closeBtn').off('click') // ←これ地味に重要（多重バインド防止）
    //.on('click', function () {
    $('.closeBtn').on('click',function(){
      $('#overflow').hide();
    });
  }
  //--------------------------------------------------------------------------------
  //登録完了しました
  if(success == "insert"){
    // 使い方：10回切り替え、200msごと
    flashImage(10, 200,'./image/4041.webp','./image/40412.webp');
    alertMessage("登録完了しました");

    setTimeout(function(){
      location.href = './dashboard?badge=true'; // Laravel
      //location.href = './dashboard'; // Laravel
    },3000);
  }
  //--------------------------------------------------------------------------------
  // Laravel
  if(success == "update"){
    flashImage(10, 200,'./image/4041.webp','./image/40412.webp');
    alertMessage("更新完了しました");

    setTimeout(function(){
      location.href = './dashboard'; // Laravel
    },3000);
  }
  //--------------------------------------------------------------------------------  
  // Laravel
  if(success == "master_update"){

    flashImage(10, 200,'./image/4041.webp','./image/40412.webp');
    alertMessage("更新完了しました");
    setTimeout(function(){
      location.href = './master'; // Laravel
    },3000);
  }
  //--------------------------------------------------------------------------------
  // Laravel
  if(error == "car"){
      flashImage(10, 200,'./image/40041.webp','./image/40042.webp');
      alertMessage("乗降車を選択してください");
      return false; 
  }
  //--------------------------------------------------------------------------------
  // Laravel
  if(error == "no_data"){
      flashImage(10, 200,'./image/40041.webp','./image/40042.webp');
      alertMessage("該当するデータはありません");
      return false; 
  }
  //--------------------------------------------------------------------------------
  // Laravel
  if(error == "no_check"){
      flashImage(10, 200,'./image/40041.webp','./image/40042.webp');
      alertMessage("点検項目は全てチェックしてください");
      return false; 
  }
  //--------------------------------------------------------------------------------
  // Laravel
  if(success == "delete"){
      flashImage(10, 200,'./image/40432.webp','./image/40431.webp');
      alertMessage("削除完了しました");

      setTimeout(function(){
        location.href = './dashboard'; // Laravel
      },3000);
  }
  //--------------------------------------------------------------------------------  
  // Laravel
  if(success == "master_delete"){
      flashImage(10, 200,'./image/40432.webp','./image/40431.webp');
      alertMessage("削除完了しました");
     
      setTimeout(function(){
        location.href = './master'; // Laravel
      },3000);
  }
  //--------------------------------------------------------------------------------  
  // Laravel
  if(error == "start_distance"){
      flashImage(10, 200,'./image/40041.webp','./image/40042.webp');
      alertMessage("始業距離を入力してください");
      return false; 
  }
  //--------------------------------------------------------------------------------  
  // Laravel
  if(error == "validationError"){
      flashImage(10, 200,'./image/40041.webp','./image/40042.webp');
      alertMessage("項目を正しく入力してください");
      return false; 
  }
  //--------------------------------------------------------------------------------  
  // Laravel
  if(error == "user_not_found"){
      flashImage(10, 200,'./image/40041.webp','./image/40042.webp');
      alertMessage("登録済みの利用者名を正しく入力してください<br>姓名の間は半角スペースで入力してください");
      return false; 
  }
  //--------------------------------------------------------------------------------  
  // Laravel
  if(error == "destination_not_found"){
      flashImage(10, 200,'./image/40041.webp','./image/40042.webp');
      alertMessage("登録済みの行き先を正しく入力してください");
      return false; 
  }
  //--------------------------------------------------------------------------------  
  // Laravel
  $('.post_btn').on('click', function (e) {
    let isError = false;
    $('.row').each(function (index) {
      const user = $(this).find('.user_name_select').val() || '';
      const startTime = $(this).find('.start').val() || '';
      const endTime = $(this).find('.end').val() || '';
      const hospital_select = $(this).find('.hospital_select').val() || '';

      let hasAnyInput = false;
      //let isError = false;
      const isEmpty = (v) => !v || v === "-";
      $('tr.row').each(function (index) {
        const $row = $(this);
        const user = $row.find('.user_name_select').val();
        const startTime = $row.find('.start').val();
        const endTime = $row.find('.end').val();
        const hospital = $row.find('.hospital_select').val();
        // 👇 どれか入ってたら「入力あり」
        const any = !isEmpty(user) || !isEmpty(startTime) || !isEmpty(endTime) || !isEmpty(hospital);

        if (any) hasAnyInput = true;

        // 👇 完全空行はスキップ
        if (!any) return true;

        // 👇 中途半端入力は禁止
        if (isEmpty(user) || isEmpty(startTime) || isEmpty(endTime) || isEmpty(hospital)) {
          isError = true;
          return false;
        }
      });

      // 👇 1行も入力なしはNG（ここが超重要）
      if (!hasAnyInput) {
        isError = true;
      }

      if (isError) {
        e.preventDefault();
        flashImage(10, 200,'./image/40041.webp','./image/40042.webp');
        alertMessage("項目を正しく入力してください");
        return false;
      }
    });
    
    // 👇 エラーなら送信ストップ
    if (isError) {
      e.preventDefault();
      flashImage(10, 200,'./image/40041.webp','./image/40042.webp');
      alertMessage("項目を正しく入力してください");
      return false;
    }

    // 👇 送信前にdisabled解除（nameが送られるように）
    $('.hospital_name').prop('disabled', false);
    $('.user_name').prop('disabled', false);
    $('.user_name_select').prop('disabled', false);
    $('.hospital_select').prop('disabled', false);

  });
});