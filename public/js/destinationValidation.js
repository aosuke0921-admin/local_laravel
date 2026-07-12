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