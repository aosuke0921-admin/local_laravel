function saveForm() {
    let formData = {};

    $('input, select, textarea').each(function () {

        let name = this.name;
        if (!name) return;

        let type = this.type || '';
        let val = this.value;

        if (type === 'checkbox') {
            formData[name] = this.checked ? 1 : 0;

        } else if (type === 'time') {

            formData[name] = val || '';

            let parts = val.split(':');
            if (parts.length >= 2) {
                val =
                    parts[0].padStart(2, '0') + ':' +
                    parts[1].slice(0, 2);
            }

            formData[name] = val;

        } else {
            formData[name] = val;
        }
    });

    localStorage.setItem('post_form', JSON.stringify(formData));
}

//------------------------------------------------------------------------------------------------

function bindFormEvents() {

    let timer;

    $(document).on('input', 'input, textarea', function () {
        clearTimeout(timer);
        timer = setTimeout(saveForm, 300);
    });

    $(document).on('change', 'select, input[type="checkbox"], input[type="time"]', saveForm);
}

//------------------------------------------------------------------------------------------------

function restoreForm() {

    let raw = localStorage.getItem('post_form');
    if (!raw) return;

    let data;
    try {
        data = JSON.parse(raw);
    } catch (e) {
        console.error(e);
        return;
    }

    $('input, select, textarea').each(function () {

        let name = this.name;
        if (!name || !(name in data)) return;

        let type = this.type;
        let val = data[name];

        if (type === 'checkbox') {
            this.checked = val == 1;

        } else if (type === 'time') {

            if (val) {
                let parts = val.split(':');
                if (parts.length >= 2) {
                    val =
                        parts[0].padStart(2, '0') + ':' +
                        parts[1].slice(0, 2);
                }
            }

            this.value = val;

        } else {
            this.value = val;
        }
    });

    setTimeout(function () {
        $('select').each(function () {
            let name = this.name;
            if (!name || !(name in data)) return;

            $(this).val(data[name]).trigger('change');
        });
    }, 300);
}

//------------------------------------------------------------------------------------------------

function bindFormSubmit() {
    $(document).on('submit', 'form', function () {
        localStorage.removeItem('post_form');
    });
}

//------------------------------------------------------------------------------------------------

$(function () {
    // フォーム関連のイベントをまとめてひも付ける（登録する）
    bindFormEvents();

    // フォームの状態を復元する
    restoreForm();

    // フォームの submit（送信）イベントを登録する
    bindFormSubmit();
});

//------------------------------------------------------------------------------------------------


/*
function initForm() {

    let timer;

    function saveForm() {
        let formData = {};

        $('input, select, textarea').each(function () {

            let name = this.name;
            if (!name) return;

            let type = this.type || '';
            let val = this.value;

            if (type === 'checkbox') {
                formData[name] = this.checked ? 1 : 0;

            } else if (type === 'time') {

                formData[name] = val || '';

                let parts = val.split(':');
                if (parts.length >= 2) {
                    val =
                        parts[0].padStart(2, '0') + ':' +
                        parts[1].slice(0, 2);
                }

                formData[name] = val;

            } else {
                formData[name] = val;
            }
        });

        localStorage.setItem('post_form', JSON.stringify(formData));
    }

    // =====================
    // イベント（重要）
    // =====================
    $(document).on('input', 'input, textarea', function () {
        clearTimeout(timer);
        timer = setTimeout(saveForm, 300);
    });

    $(document).on('change', 'select, input[type="checkbox"], input[type="time"]', saveForm);

    // =====================
    // 復元（ここが改善ポイント）
    // =====================
    let raw = localStorage.getItem('post_form');
    if (!raw) return;

    let data = {};
    try {
        data = JSON.parse(raw);
    } catch (e) {
        console.error(e);
        return;
    }

    $('input, select, textarea').each(function () {

        let name = this.name;
        if (!name || !(name in data)) return;

        let type = this.type || '';
        let val = data[name];

        if (type === 'checkbox') {
            this.checked = val == 1;

        } else if (type === 'time') {

            if (val) {
                let parts = val.split(':');
                if (parts.length >= 2) {
                    val =
                        parts[0].padStart(2, '0') + ':' +
                        parts[1].slice(0, 2);
                }
            }

            this.value = val;

        } else {
            this.value = val;
        }
    });

    setTimeout(function () {
        $('select').each(function () {
            let name = this.name;
            if (!name || !(name in data)) return;

            $(this).val(data[name]).trigger('change');
        });
    }, 300);

    // =====================
    // submit削除
    // =====================
    $(document).on('submit', 'form', function () {
        localStorage.removeItem('post_form');
    });
}

$(function () {
    initForm();
});*/