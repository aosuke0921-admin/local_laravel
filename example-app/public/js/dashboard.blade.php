<?php //94点 ?>
<!doctype html>
<html>
<head>
<meta charset="utf-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0, minimum-scale=1.0">
<title>ダッシュボード</title>
<script type="text/javascript" charset="UTF-8"></script>
<script src="https://code.jquery.com/jquery-3.6.1.min.js" crossorigin="anonymous"></script>
<script src="{{ asset('js/system.js') }}?v={{ time() }}" charset="utf-8"></script>
<link href="{{ asset('css/style.css') }}?v={{ time() }}" rel="stylesheet" type="text/css">
<link rel="stylesheet" href="{{ asset('js/plugin/Calendar/Calendar.css') }}">
<script src="{{ asset('js/plugin/Calendar/Calendar.js') }}"></script>
<script src="{{ asset('js/plugin/osiraseMessage/osiraseMessage.js') }}"></script>

<?php /*<script src="{{ asset('sw.js') }}" crossorigin="anonymous"></script>*/ ?>

<script>

localStorage.removeItem('post_form');

$(function(){

  $('#calendar').Calendar_Plugin();

  $('#osiraseMessage_Plugin').osiraseMessage_Plugin();

});

</script>

</head>
<body>
  <div class="select_page">
    <div class="inner">
        <form id="actionForm" action="{{ url('/dashboard') }}" method="POST">
            @csrf
            <input type="hidden" name="recaptcha_token" class="recaptcha_token">



            <table>
              <tr>
                <td>
                  <div class="unkou">
                    <dl>
                      <dt>運行日</dt>
                      <dd>
                        <input id="ymd" class="ymd" placeholder="（例）{{ $today }}" size="20" type="text" name="dates" value="{{ $dates ?? '' }}" readonly>
                        <div class="cl_toggle">
                          <div id="calendar"></div>
                        </div>
                      </dd>
                    </dl>
                  </div>
                  <div class="jyoukou">
                    <dl>
                      <dt>乗降車を選択</dt>
                      <dd>
                        <select name="car" id="car">
                            <option value="">選択してください</option>
                            @foreach($cars as $c)
                                <option value="{{ $c }}" {{ $car == $c ? 'selected' : '' }}>{{ $c }}</option>
                            @endforeach
                        </select>
                      </dd>
                    </dl>
                  </div>
                  <div class="start_k">
                    <dl>
                      <dt>登録は始業距離を入力<span><b>登録されている始業距離が消えている場合は、</b>更新ボタンで再確認してください</span></dt>
                      <dd>
                        <input 
                          type="number" 
                          name="start_distance" 
                          value="{{ request('start_distance') ?: session('start_distance') }}" 
                          id="start_distance"
                          pattern="[0-9]*"
                          inputmode="numeric"
                          step="0.1"
                        >
                        <span class="error_msg"></span>
                      </dd>
                    </dl>
                  </div>          
                  <div class="osirase"><b>お願いします<i>&#x2728;</i></b><img src="{{ asset('image/wanko_haru8.png') }}" alt=""><span>業務終了時に更新ページから<br>終業距離を入力してください</span></div>
                </td>
              </tr>
            </table>
            <div class="button">
              <div class="flex-item"><button type="submit" name="submitText" value="insert">登録</button></div>
              <div class="flex-item"><button type="submit" name="submitText" value="update">更新</button></div>
              <div class="flex-item"><button type="submit" name="submitText" value="delete">削除</button></div>
              <div class="flex-item"><button type="submit" name="submitText" value="archive">検索</button></div>
              <div class="flex-item"><button type="submit" name="submitText" value="month-archive">月報・CSV</button></div>         
              <div class="flex-item"><button type="submit" name="submitText" value="print">運行日報・印刷</button></div>
              <div class="flex-item"><button type="submit" name="submitText" value="user_registration">利用者登録</button></div>
              <div class="flex-item"><button type="submit" name="submitText" value="destination_registration">行き先登録</button></div>
              <div class="flex-item"><button type="submit" name="submitText" value="user_destination_registration">利用者・行き先・登録</button></div>
              <div class="flex-item"><button type="submit" name="submitText" value="boarding_reservation">乗降予約</button></div>
              <div class="flex-item"><button type="submit" name="submitText" value="reservation_search">乗降一覧検索</button></div>

              <?php /*<div class="flex-item"><button type="submit" name="submitText" value="cancel_boarding">キャンセル受付</button></div>*/ ?>
              <div class="flex-item"><button type="submit" name="submitText" value="pop_select">キャンセル受付</button></div>

              <div class="flex-item"><button type="submit" name="submitText" value="cancel_search">キャンセル一覧検索</button></div>

              <div class="flex-item"><button type="submit" name="submitText" value="master">マスター保守</button></div>


            </div>
          </form>
          <!--Laravelはセキュリティ（CSRF対策）のためにPOSTで行う-->
          <form method="POST" action="{{ route('logout') }}">
              @csrf
              <input type="hidden" name="recaptcha_token" class="recaptcha_token">



              <div class="flex-item"><input type="submit" name="submitText" value="ログアウト"></div>
          </form>
    </div>
  </div>
  <div id="overflow"><div class="conf"><img src="{{ asset('image/404.webp') }}"><p></p><button class="closeBtn">閉じる</button></div></div>

  <x-recaptcha />
  
</body>
</html>