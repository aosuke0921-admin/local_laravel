/*-------------------------------------------------------------------------------------*/
/* errorCheck
-------------------------------------------------------------------------------------*/
$(function(){
    var ErrorCheck = [];

    $('.start').on('blur',function(){
        //alert('a');

        var startTime = $(this).val();

        var endTime = $(this).parent().next().children().val();

        if(endTime != ''){

        if(startTime <= endTime){

            //alert('正');
            ErrorCheck = -1;

        }else{

            //alert('誤');
            alertMessage("開始時刻・終了時刻を正しく選択してください");

            ErrorCheck = 0;

        }
        }
    });
    //----------------------------------------------------------------------------------------
    $('.end').on('blur',function(){

        //alert('a');
        var endTime = $(this).val();

        var startTime = $(this).parent().prev().children().val();

        //if(startTime != '00:00'){
        if(startTime != ''){

        if(startTime <= endTime){

            //alert('正');
            ErrorCheck = -1;

        }else{
            //alert('誤');
            alertMessage("開始時刻・終了時刻を正しく選択してください");

            ErrorCheck = 0;

        }
        }
    });
    //----------------------------------------------------------------------------------------
    $(".departure").on('click', function() {
        var now = new Date();

        //例: 7時18分 → "7:18"
        //"07:18" じゃないとブラウザが弾くエラー。必ず2桁にする
        var h = now.getHours().toString().padStart(2, '0'); // 2桁にする
        var m = now.getMinutes().toString().padStart(2, '0'); // 2桁にする

        var his = h + ':' + m;

        $(this).val(his);
    });
});
/*-------------------------------------------------------------------------------------*/
/* 上下大きくスワイプ・ローディング後・復元
-------------------------------------------------------------------------------------*/
// フォーム保存
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
// 自動保存（遅延）-------------------------------------------------------
let timer;

$(document).on('input', 'input, textarea', function () {
    clearTimeout(timer);
    timer = setTimeout(saveForm, 300);
});

$(document).on('change', 'select, input[type="checkbox"], input[type="time"]', function () {
    saveForm();
});

 // 復元 -------------------------------------------------------
$(function () {

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
    //setTimeout(function () {
        //$('input').not('[type="time"]').trigger('change');
    //}, 400);
});
// 送信後削除 -------------------------------------------------------
$('form').on('submit', function () {
    localStorage.removeItem('post_form');
});