// jQuery + javaScript
//----------------------------------------------------------------------------------------
$(function(){

let isInit = true; // ←ここ
//------------------------------------------------------------------------
// Ajax キャッシュ防止
$.ajaxSetup({
  cache: false
});

// すでにリダイレクト済みかどうかのフラグ / 何回もアラートやリダイレクトが出るのを防ぐ
let redirected = false;

// セッション確認の間隔（5分）分ごとにサーバーへ確認リクエストを送る
const checkInterval = 60 * 1000; // ← 1分 / (例) 5 * 60 * 1000; // 5分

// 一定間隔で処理を繰り返す（セッション監視）
setInterval(function () {

    // サーバーにAjaxリクエストを送信
    $.ajax({

        // セッション状態を確認する専用ルート / サーバー（Laravel）に用意した「セッション確認用のURL」 / 「今ログイン状態ですか？」ってサーバーに聞いてる
        url: '/ping-session',

        // GETリクエスト
        type: 'GET',

        // ===== 通信成功時 =====
        success: function (res) {

            // res.auth が false → セッション切れ（ログアウト状態）/ かつ まだリダイレクトしていない場合
            if (!res.auth && !redirected) {

                // リダイレクト済みにする（多重防止）
                redirected = true;

                // ユーザーに確認ダイアログを表示
                if (confirm('セッションが切れました。ログイン画面へ移動しますか？')) {

                    // OKならログイン画面へ移動
                    window.location.href = '/login';
                }

            } else {

                // セッションが生きている場合
                //console.log('session alive');
            }
        },

        // ===== 通信エラー時 =====
        error: function (xhr) {

            // HTTPステータスコードで判定 / 419 → CSRFトークン切れ / 401 → 未認証（ログアウト状態）
            if ((xhr.status === 419 || xhr.status === 401) && !redirected) {

                // リダイレクト済みにする（多重防止）
                redirected = true;

                // ユーザーに確認ダイアログを表示
                if (confirm('セッションが切れました。ログイン画面へ移動しますか？')) {

                    // OKならログイン画面へ移動
                    window.location.href = '/login';
                }
            }
        }
    });

// 指定した間隔で繰り返し実行
}, checkInterval);

// =============================================================================================

// ファイル名を取得
const fileName = window.location.pathname.split("/").pop();


window.js_array = [];

if(fileName == "post" || fileName == "preview"){

  //--------------------------------------------------------------------------------

  fetch('/api/user-destinations')
  .then(res => res.json())
  .then(data => {

      window.js_array = Array.isArray(data) ? data : [];

      // -----------------------------
      // ★ ① 初期はまだロック状態
      // -----------------------------
      isInit = true;
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
          isInit = false;

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
} // end post preview

/* window open
------------------------------------------------------------------------------*/ 
if(fileName == "reservation_search"
|| fileName == "boarding_reservation"
|| fileName == "user_destination_registration"
|| fileName == "preview"
|| fileName == "post"
|| fileName == "archive"
|| fileName == "month-archive"
|| location.pathname.includes("/edit")){

  $('.open_window').hide();


  $('.close_btn').on('click', function () {

    // ウィンドウ閉じる
    $('.open_window').hide();

    // 背景戻す
    $('#wrapper').show();

    // 月報系戻す
    if (
      fileName == "archive" ||
      fileName == "achievements.php" ||
      fileName == "month-archive"
    ) {
      $('.month_select').show();
    }

    // ボタン復活
    $('.user_name_select, .user_name_selects, .cap').show();

    // active解除
    $('.open_window ul').removeClass('active');

    // 一旦全部閉じる
    $('.open_window ul li').hide();

    // 見出しだけ戻す
    $('.open_window ul li.cap').show();

  });

//------------------------------------------------------------------------------------------------

$('.user_name_select,.user_name_selects').on('click',function(){

  let $w = $(window).width(); //現在のwindow幅を取得

  //月報
  if(fileName == "month-archive"){

    $('.month_archive .prevPage a').hide();
    $('.month_archive .prevPage').hide();

  }

  if($w > 768){

    $('.open_window').css('margin','70px auto 0');

    $('.open_window ul').removeClass('active');

    $('.open_window ul').css({
      display:'block'
    });

    $('.open_window ul').css('display','block');

    $('.open_window ul').children('li').css('display','none');

    const className = $(this).attr('class');

    if (className.includes('selects')) {
        //alert('A');
        $('.open_window ul.open_2 li.cap').css('display','block'); 
    }else{
        //alert('B');
        $('.open_window ul.open_1 li.cap').css('display','block'); 
    }

    //------------------------------------------------------------------------------------------------

    $('.open_window ul').on('click',function(){  

      $('.open_window').css('margin','0 auto');

      $('.open_window ul').removeClass('active');

      $('.open_window ul').toggleClass('active');

      if($('.open_window ul').hasClass('active')){

        // あーん・表示
        $('.open_window ul').css('display','none');

      }

      $(this).css({
        display:'block',
      });

      $('.open_window ul li').css('display','none');

      $(this).children('li:nth-child(n+2)').show();

      $(this).children('li').css('display','inline-block');

      //数を取得
      const count = $(this).children('li').length;

      //頭文字だけマイナス
      const count_check = count - 1;

      if(count_check == 0){

        $('.open_window').append('<div class="nasi">該当する検索結果はありません</div>');
        
        $('.nasi').css({
          fontFamily:'"ヒラギノ丸ゴ Pro W4","ヒラギノ丸ゴ Pro","Hiragino Maru Gothic Pro","ヒラギノ角ゴ Pro W3","Hiragino Kaku Gothic Pro","HG丸ｺﾞｼｯｸM-PRO","HGMaruGothicMPRO"',
          width:'100%',
          height:'90vh',
          lineHeight:'90vh',
          position:'fixed',
          top:'0',
          textAlign:'center',
          fontSize:'50px'
        });

      }
      $('.open_window ul li.cap').css('display','none');
    }); // open_window ul onclick
  } // 768以上 ここまで

  var tr_id = $(this).parent().parent('tr').attr('id');

  $('.open_window').css({
      opacity: 1,
      display: 'flex'}).hide().fadeIn(500);

  $('#wrapper').hide();

  if(fileName == "archive" || fileName == "achievements.php" || fileName == "month-archive"){

    $('.month_select').hide();

  }

  $(this).parent().addClass('active');

  $(this).hide();

  var user_select = $(this);

  //------------------------------------------------------------------------------------------------
  if(
    fileName == "reservation_search"
    || fileName == "boarding_reservation"
    || fileName == "user_destination_registration"
    || fileName == "preview"
    || fileName == "post"
    || fileName == "archive"
    || fileName == "month-archive"
    || location.pathname.includes("/edit")
  ){

    var li_tag = $('.open_window').find('li:not(.cap)');

  }else if(fileName == "month-archive"){

    $('.page404').hide();

  }

  li_tag.on('click',function(){

      const text = $(this).text().trim();

      //alert(text);

      if(text == "—"){

        $('#wrapper').show();
        user_select.show();
        $('.open_window').hide();
        
        return;
      }

      let $target;

      if (fileName == "post" || fileName == "preview" ) {
          $target = $('.active').find('.user_name_select');
          $('.active').removeClass('active');
      } else {
          const $row = $(this).closest('tr');
          $target = $row.find('.user_name_select');
      }

      if ($target.length) {

          $target.val(text);

          // ★ 初期だけ止める
          if (!isInit) {
              $target.trigger('change');
          }
      }

    if(fileName == "archive" || fileName == "month-archive"){

      $('.month_select').show();

    }

    $('#wrapper').show();

    user_select.show();

    $('.open_window').hide();

    //月報
    if(fileName == "month-archive"){

      $('.month_archive .prevPage a').show();
      $('.month_archive .prevPage').show();

    }

    if(fileName == "reservation_search" || fileName == "archive" || fileName == "month-archive"){

      const text = $(this).text().trim();

      $('.user_name_select').val(text).trigger('change'); // かわらない       

    }else if(
      fileName == "boarding_reservation"
      || fileName == "user_destination_registration"
      || location.pathname.includes("/edit")
    ){

      let type = $(this).data('type');
      let val = $(this).data('value') || $(this).text().trim();

      if(type === 'user'){
        $('.user_name_select').val(val).trigger('change');
      }else if(type === 'destination'){
        $('.user_name_selects').val(val).trigger('change');
      }
    }

    $('.user_name_select').parent().removeClass('active');

  });
});
//------------------------------------------------------------------------------------------------
$(document).on('click', '.open_window ul li:not(.cap)', function () {

  const val = $(this).text().trim();

  const isUser = $(this).closest('ul').hasClass('open_1');
  const isDest = $(this).closest('ul').hasClass('open_2');

  // ★ rowを「クリック元から取る」方式に変更
  const $row = $(this).closest('.open_window').data('target-row');

  if (!$row || $row.length === 0) return;

  if (isUser) {
      $row.find('.user_name_select').val(val);
  }

  if (isDest) {
      $row.find('.destination_select').val(val);
  }

  $('.open_window').hide();
});
//------------------------------------------------------------------------------------------------
}/*  if(fileName == "reservation_search"・・・・・・*/
//------------------------------------------------------------------------------------------------

var $w = $(window).width(); //現在のwindow幅を取得

if(fileName != "dashboard" && fileName != "inspection_check"){

  function controlSharedRide() {
    $('.classification').each(function () {

    let $row = $(this).closest('tr');
    let $check = $row.find('.sharedRide');

    if ($(this).val().trim() === '保険外') {
      $check.prop('checked', false);
      $check.prop('disabled', true);
    } else {
      $check.prop('disabled', false);
    }

    });
  }
  // ★ 初期表示時に実行
  controlSharedRide();
  //------------------------------------------------------------------------------------------------------------------------------

  $(document).on('change', '.classification', function(){

      let $row   = $(this).closest('tr');
      let $check = $row.find('.sharedRide');
      let val    = $(this).val();

      if (val === '保険外') {

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

  $(document).on('change', '.hospital_select', function () {

      if (isInit) return; // ←これ入れる

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

  //------------------------------------------------------------------------------------------------------------------------------

  $('.select').change(function(){

    if (isInit) return; // ←これ追加（ここ重要！！！）

    var className = $(this).attr('class');
      
    if(className == 'select user_name_select'){ //利用者

        var keyword1 = $(this).val();

        var keyword2 = $(this).parent().next().next().next().next().children().next().val();

        var $row = $(this).closest('tr');

        var distance__val = $row.find('.distance'); // 変数はそのまま

        var price__val    = $row.find('.price');

        if (!distance__val.val()) {
            distance__val.val(0);
        }

        if (!price__val.val()) {
            price__val.val(0);
        }
        //total.js内の関数を実行▼
        total_amount();
        total_distance();
        //total.js内の関数を実行▲
    }

    //------------------------------------------------------------------------------------------------------------------------------

    if(className == 'select hospital_select'){ //行先
        var keyword2 = $(this).val();
        $(this).prev().val(keyword2);

        // 同じ行の利用者 select 値を取得
        var keyword1 = $(this).closest('tr').find('.user_name_select').val();

        // 同じ行の distance と price を取得
        var $row = $(this).closest('tr');
        var distance__val = $row.find('.distance');
        var price__val    = $row.find('.price');
            
        // 距離取得、なければ0
        const distance = parseFloat(distance__val.val() || 0);
        // 区分取得
        const classification = $row.find('.classification').val();
        // 乗合取得
        const $check = $row.find('.sharedRide');
        // 距離が0と違う　　区分が保険外
        if (distance !== 0 && classification === '保険外') {
            // 乗合選択不可ON
            $check.prop('disabled', true).prop('checked', false);
        } else {
            // 乗合選択不可OFF
            $check.prop('disabled', false);
            syncRowDisabledState();
        }

        // ★ 既に値があるときは触らない
        if (!distance__val.val()) {
            distance__val.val(0);
        }

        if (!price__val.val()) {
            price__val.val(0);
        }
    }

    //------------------------------------------------------------------------------------------------------------------------------

    if(className == 'select user_name_select'){ //利用者
        var $row = $(this).closest('tr');

        if(fileName == "post"){
            // 区分選択を有効化
            $row.find('.classification').prop('disabled', false).css('opacity',1);
        }
    }

    //------------------------------------------------------------------------------------------------------------------------------

    if(className == 'select hospital_select'){ //行先
        var $row = $(this).closest('tr');

        hospital_select_next_checkbox = $row.find('.sharedRide');
        hospital_select__price = $row.find('.price').val();
    }
  });
} // if(fileName != "dashboard" && fileName != "inspection_check"){ end

//--------------------------------------------------------------------------------

});