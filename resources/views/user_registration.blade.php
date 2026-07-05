
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

{{-- ---------------------------------------------------------------------------------------- --}}

<link href="{{ asset('css/style.css') }}?id=1389655283" rel="stylesheet" type="text/css">
<script>
document.addEventListener('DOMContentLoaded', function () {

    // フォーム全体を取得
    const form = document.querySelector('form');

    // 日本語（ひらがな・カタカナ・漢字）許可の正規表現
    const jpRegex = /^[ぁ-んァ-ン一-龥々ー　]+$/u;

    // 全角カタカナのみ許可の正規表現
    const kanaRegex = /^[ァ-ヶー　]+$/u;

    // =========================
    // 入力欄の取得
    // =========================
    const user1 = document.querySelector('[name="user1"]'); // 姓
    const user2 = document.querySelector('[name="user2"]'); // 名
    const kana1 = document.querySelector('[name="user_hurigana1"]'); // 姓フリガナ
    const kana2 = document.querySelector('[name="user_hurigana2"]'); // 名フリガナ

    const classification = document.querySelector('[name="classification"]');

    // =========================
    // エラーメッセージ表示エリア
    // =========================
    const eUser1 = document.getElementById('error_user1');
    const eUser2 = document.getElementById('error_user2');
    const eKana1 = document.getElementById('error_user_hurigana1');
    const eKana2 = document.getElementById('error_user_hurigana2');


    const eClassification = document.getElementById('error_classification');

    // =========================
    // エラー表示を統一管理する関数
    // =========================
    function setError(box, message) {

        // メッセージがある場合（エラー表示）
        if (message) {
            box.textContent = message;      // 文字を入れる
            box.style.display = 'block';    // 表示する
        } else {
            // メッセージがない場合（エラー解除）
            box.textContent = '';           // 中身を消す
            box.style.display = 'none';     // 非表示
        }
    }

    // =========================
    // 日本語チェック関数
    // =========================
    function checkJapanese(input, errorBox) {

        const value = input.value.trim(); // 前後の空白削除

        // 空 or 日本語以外ならエラー
        if (value === '' || !jpRegex.test(value)) {
            setError(errorBox, '日本語で正しく入力してください');
            return false; // NG
        }

        // 正常ならエラー消す
        setError(errorBox, '');
        return true; // OK
    }

    // =========================
    // カタカナチェック関数
    // =========================
    function checkKana(input, errorBox) {

        const value = input.value.trim(); // 前後の空白削除

        // 空 or カタカナ以外ならエラー
        if (value === '' || !kanaRegex.test(value)) {
            setError(errorBox, '全角カタカナで入力してください');
            return false; // NG
        }

        // 正常ならエラー消す
        setError(errorBox, '');
        return true; // OK
    }

    // =========================
    // 区分チェック
    // =========================
    function checkClassification(input, errorBox) {

        if (input.value === '') {
            setError(errorBox, '区分を選択してください');
            return false;
        }

        setError(errorBox, '');
        return true;
    }

    // =========================
    // blur（フォーカス外れた時）チェック
    // =========================
    user1.addEventListener('blur', () => checkJapanese(user1, eUser1));
    user2.addEventListener('blur', () => checkJapanese(user2, eUser2));

    kana1.addEventListener('blur', () => checkKana(kana1, eKana1));
    kana2.addEventListener('blur', () => checkKana(kana2, eKana2));

    // =========================
    // 初期表示時にエラー非表示にする
    // =========================
    [eUser1, eUser2, eKana1, eKana2, eClassification].forEach(box => {
        box.style.display = 'none'; // 最初は全部隠す
    });

    // =========================
    // submit時の最終チェック
    // =========================
    form.addEventListener('submit', function (e) {

        let ok = true; // 全体OKフラグ

        // 全項目チェック（1つでもNGならfalseになる）
        ok = checkJapanese(user1, eUser1) && ok;
        ok = checkJapanese(user2, eUser2) && ok;

        ok = checkKana(kana1, eKana1) && ok;
        ok = checkKana(kana2, eKana2) && ok;

        ok = checkClassification(classification, eClassification) && ok;

        // NGが1つでもあれば送信停止
        if (!ok) {
            e.preventDefault(); // フォーム送信キャンセル
        }
    });
});
</script>
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
                            フリガナ
                            <input type="text" class="input kana js-kana" name="user_hurigana1" value="{{ old('user_hurigana1') }}" placeholder="(例) ミエ">
                            <div class="error_msg" id="error_user_hurigana1">
                                @error('user_hurigana1')
                                    {{ $message }}
                                @enderror
                            </div>
                            (名)
                            <input type="text" class="input ja js-check" name="user2" value="{{ old('user2') }}" placeholder="(例) 太郎">
                            <div class="error_msg" id="error_user2">
                                @error('user2')
                                    {{ $message }}
                                @enderror
                            </div>
                            フリガナ
                            <input type="text" class="input kana js-kana" name="user_hurigana2" value="{{ old('user_hurigana2') }}" placeholder="(例) タロウ">
                            <div class="error_msg" id="error_user_hurigana2">
                                @error('user_hurigana2')
                                    {{ $message }}
                                @enderror
                            </div>

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