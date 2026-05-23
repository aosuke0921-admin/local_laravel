/**
 * フォーム保存
 */
function saveForm() {
    let formData = {};

$('input, select, textarea').each(function () {

    let name = this.name;
    if (!name) return;

    let type = this.type || '';
    let val = this.value; // ★最初に取る

    if (type === 'checkbox') {
        formData[name] = this.checked ? 1 : 0;

    } else if (type === 'time') {

        //console.log('A');

        formData[name] = val || '';

        //console.log('B');

        let parts = val.split(':');
        if (parts.length >= 2) {
            val =
                parts[0].padStart(2, '0') + ':' +
                parts[1].slice(0, 2);
        }

        formData[name] = val;

    } else {

        //console.log('C');

        formData[name] = val;
    }
});

    localStorage.setItem('post_form', JSON.stringify(formData));
}


/**
 * 自動保存（遅延）
--------------------------------------------------------------------------- */
let timer;
$(document).on('input', 'input, textarea', function () {
    clearTimeout(timer);
    timer = setTimeout(saveForm, 300);
});

$(document).on('change', 'select, input[type="checkbox"], input[type="time"]', function () {
    saveForm();
});

/**
 * 復元
--------------------------------------------------------------------------- */
$(function () {

    //console.log(JSON.parse(localStorage.getItem('post_form')));

    let raw = localStorage.getItem('post_form');
    if (!raw) return;

    let data = {};
    try {
        data = JSON.parse(raw);
    } catch (e) {

        //console.error('JSON error', e);

        return;
    }

    // ① 値復元
    $('input, select, textarea').each(function () {

        let name = this.name;
        if (!name || !(name in data)) return;

        let type = this.type || '';
        let val = data[name];

        if (type === 'checkbox') {
            this.checked = val == 1;

        } else if (type === 'time') {

            if (val) {
                let parts = val.split(':');
                if (parts.length >= 2) {
                    val =
                        parts[0].padStart(2, '0') + ':' +
                        parts[1].slice(0, 2);
                }
            }

            this.value = val;

        } else {
            this.value = val;
        }
    });

    // ② select再適用
    setTimeout(function () {
        $('select').each(function () {
            let name = this.name;
            if (!name || !(name in data)) return;

            $(this).val(data[name]).trigger('change');
        });
    }, 300);

    // ③ 再計算系
    /*setTimeout(function () {
        $('input').not('[type="time"]').trigger('change');
    }, 400);*/
});

/**
 * 送信後削除
--------------------------------------------------------------------------- */
$('form').on('submit', function () {
    localStorage.removeItem('post_form');
});

