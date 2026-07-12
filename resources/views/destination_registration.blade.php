
<!doctype html>
<html>
<head>
<meta charset="utf-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0, minimum-scale=1.0">
<script type="text/javascript" charset="UTF-8"></script>
<script src="https://code.jquery.com/jquery-3.6.0.min.js" integrity="sha256-/xUj+3OJU5yExlq6GSYGSHk7tPXikynS7ogEvDej/m4=" crossorigin="anonymous"></script>
<title>行き先登録</title>
<!--<script src="https://cdnjs.cloudflare.com/ajax/libs/jquery-csv/1.0.21/jquery.csv.min.js"></script>-->
{{-- ---------------------------------------------------------------------------------------- --}}
<script src="{{ asset('js/sessionMonitor.js') }}?v={{ time() }}" charset="utf-8"></script>
<!--<script src="{{ asset('js/ajax_config.js') }}?v={{ time() }}" charset="utf-8"></script>-->
<!--<script src="{{ asset('js/system_init.js') }}?v={{ time() }}" charset="utf-8"></script>-->
<script src="{{ asset('js/destinationValidation.js') }}?v={{ time() }}" charset="utf-8"></script>
{{-- ---------------------------------------------------------------------------------------- --}}
<link href="{{ asset('css/style.css') }}?id=577246838" rel="stylesheet">

@viteReactRefresh

@vite('resources/react/index.tsx')

</head>

<body class="destination_registration">

    <div class="react" data-component="LogoTitle"></div>

    <form action="{{ route('destination_registration.post') }}" method="post">
        @csrf
        <div class="wrap">
            <div class="wrap__inner">
                <div class="f_box">
                <table>
                    <tr>
                        <th class="tit">行き先・登録</th>
                        <td>

                            行き先
                            <input type="text" class="input ja" name="destination" value="{{ old('destination') }}" placeholder="(例) 遠山病院">

                            <div id="error_destination" class="error_msg">
                                @error('destination')
                                    {{ $message }}
                                @enderror
                            </div>
                        </td>
                        <td>

                            フリガナ
                            <input type="text" class="input kana" name="destination_hurigana" value="{{ old('destination_hurigana') }}" placeholder="(例) トオヤマビョウイン">

                            <div id="error_destination_hurigana" class="error_msg">
                                @error('destination_hurigana')
                                    {{ $message }}
                                @enderror
                            </div>

                        </td>
                    </tr>
                </table>
                </div>
                <div class="b_box">
                    <div class="prevPage no-print">
                        <input type="submit" name="submit" class="submit" value="登録">&nbsp;&nbsp;&nbsp;
                        <a href="{{ route('dashboard') }}">戻る</a>
                    </div>
                </div>
            </div>
        </div>
    </form>
<div id="overflow"><div class="conf"><img src="./image/404.webp"><p></p><button class="closeBtn">閉じる</button></div></div>

<x-recaptcha />

</body>
</html>