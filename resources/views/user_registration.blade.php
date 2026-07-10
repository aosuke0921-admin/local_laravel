
<!doctype html>
<html>
<head>
<meta charset="utf-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0, minimum-scale=1.0">
<script type="text/javascript" charset="UTF-8"></script>
<script src="https://code.jquery.com/jquery-3.6.0.min.js" integrity="sha256-/xUj+3OJU5yExlq6GSYGSHk7tPXikynS7ogEvDej/m4=" crossorigin="anonymous"></script>
<title>利用者登録</title>
<script src="https://cdnjs.cloudflare.com/ajax/libs/jquery-csv/1.0.21/jquery.csv.min.js"></script>

{{-- ---------------------------------------------------------------------------------------- --}}

<script src="{{ asset('js/sessionMonitor.js') }}?v={{ time() }}" charset="utf-8"></script>

<script src="{{ asset('js/system.js') }}?v={{ time() }}"></script>

<script src="{{ asset('js/userValidation.js') }}?v={{ time() }}"></script>

{{-- ---------------------------------------------------------------------------------------- --}}
<link href="{{ asset('css/style.css') }}?id=1389655283" rel="stylesheet" type="text/css">
</head>
<body class="user_registration">
    <form action="{{ route('user_registration.post') }}" method="post">
        @csrf
        <div class="wrap">
            <div class="wrap__inner">
                <div class="f_box">
                <table>
                    <tr>
                        <th class="tit">利用者登録</th>
                        <td>
                            (姓)
                            <input type="text" class="input ja js-check" name="user1" value="{{ old('user1') }}" placeholder="(例) 三重">
                            <div class="error_msg" id="error_user1">
                                @error('user1')
                                    {{ $message }}
                                @enderror
                            </div>
                        </td>
                        <td>
                            フリガナ
                            <input type="text" class="input kana js-kana" name="user_hurigana1" value="{{ old('user_hurigana1') }}" placeholder="(例) ミエ">
                            <div class="error_msg" id="error_user_hurigana1">
                                @error('user_hurigana1')
                                    {{ $message }}
                                @enderror
                            </div>
                        </td>
                        <td>
                            (名)
                            <input type="text" class="input ja js-check" name="user2" value="{{ old('user2') }}" placeholder="(例) 太郎">
                            <div class="error_msg" id="error_user2">
                                @error('user2')
                                    {{ $message }}
                                @enderror
                            </div>
                        </td>
                        <td>
                            フリガナ
                            <input type="text" class="input kana js-kana" name="user_hurigana2" value="{{ old('user_hurigana2') }}" placeholder="(例) タロウ">
                            <div class="error_msg" id="error_user_hurigana2">
                                @error('user_hurigana2')
                                    {{ $message }}
                                @enderror
                            </div>
                        </td>
                        <td>
<!------------------------------------------------------------------------------------------------------------>
                            区分
                            <select name="classification" class="select">

                                <option value="">選択してください</option>
                                <option value="介護保険">介護保険</option>
                                <option value="障害福祉">障害福祉</option>
                                <!-- <option value="保険外">保険外</option> -->

                            </select>

                            <div class="error_msg" id="error_classification">
                                @error('classification')
                                    {{ $message }}
                                @enderror
                            </div>
                        </td>
                        <td>
<!------------------------------------------------------------------------------------------------------------>

                            <div class="support_txt">
                                支援上の留意点
                                <textarea name="support_textarea" class="support_textarea">{{ old('support_textarea') }}</textarea>
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