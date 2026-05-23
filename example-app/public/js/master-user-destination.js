//------------------------------------------------------------------------------------------------------------------------
document.addEventListener('DOMContentLoaded', function () {




    // =========================
    // 初期化（これはOK）
    // =========================
    document.querySelectorAll('[id^="error_"]').forEach(el => {
        el.textContent = '';
        el.style.display = 'none';
    });

    const form = document.querySelector('form');

    // =========================
    // 要素
    // =========================
    const user = document.querySelector('[name="user_name"]');
    const destination = document.querySelector('[name="destination"]');
    const client = document.querySelector('[name="client_name"]');
    const receptionist = document.querySelector('[name="receptionist"]');
    const place = document.querySelector('[name="place"]');

    // =========================
    // エラー表示領域
    // =========================
    const eUser = document.getElementById('error_user_name');
    const eDestination = document.getElementById('error_destination');
    const eClient = document.getElementById('error_client_name');
    const eReceptionist = document.getElementById('error_receptionist');
    const ePlace = document.getElementById('error_place');

    // =========================
    // エラー表示
    // =========================
    function setError(box, message) {
        if (!box) return;
        box.textContent = message;
        box.style.display = message ? 'block' : 'none';
    }

    // =========================
    // チェック
    // =========================
    function checkSelect(input, box, message) {
        const value = (input?.value || '').trim();

        if (value === '') {
            setError(box, message);
            return false;
        }

        setError(box, '');
        return true;
    }

    // =========================
    // ⭐ changeでエラー消す（ここが重要）
    // =========================
    user?.addEventListener('change', () => {
        if (user.value) setError(eUser, '');
    });

    destination?.addEventListener('change', () => {
        if (destination.value) setError(eDestination, '');
    });

    client?.addEventListener('change', () => {
        if (client.value) setError(eClient, '');
    });

    receptionist?.addEventListener('change', () => {
        if (receptionist.value) setError(eReceptionist, '');
    });

    place?.addEventListener('change', () => {
        if (place.value) setError(ePlace, '');
    });

    // =========================
    // submit
    // =========================
    form.addEventListener('submit', function (e) {

        let ok = true;

        const isCancel = document.querySelector('#submit_value')?.value === 'cancel_mode';

        ok = checkSelect(user, eUser, '利用者を選択してください') && ok;

        // ★ここ分岐
        if (!isCancel && destination && destination.value !== '') {
            ok = checkSelect(destination, eDestination, '行き先を選択してください') && ok;
        }

        ok = checkSelect(client, eClient, '依頼者を選択してください') && ok;
        ok = checkSelect(receptionist, eReceptionist, '受付者を選択してください') && ok;
        ok = checkSelect(place, ePlace, '場所を選択してください') && ok;

        if (!ok) {
            e.preventDefault();
        }
    });
});

$(function(){

window.App = window.App || {};
window.App.records = window.App.records || [];
const records = window.App.records;



    let mode = '';

    $('.user_name_selects').on('click', function () {
        
        const label = $(this).parent().prev().text().trim();

        mode = (label === '利用者') ? 'user' : 'destination';

        $('.open_1, .open_2').hide();

        if (mode === 'user') {
            $('.open_1').show();
        } else {
            $('.open_2').show();
        }

        $('.open_window').fadeIn(200);
    });

    //const records = @json($user_destination_records);

    function renderDestination(user, currentValue = null) {

        const $select = $('.destination');

        $select.empty().append('<option value="">選択してください</option>');

        const seen = new Set();

        (records ?? []).forEach(function (row) {

            if ((row.user ?? '').trim() !== (user ?? '').trim()) return;

            const key = (row.destination ?? '') + '|' + (row.pickup_location ?? '');

            if (seen.has(key)) return;
            seen.add(key);

            let value = row.destination ?? '';
            let label = row.destination ?? '';

            if (row.pickup_location) {
                value = row.destination + '←→' + row.pickup_location;
                label = value;
            }

            $select.append(`<option value="${value}">${label}</option>`);
        });

        // ★ここが本体
        if (currentValue) {
            $select.val(currentValue);
        }
    }

    // =========================
    // 利用者変更時
    // =========================
    $('.user_name_select').on('change', function () {

        const user = $(this).val();
        const notes = $(this).find(':selected').data('notes');

        $('.attention').val(notes ?? '');

        renderDestination(user);
    });

    // =========================
    // 🔥 初期表示（ここが追加ポイント）
    // =========================
    const initUser = $('.user_name_select').val();

    // ★ DB側の初期値（行き先）
    const initDestination = $('.destination').data('init'); // or hidden input

    if (initUser) {
        renderDestination(initUser, initDestination);
    }

});