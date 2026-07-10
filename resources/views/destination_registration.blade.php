
<!doctype html>
<html>
<head>
<meta charset="utf-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0, minimum-scale=1.0">
<script type="text/javascript" charset="UTF-8"></script>
<script src="https://code.jquery.com/jquery-3.6.0.min.js" integrity="sha256-/xUj+3OJU5yExlq6GSYGSHk7tPXikynS7ogEvDej/m4=" crossorigin="anonymous"></script>
<title>行き先登録</title>
<script src="https://cdnjs.cloudflare.com/ajax/libs/jquery-csv/1.0.21/jquery.csv.min.js"></script>
{{-- ---------------------------------------------------------------------------------------- --}}

<script src="{{ asset('js/sessionMonitor.js') }}?v={{ time() }}" charset="utf-8"></script>

<script src="{{ asset('js/system.js') }}?v={{ time() }}"></script>

{{-- ---------------------------------------------------------------------------------------- --}}
<link href="{{ asset('css/style.css') }}?id=577246838" rel="stylesheet">
<script>
document.addEventListener('DOMContentLoaded', function () {

    const form = document.querySelector('form');

    const jpRegex = /^[ぁ-んァ-ン一-龥々ー　]+$/u;
    const kanaRegex = /^[ァ-ヶー　]+$/u;

    const destination = document.querySelector('[name="destination"]');
    const kana = document.querySelector('[name="destination_hurigana"]');

    const eDestination = document.getElementById('error_destination');
    const eKana = document.getElementById('error_destination_hurigana');

    function setError(box, message) {
        if (!box) return;

        if (message) {
            box.textContent = message;
            box.style.display = 'block';
        } else {
            box.textContent = '';
            box.style.display = 'none';
        }
    }

    function checkJapanese(input, errorBox) {
        const value = input.value.trim();

        if (value === '' || !jpRegex.test(value)) {
            setError(errorBox, '日本語で正しく入力してください');
            return false;
        }
        setError(errorBox, '');
        return true;
    }

    function checkKana(input, errorBox) {
        const value = input.value.trim();

        if (value === '' || !kanaRegex.test(value)) {
            setError(errorBox, '全角カタカナで入力してください');
            return false;
        }
        setError(errorBox, '');
        return true;
    }

    // =========================
    // ⭐ blur（確実に発火させる）
    // =========================
    document.addEventListener('blur', function (e) {

        if (e.target.name === 'destination') {
            checkJapanese(destination, eDestination);
        }

        if (e.target.name === 'destination_hurigana') {
            checkKana(kana, eKana);
        }

    }, true); // ← これ重要（キャプチャ）

    // =========================
    // 初期非表示
    // =========================
    [eDestination, eKana].forEach(box => {
        if (box) box.style.display = 'none';
    });

    // =========================
    // submitチェック
    // =========================
    form.addEventListener('submit', function (e) {

        let ok = true;

        ok = checkJapanese(destination, eDestination) && ok;
        ok = checkKana(kana, eKana) && ok;

        if (!ok) {
            e.preventDefault();
        }
    });

});
</script>

</head>

<body class="destination_registration">
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