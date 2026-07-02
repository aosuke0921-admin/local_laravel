<!doctype html>
<html>
<head>
<meta charset="utf-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0, minimum-scale=1.0">
<title>ダッシュボード</title>
<script type="text/javascript" charset="UTF-8"></script>
<script src="https://code.jquery.com/jquery-3.6.1.min.js" crossorigin="anonymous"></script>

{{-- ---------------------------------------------------------------------------------------- --}}
<script src="{{ asset('js/system.js') }}?v={{ time() }}" charset="utf-8"></script>
<script src="{{ asset('js/page-feedback.js') }}?v={{ time() }}" charset="utf-8"></script>
<script src="{{ asset('js/total.js') }}?v={{ time() }}" charset="utf-8"></script>
<script src="{{ asset('js/mobile-dropdown.js') }}?v={{ time() }}" charset="utf-8"></script>
{{-- ---------------------------------------------------------------------------------------- --}}
<script src="{{ asset('js/Calendar/Calendar.js') }}?v={{ time() }}" charset="utf-8"></script>
<link rel="stylesheet" href="{{ asset('js/Calendar/Calendar.css') }}?v={{ time() }}" charset="utf-8">
{{-- ---------------------------------------------------------------------------------------- --}}
<link href="{{ asset('css/style.css') }}?v={{ time() }}" rel="stylesheet" type="text/css">


@viteReactRefresh

@vite('resources/react/index.tsx')

@include('layouts.pwa')

<script>

//--------------------------------------------------------------------

// 🔵 PWAのアプリアイコンに未読バッジ（通知数）を表示する
// auth()->user()->badge_count の値をそのままアイコンのバッジとして設定
// ※対応ブラウザのみ（未対応の場合は無視される）

if ('setAppBadge' in navigator) {
    navigator.setAppBadge({{ auth()->user()->badge_count }});
}

//--------------------------------------------------------------------
// 🧹 一時保存していた投稿フォームデータを削除
// ページリロード後や投稿完了後に残っている入力内容をクリアするため
// localStorageに保存していた "post_form" を完全に消去

localStorage.removeItem('post_form');

//--------------------------------------------------------------------
</script>
</head>
<body>

<div class="react" data-component="PushNotification"></div>

<?php /*----------------------------------------------------------------------------------------------------*/ ?>

  <div class="select_page">

    @include('components.notification_bell')

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
                        {{-- パラメータがついている時はパラメータの値を優先 --}}
                        <input
                        id="ymd"
                            class="ymd"
                            placeholder="（例）{{ $today }}"
                            size="20"
                            type="text"
                            name="dates"
                            value="{{ request('dates') ?? ($dates ?? '') }}"
                            readonly
                        >

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

                      {{-- パラメータがついている時はパラメータの値を優先 --}}
                      <select name="car" id="car">
                          <option value="">選択してください</option>

                          @foreach($cars as $c)
                              <option
                                  value="{{ $c }}"
                                  {{ request('car', $car) == $c ? 'selected' : '' }}
                              >
                                  {{ $c }}
                              </option>
                          @endforeach
                      </select>
                      </dd>
                    </dl>
                  </div>
                  <div class="start_k">
                    <dl>
                      <dt>登録は始業距離を入力<span><b>登録されている始業距離が消えている場合は、</b>更新ボタンで再確認してください</span></dt>
                      <dd>

                      {{-- パラメータがついている時はパラメータの値を優先 --}}
                      <input 
                        type="number" 
                        name="start_distance" 
                        value="{{ request('start_distance') }}"
                        id="start_distance"
                        pattern="[0-9]*"
                        inputmode="numeric"
                        step="0.1"
                      >
                        <span class="error_msg"></span>
                      </dd>
                    </dl>
                  </div>          
                  <div class="react" data-component="Reminder"></div>
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