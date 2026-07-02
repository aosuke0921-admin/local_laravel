// All javaScript
//------------------------------------------------------------------------------------------------------------------------
function initDestination() {
    // 行き先表示・非表示

        const destinationWrap = document.querySelector('.destination_wrap');
        const dest = document.querySelector('.destination');

        const mode = document.body.dataset.mode;
        const id = new URL(window.location.href).searchParams.get('id');

        if (!destinationWrap || !dest) return;

        const isSupport = (mode === 'support');
        const isSpecial = (id == 1);

        if (isSupport && isSpecial) {
            destinationWrap.style.display = 'none';
            dest.value = '';
            dest.disabled = true;
        } else {
            destinationWrap.style.display = 'block';
            dest.disabled = false;
        }
}
//------------------------------------------------------------------------------------------------------------------------
function initValidation() {
        //バリデーション
        // =========================
        // 初期化（これはOK）
        // =========================
        document.querySelectorAll('[id^="error_"]').forEach(el => {
            el.textContent = '';
            el.style.display = 'none';
        });

        const form = document.querySelector('form');

        if (!form) return; // フォームがないページでもエラーにならない。

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
        user?.addEventListener('click', () => {

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

            const mode = document.body.dataset.mode;
            const isCancel = mode === 'support' || mode === 'boarding';

            ok = checkSelect(user, eUser, '利用者を選択してください') && ok;

            // ★ここ分岐
            if (!isCancel) {
                ok = checkSelect(destination, eDestination, '行き先を選択してください') && ok;
            }

            ok = checkSelect(client, eClient, '依頼者を選択してください') && ok;
            ok = checkSelect(receptionist, eReceptionist, '受付者を選択してください') && ok;
            ok = checkSelect(place, ePlace, '場所を選択してください') && ok;

            if (!ok) {
                e.preventDefault();
            }
        });
}
//------------------------------------------------------------------------------------------------------------------------
function initDestinationList() {
    const records = window.records ?? [];

    document.querySelectorAll('.open_1 li').forEach(li => {
        li.addEventListener('click', function () {

            const user = li.dataset.user;
            const select = document.querySelector('.destination');

            if (!select) return;

            select.innerHTML = '<option value="">選択してください</option>';

            const seen = new Set();

            records.forEach(row => {
                if (row.user !== user) return;

                const key = row.destination + '|' + (row.pickup_location ?? '');
                if (seen.has(key)) return;
                seen.add(key);

                let value = row.destination;
                let label = row.destination;

                if (row.pickup_location) {
                    value = row.destination + '←→' + row.pickup_location;
                    label = value;
                }

                const option = document.createElement('option');
                option.value = value;
                option.textContent = label;

                select.appendChild(option);
            });

        });
    });
}
//------------------------------------------------------------------------------------------------------------------------
function initUserNotes() {
    const userSelect = document.querySelector('.user_name_select');
    const attention = document.querySelector('.attention');
    const close_btn = document.querySelector('.close_btn');
    const error = document.getElementById('error_user_name');

    if (!userSelect || !attention) return;

    userSelect.addEventListener('click', function () {

        const selected = userSelect.options[userSelect.selectedIndex];

        const notes = selected?.dataset?.notes ?? '';

        attention.value = notes;

        if (error) error.style.display = 'none';
    });

    close_btn.addEventListener('click', function () {

        const errorText = error?.textContent ?? "";

        //console.log(errorText.length);

        if(!userSelect.value){

            if(errorText.length === 0){

                error.style.display = 'none';

            }else{

                error.style.display = 'block';

            }

        }else if(errorText){

            if (error) error.style.display = 'none';

        }
    });
}
document.addEventListener('DOMContentLoaded', () => {

    initDestination();
    initValidation();
    initDestinationList();
    initUserNotes();

});