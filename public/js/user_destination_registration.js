// All javaScript
//------------------------------------------------------------------------------------------------------------------------
document.addEventListener('DOMContentLoaded', function () {
    // =========================
    // エラー初期化
    // =========================
    document.querySelectorAll('[id^="error_"]').forEach(el => {
        el.textContent = '';
        el.style.display = 'none';
    });

    const form = document.querySelector('form');

    const user = document.querySelector('[name="user_name"]');
    const destination = document.querySelector('[name="destination"]');
    const distance = document.querySelector('[name="distance"]');

    const eUser = document.getElementById('error_user_name');
    const eDestination = document.getElementById('error_destination');
    const eDistance = document.getElementById('error_distance');

    // =========================
    // エラー表示
    // =========================
    function setError(box, message) {
        if (!box) return;

        box.textContent = message;
        box.style.display = message ? 'block' : 'none';
    }

    // =========================
    // selectチェック（submitのみ）
    // =========================
    function checkSelect(input, errorBox, message) {

        const value = (input.value || '').trim();

        if (value === '') {
            setError(errorBox, message);
            return false;
        }

        setError(errorBox, '');
        return true;
    }

    // =========================
    // 数値チェック（blur + submit）
    // =========================
    function checkNumber(input, errorBox, message) {

        const value = (input.value || '').trim();

        // 空チェック（必要なら）
        if (value === '') {
            setError(errorBox, '半角数字で正しく入力してください');
            return false;
        }

        // 半角数字チェック（整数 or 小数）
        if (!/^[0-9]+(\.[0-9]+)?$/.test(value)) {
            setError(errorBox, message);
            return false;
        }

        setError(errorBox, '');
        return true;
    }

    // =========================
    // blur（distanceだけ）
    // =========================
    if (distance) {
        distance.addEventListener('blur', function () {
            checkNumber(distance, eDistance);
        });
    }
    // =========================
    // submitチェック（全体）
    // =========================
    form.addEventListener('submit', function (e) {

        let ok = true;

        ok = checkSelect(user, eUser, '利用者を選択してください') && ok;
        ok = checkSelect(destination, eDestination, '行き先を選択してください') && ok;
        ok = checkNumber(distance, eDistance, '半角数字で正しく入力してください') && ok;

        if (!ok) {
            e.preventDefault(); // ←これが超重要
        }
    });

});