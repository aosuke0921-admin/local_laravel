<!doctype html>
<html>
<head>
<meta charset="utf-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>キャンセル受付・乗降支援選択</title>

<!-- Hot Reload -->
@viteReactRefresh

<!-- 使用ファイル読み込み -->
@vite('resources/js/pop_select.jsx')

<link href="{{ asset('css/style.css') }}" rel="stylesheet">
</head>
<body class="reservation_edit">

    <div id="pop_select"></div>

    <x-recaptcha />

</body>
</html>