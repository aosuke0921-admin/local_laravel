/**
 * フォーム保存
 */
function saveForm() {
    let formData = {};

$('input, select, textarea').each(function () {

    let name = this.name;
    if (!name) return;

    let type = this.type || '';
    let val = this.value; // ★最初に取る

    if (type === 'checkbox') {
        formData[name] = this.checked ? 1 : 0;

    } else if (type === 'time') {

        console.log('A');

        //if (!val || val.length < 4) return;

         formData[name] = val || '';

        console.log('B');

        let parts = val.split(':');
        if (parts.length >= 2) {
            val =
                parts[0].padStart(2, '0') + ':' +
                parts[1].slice(0, 2);
        }

        formData[name] = val;

    } else {

        console.log('C');

        formData[name] = val;
    }
});

    localStorage.setItem('post_form', JSON.stringify(formData));
}


/**
 * 自動保存（遅延）
 */
let timer;
//$(document).on('input change', 'input, select, textarea', function () {
$(document).on('input', 'input, textarea', function () {
    clearTimeout(timer);
    timer = setTimeout(saveForm, 300);
});

$(document).on('change', 'select, input[type="checkbox"], input[type="time"]', function () {
    saveForm();
});



/**
 * 復元
 */
$(function () {


//console.log(JSON.parse(localStorage.getItem('post_form')));



    let raw = localStorage.getItem('post_form');
    if (!raw) return;

    let data = {};
    try {
        data = JSON.parse(raw);
    } catch (e) {
        console.error('JSON error', e);
        return;
    }

    // ① 値復元
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

    // ② select再適用
    setTimeout(function () {
        $('select').each(function () {
            let name = this.name;
            if (!name || !(name in data)) return;

            $(this).val(data[name]).trigger('change');
        });
    }, 300);

    // ③ 再計算系
    /*setTimeout(function () {
        $('input').not('[type="time"]').trigger('change');
    }, 400);*/
});


/**
 * 送信後削除
 */
$('form').on('submit', function () {
    localStorage.removeItem('post_form');
});