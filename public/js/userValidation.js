// All javaScript
/*-------------------------------------------------------------------------------------*/
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