$(function(){

  let isInit = true; // ←ここ

//------------------------------------------------------------------------

  // Ajax キャッシュ防止
  $.ajaxSetup({
    cache: false
  });

  // すでにリダイレクト済みかどうかのフラグ
  // → 何回もアラートやリダイレクトが出るのを防ぐ
  let redirected = false;

  // セッション確認の間隔（5分）
  // 分ごとにサーバーへ確認リクエストを送る
  //const checkInterval = 5 * 60 * 1000; // 5分
  const checkInterval = 60 * 1000; // 1分

  // 一定間隔で処理を繰り返す（セッション監視）
  setInterval(function () {

      // サーバーにAjaxリクエストを送信
      $.ajax({

          // セッション状態を確認する専用ルート
          // サーバー（Laravel）に用意した「セッション確認用のURL」
          // 「今ログイン状態ですか？」ってサーバーに聞いてる
          url: '/ping-session',

          // GETリクエスト
          type: 'GET',

          // ===== 通信成功時 =====
          success: function (res) {

              // res.auth が false → セッション切れ（ログアウト状態）
              // かつ まだリダイレクトしていない場合
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

              // HTTPステータスコードで判定
              // 419 → CSRFトークン切れ
              // 401 → 未認証（ログアウト状態）
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

  // URLのクエリ文字列を取得
  const queryString = window.location.search;
  // URLSearchParamsオブジェクトを作成してクエリ文字列を解析
  const params = new URLSearchParams(queryString);

  // 特定のパラメータの値を取得
  //const paramValue = params.get('id');
  const success = params.get('success');
  const error = params.get('error');

  // ファイル名を取得
  var fileName = window.location.pathname.split("/").pop();

// =============================================================================================
// 端末判定（スマホ / タブレット / PC）
// =============================================================================================
const ua = navigator.userAgent;

// スマホ判定
const isSP =
  (ua.indexOf('Android') > -1 && ua.indexOf('Mobile') > -1) || // Androidスマホ
  ua.indexOf('iPhone') > -1 ||                                // iPhone
  ua.indexOf('iPod') > -1;                                    // iPod

// タブレット判定
const isTablet =
  (ua.indexOf('Android') > -1 && ua.indexOf('Mobile') === -1) || // Androidタブレット
  ua.indexOf('iPad') > -1;                                      // iPad

// =============================================================================================
// 分岐処理
// =============================================================================================
if (isSP) {

  // ===== スマホ =====
  // スマホ用の処理を書く（今回は特になし）

} else if (isTablet) {

  // ===== タブレット =====
  // タブレット用の処理を書く（今回は特になし）

} else {

  // ===== PC =====
  // PCのみリサイズ時にリロード処理を行う

  $(function(){

    // タイマー管理（resize連続発火を防ぐ）
    let timer = null;

    // リサイズイベント
    $(window).on('resize', function(){

      // 現在のウィンドウ幅を取得
      const width = $(window).width();

      // 例：1260px以下になったときに発動（ブレークポイント）
      if (width <= 1260) {

        // すでにタイマーがあればキャンセル（連続実行防止）
        if (timer) {
          clearTimeout(timer);
        }

        // 少し遅らせて1回だけ実行
        timer = setTimeout(function(){

          // ページをリロード
          location.reload();

        }, 300); // 300ms後に実行
      }
    });
  });
}
//----------------------------------------------------------------------------------------
  if(fileName === "dashboard"){
//----------------------------------------------------------------------------------------
    // 数値（小数OK）しか入力できないようにする
    $('#start_distance').on('input', function() {
        // 入力された値から「数字(0-9)とドット(.)以外」をすべて削除する
        // 例: "abc123.4d" → "123.4"
        this.value = this.value.replace(/[^0-9.]/g, '');

        // ドット(.)を基準に分割（小数点チェックのため）
        const parts = this.value.split('.');

        // ドットが2つ以上ある場合（例: 12.3.4）
        if(parts.length > 2) {
            // 1つ目のドットだけ残して、それ以降はすべて結合して1つの小数にする
            // 例: ["12","3","4"] → "12.34"
            this.value = parts[0] + '.' + parts.slice(1).join('');
        }
    });
//----------------------------------------------------------------------------------------
    // 「2026年4月4日」のような日付文字列を
    // 「2026-04-04」のDB用フォーマットに変換する関数
    function formatDateToDB(dateStr) {

      // 正規表現で「年・月・日」の数字を抽出
      // (\d+) → 数字を1つ以上取得
      // 例: "2026年4月4日" → ["2026年4月4日", "2026", "4", "4"]
      const match = dateStr.match(/(\d+)年(\d+)月(\d+)日/);

      // マッチしなかった場合（形式が違う場合）は空文字を返す
      if (!match) return '';

      // 年を取得（配列の2番目）
      const year  = match[1];

      // 月を取得し、2桁にする（例: 4 → 04）
      const month = String(match[2]).padStart(2, '0');

      // 日を取得し、2桁にする（例: 4 → 04）
      const day   = String(match[3]).padStart(2, '0');

      // 「YYYY-MM-DD」の形式に組み立てて返す
      // 例: "2026-04-04"
      return `${year}-${month}-${day}`;
    }
//----------------------------------------------------------------------------------------
    // 始業距離をDBから取得して、入力欄に自動反映する関数
    function updateStartDistance() {

        // カレンダー入力欄（#ymd）から選択された日付を取得
        const rawDate = $('#ymd').val();

        // 「2026年4月4日」→「2026-04-04」に変換（DB用フォーマット）
        const date = formatDateToDB(rawDate);

        // 車種（#car）の選択値を取得
        const car  = $('#car').val();

        // 日付または車種が未選択の場合
        if (!date || !car) {

            // 始業距離を0にリセット
            $('#start_distance').val(0);

            // それ以上処理しない（Ajaxを送らない）
            return;
        }

        // サーバー（Laravel）にAjaxリクエストを送信
        $.ajax({

            // データ取得用のURL（web.phpで定義したルート）
            url: '/get-start-distance',

            // GETメソッドで送信
            type: 'GET',

            // サーバーに送るデータ（クエリパラメータ）
            // 例: /get-start-distance?dates=2026-04-04&car=アルファード
            data: { dates: date, car: car },

            // 成功時の処理（LaravelからJSONが返ってくる）
            success: function(res) {

                // コンソールにレスポンスを表示（デバッグ用）
                //console.log('成功データ↓↓↓', res);

                // 取得した始業距離を入力欄にセット
                // 値がnull/undefinedなら0を入れる
                $('#start_distance').val(res.start_distance ?? 0);
            },

            // エラー時の処理（通信失敗・500エラーなど）
            error: function(err) {

                // エラー内容をコンソールに出力
                //console.error('Ajax Error:', err);

                // 安全のため0をセット
                $('#start_distance').val(0);
            }
        });
    }
//----------------------------------------------------------------------------------------
    // #carの変更時だけ発火
    //$('#car').on('change', updateStartDistance);
    $('#car, #ymd').on('change', updateStartDistance);

    // ページ読み込み時にも初期値を表示
    updateStartDistance();

  }

  /* td 0非表示
  ------------------------------------------------------------------------------*/
  $(".table_content td").each(function(i, elem) {
      if($(elem).text() == 0){
        $(this).css('color','rgb(255,0,0,0)');
      }
  });

  /* SPページTOPボタン
  ------------------------------------------------------------------------------*/
  $(".pagetop_btn").on("click",function(){
    $("html, body").animate({ scrollTop: 0 },600);
  });

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
}

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

    if(fileName == "preview" || fileName == "post"){
      // アンカーリンク
      function linkscroll(target) {
        $('html, body').animate({scrollTop: $(target).offset().top -86 }, 0, 'swing');//86px上にずらす
      }
    }

    $('.open_window').hide();

//------------------------------------------------------------------------------------------------------------
const kanaGroupMap = {
  'ア': 'a','イ': 'a','ウ': 'a','エ': 'a','オ': 'a',
  'カ': 'ka','キ': 'ka','ク': 'ka','ケ': 'ka','コ': 'ka',
  'サ': 'sa','シ': 'sa','ス': 'sa','セ': 'sa','ソ': 'sa',
  'タ': 'ta','チ': 'ta','ツ': 'ta','テ': 'ta','ト': 'ta',
  'ナ': 'na','ニ': 'na','ヌ': 'na','ネ': 'na','ノ': 'na',
  'ハ': 'ha','ヒ': 'ha','フ': 'ha','ヘ': 'ha','ホ': 'ha',
  'マ': 'ma','ミ': 'ma','ム': 'ma','メ': 'ma','モ': 'ma',
  'ヤ': 'ya','ユ': 'ya','ヨ': 'ya',
  'ラ': 'ra','リ': 'ra','ル': 'ra','レ': 'ra','ロ': 'ra',
  'ワ': 'other','ヲ': 'other','ン': 'other'
};

function getGroup(first) {
  return kanaGroupMap[first] ?? 'other';
}

$('.open_window ul.open_1, .open_window ul.open_2').each(function () {

  const first = $(this).find('.cap').text().trim().charAt(0);
  const group = getGroup(first);
  $(this).addClass(group);

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

        $('.open_window ul').on('click',function(){  

          //alert('aaa');

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
      });
//------------------------------------------------------------------------------------------------
  }
// 768以上 ここまで
//------------------------------------------------------------------------------------------------------------

        var tr_id = $(this).parent().parent('tr').attr('id');

        $('.open_window').css('opacity',1).fadeIn(500);

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
   
          if(fileName == "preview" || fileName == "post"){

             linkscroll('#' + tr_id);

          }else if(fileName == "reservation_search" || fileName == "archive" || fileName == "month-archive"){

            const text = $(this).text().trim();

            //console.log(text);// はいってる

            //console.log($('.user_name_select').length);

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
//------------------------------------------------------------------------------------------------
    });

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
  }

  /* sp / 利用者プルダウンメニュー
  ------------------------------------------------------------------------------*/
  var $w = $(window).width(); //現在のwindow幅を取得
  if($w <= 768){
    $('.open_window ul li:nth-child(n + 2)').hide();
    $('.open_window ul').on('click',function(){
      $(this).toggleClass("active");
      if($(this).hasClass('active')){
        $(this).children('li:nth-child(n + 2)').show();
      }else{
        $(this).children('li:nth-child(n + 2)').hide();
      }
    });
    $('.open_window ul li:nth-child(n + 2)').on('click',function(){
      $('.open_window ul li').hide();
      $('.open_window ul li.cap').show();
    });
    $('.user_name_select').on('click',function(){
      $('.open_window ul').removeClass('active');
    });
  } 

  let $img = $('.page404').children('img');
  let images = ['./image/40041.webp', './image/40042.webp'];

  for (let i = 0; i < 10; i++) {
    setTimeout(() => {
      $img.attr('src', images[i % 2]);
    }, i * 200);
  }

  if($w <= 768){
    if(fileName == "preview" || fileName == "delete"){
      var user_name_value = $('body.delete .user_name').val();
      if(user_name_value == null){
      }
    }
  }

  for (let i = 0; i < 10; i++) {
    setTimeout(function () {
      $('.inner404').children('img').attr('src', images[i % 2]);
    }, i * 200);
  }

  if(fileName != "dashboard" && fileName != "inspection_check" && fileName != "end_roll_call.php" && fileName != "start_roll_call.php" && fileName != "select.php" && fileName != "inspection_check.php"){

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

          total_amount();
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
          total_amount();
          total_distance();

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

            total_amount();
            total_distance();
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

            if(distance__val.val() != 0){
                distance__val.closest('tr').find('.sharedRide').prop('disabled', true);
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
  }

// 料金 ----------------------------------------------------------------------------------------
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

//  ----------------------------------------------------------------------------------------

$(document).on('change', '.sharedRide', function () {

    const $row = $(this).closest('tr');

    // ★これだけでOK
    calcPrice($row);

    total_amount();

});

/*-------------------------------------------------------------------------------------*/
  //合計金額
  function total_amount(){

    let total = 0;

    $('.price').each(function(){

      let $row = $(this).closest('tr'); // ★追加
      let dest = $row.find('.hospital_select').val(); // ★追加

      let price = Number($(this).val()) || 0;

      if (!dest) {
        return;
      }

      total += price;

    });

    $('.total_amount').val(total);
  }
 /*-------------------------------------------------------------------------------------*/

// 各行のチェックボックス状態を見て、同じ行の入力欄を有効/無効に揃える関数
function syncRowDisabledState() {
  $("input[type='checkbox']").each(function(){
    let row = $(this).closest('tr');
    let isChecked = $(this).prop('checked');
    row.find('.user_name_select, .hospital_select').prop('disabled', isChecked);
  });
}

/*-------------------------------------------------------------------------------------*/
  // 合計距離
  function total_distance() {

    // 合計を入れる変数を用意、最初は 0 からスタート
    let total = 0;

    // class="d0", d1, d2... みたいな「dで始まるクラス全部」を取得。見つかった要素を1個ずつ処理する
    $('[class^="d"]').each(function () {
      
      // input値を取得、数値に変換
      let val = Number($(this).val());
      
      //isNaN(val) = 「NaNかどうかチェック」
      // NaNじゃなければ、合計に足す
      if (!isNaN(val)) total += val;
    });

    //$('.total_distance').val(total.toFixed(1));

    total = Math.round(total * 10) / 10; // ★これ追加


    $('.total_distance').val(total); // ← 小数固定はやめて整数表示

  }
  total_amount();
  total_distance();
/*-------------------------------------------------------------------------------------*/

  syncRowDisabledState();

  // チェックON/OFF時
  $("input[type='checkbox']").on('change', function(){

      let row = $(this).closest('tr');
      let isChecked = $(this).prop('checked');

      row.find('.user_name_select, .hospital_select').prop('disabled', isChecked);

  });

  $('.preview-page #form').on('submit', function(e){

      let error = false;

      $('.start_distance').each(function(index){

          let start = parseInt($(this).val());
          let end   = parseInt($('.end_distance').eq(index).val());

          if (start > end) {
              error = true;
          }
      });

      if (error) {

        e.preventDefault(); // ← これが本命

          //alert('A'); //でる
          flashImage(10, 200,'./image/40041.webp','./image/40042.webp');
          //alert('B'); //でる

          alertMessage("終業距離は開始距離より<br>大きい値を入力してください");

          syncRowDisabledState();
      }
  });
/*-------------------------------------------------------------------------------------*/
  $('caption').on('click',function(){

    $(this).children('span').toggleClass("active");

    if($(this).children('span').hasClass('active')){

      //alert("A");

      $(this).children('span').text('＋');

    }else{

      //alert("B");

      $(this).children('span').text('ー');

    }

    $(this).next('tbody').toggle();

  });

//------------------------------------------------------------------------------------------------------------------------------

  $('.preview-page caption:last').on('click',function(){
    //alert('last');
    $(this).toggleClass("on");
    if($(this).hasClass("on")){
      $(this).css('marginBottom','160px');
    }else{
      $(this).css('marginBottom','0');
    }
  });

  /*-------------------------------------------------------------------------------------*/

  if (fileName === "dashboard") {

    sessionStorage.removeItem('open_rows');

  }

  /*-------------------------------------------------------------------------------------*/

  if($w < 768){

        let count = parseInt(sessionStorage.getItem('open_rows')) || 0;

        let num = count;

        //console.log(count);

        // ① 全部閉じる（0以外）
        for (let i = 1; i <= 13; i++) {
          $('.input_area_c' + i).hide();
          $('.input_area_t' + i).hide();
        }

        // ② 保存分だけ開く
        for (let i = 1; i <= count; i++) {
          $('.input_area_c' + i).show();
          $('.input_area_t' + i).show();
        }
       
        $('.addition_button').on('click',function(){

          num++;
          for(var i = 1; i <= 14; i++){
            if(num == i){
              $('.input_area_c' + i).css('display','block');
              $('.input_area_t' + i).css('display','block');
            }
          }

        // ★ここ修正
        sessionStorage.setItem('open_rows', num);

        });
  }
  /*-------------------------------------------------------------------------------------*/

  $(".departure").on('click', function() {
      var now = new Date();

      //例: 7時18分 → "7:18"
      //"07:18" じゃないとブラウザが弾くエラー。必ず2桁にする
      var h = now.getHours().toString().padStart(2, '0'); // 2桁にする
      var m = now.getMinutes().toString().padStart(2, '0'); // 2桁にする

      var his = h + ':' + m;

      $(this).val(his);
  });
/*-------------------------------------------------------------------------------------*/
/* errorCheck
-------------------------------------------------------------------------------------*/
  var ErrorCheck = [];

  $('.start').on('blur',function(){
    //alert('a');

    var startTime = $(this).val();

    var endTime = $(this).parent().next().children().val();

    if(endTime != ''){

      if(startTime <= endTime){

        //alert('正');
        ErrorCheck = -1;

      }else{

        //alert('誤');
        alertMessage("開始時刻・終了時刻を正しく選択してください");

        ErrorCheck = 0;

      }
    }
  });
/*-------------------------------------------------------------------------------------*/
  $('.end').on('blur',function(){

    //alert('a');
    var endTime = $(this).val();

    var startTime = $(this).parent().prev().children().val();

    //if(startTime != '00:00'){
    if(startTime != ''){

      if(startTime <= endTime){

        //alert('正');
        ErrorCheck = -1;

      }else{
        //alert('誤');
        alertMessage("開始時刻・終了時刻を正しく選択してください");

        ErrorCheck = 0;

      }
    }
  });
/*-------------------------------------------------------------------------------------*/
  $('.end_distance').on('focus',function(){

    if ($(this).val().length === 0) {

      $(this).css('backgroundColor','#fff');

    }

  });
/*-------------------------------------------------------------------------------------*/

  $(document).ready(function() {
      // ページ読み込み時に updateStartDistance を実行
      $('#ymd, #car').trigger('change');
  });

  $('#ymd, #car').on('change', updateStartDistance);

/*-------------------------------------------------------------------------------------*/
// flashImage1

  function flashImage(times, interval,image1,image2) {
      const img = $('.conf').children('img');
      for (let i = 0; i < times; i++) {
          setTimeout(() => {
              img.attr('src', i % 2 === 0 ? image1 : image2);
          }, i * interval);
      }
  }
/*-------------------------------------------------------------------------------------*/
  // alertMessage

  function alertMessage(text){
    $('#overflow').show();
    $('#overflow').children().children('p').html(text);
    $('.closeBtn').on('click',function(){
      $('#overflow').hide();
    });
  }
/*-------------------------------------------------------------------------------------*/
  $('.end_distance').on('blur',function(){

    if ($(this).val().length === 0) {

      $(this).css('backgroundColor','#F9CAA7');

    }else{

      $(this).css('backgroundColor','#fff');

    }

    if($(this).val().match(/^\d+(?:.\d+)?$/)){

      // 数値の時
      $(this).css('backgroundColor','#fff');

    } else {

      // それ以外
      $(this).css('backgroundColor','#F9CAA7');

      // 使い方：10回切り替え、200msごと
      flashImage(10, 200,'./image/4041.webp','./image/40412.webp');
      alertMessage("走行距離は半角数字で正しく入力してください");
    }
  });
/*-------------------------------------------------------------------------------------*/
  $('.end_distance').on('change',function(){

    var end_distance = $(this).val();

    $(this).val('');

    $(this).val(end_distance);

  });


/*------------------------------------------------------------------------*/








































/*------------------------------------------------------------------------*/
  //登録完了しました
  if(success == "insert"){
    // 使い方：10回切り替え、200msごと
    flashImage(10, 200,'./image/4041.webp','./image/40412.webp');
    alertMessage("登録完了しました");

    setTimeout(function(){
      location.href = './dashboard'; // Laravel
    },3000);
  }
/*------------------------------------------------------------------------*/
  // Laravel
  if(success == "update"){
    flashImage(10, 200,'./image/4041.webp','./image/40412.webp');
    alertMessage("更新完了しました");

    setTimeout(function(){
      location.href = './dashboard'; // Laravel
    },3000);
  }
/*------------------------------------------------------------------------*/
  // Laravel
  if(error == "car"){
      flashImage(10, 200,'./image/40041.webp','./image/40042.webp');
      alertMessage("乗降車を選択してください");
      return false; 
  }
/*------------------------------------------------------------------------*/
  // Laravel
  if(error == "no_data"){
      flashImage(10, 200,'./image/40041.webp','./image/40042.webp');
      alertMessage("該当するデータはありません");
      return false; 
  }
/*------------------------------------------------------------------------*/
  // Laravel
  if(error == "no_check"){
      flashImage(10, 200,'./image/40041.webp','./image/40042.webp');
      alertMessage("点検項目は全てチェックしてください");
      return false; 
  }
/*------------------------------------------------------------------------*/
  // Laravel
  if(success == "delete"){
      flashImage(10, 200,'./image/40432.webp','./image/40431.webp');
      alertMessage("削除完了しました");
      return false; 
  }
/*------------------------------------------------------------------------*/
  // Laravel
  if(error == "start_distance"){
      flashImage(10, 200,'./image/40041.webp','./image/40042.webp');
      alertMessage("始業距離を入力してください");
      return false; 
  }
/*------------------------------------------------------------------------*/
  // Laravel
  if(error == "validationError"){
      flashImage(10, 200,'./image/40041.webp','./image/40042.webp');
      alertMessage("項目を正しく入力してください");
      return false; 
  }
/*------------------------------------------------------------------------*/
  // Laravel
  $('.post_btn').on('click', function (e) {

    let isError = false;

    $('.row').each(function (index) {

      const user = $(this).find('.user_name_select').val() || '';
      const startTime = $(this).find('.start').val() || '';
      const endTime = $(this).find('.end').val() || '';
      const hospital_select = $(this).find('.hospital_select').val() || '';

      let hasAnyInput = false;
      let isError = false;

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
/*---------------------------------------------------------------------------*/
});