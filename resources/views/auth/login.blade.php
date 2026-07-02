<!doctype html>
<html>
<head>
<meta charset="utf-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0, minimum-scale=1.0">
<title>logute</title>
<link rel="shortcut icon" href="./image/favicon.ico" type="image/x-icon"><!--favicon pc-->
<link rel="apple-touch-icon" href="./image/apple-touch-icon.png" sizes="180x180"><!--favicon iOS-->
<link rel="manifest" href="/manifest.json?v=2">
<link rel="icon" type="image/png" href="./image/android-touch-icon.png" sizes="192x192"><!--favicon Android-->
<link rel="stylesheet" href="{{ asset('css/style.css') }}">
<script type="text/javascript" charset="UTF-8"></script>
<script src="https://code.jquery.com/jquery-3.6.1.min.js" crossorigin="anonymous"></script>
<script>

$(function(){

    //ブックマーク時URL変更
    history.replaceState('','','/post');

});
</script>

<style>
  .grecaptcha-badge {
    visibility: hidden;
  }
</style>

@include('layouts.pwa')

</head>
<body class="login_page_body">

    <div class="login_page">
        <div class="inner">
            <form id="actionForm" method="POST" action="{{ route('login') }}">
                @csrf
                <input type="hidden" name="recaptcha_token" class="recaptcha_token">

                <table>
                    <tr>
                    <th>Smile Heart<span>運行日報記録</span></th>
                    </tr>
                    <tr>
                        <td>
                            <input type="text" name="user_login" placeholder="ユーザー名" required><br>
                            <input type="password" name="password" placeholder="パスワード" required>
                            
                            @if ($errors->any())
                                <div style="color:red;">
                                    {{ $errors->first() }}
                                </div>
                            @endif

                        </td>
                    </tr>
                </table>
                <div class="button">
                    <input type="submit" value="ログイン">
                </div>
            </form>
        </div>
    </div>

<script>
window.RECAPTCHA_SITE_KEY = "{{ config('recaptcha.site_key') }}";
window.RECAPTCHA_PAGE = "login";
</script>
<script src="https://www.google.com/recaptcha/api.js?render={{ config('recaptcha.site_key') }}" async defer></script>
<script src="{{ asset('js/recaptcha-login.js') }}?v={{ time() }}"></script>

</body>
</html>