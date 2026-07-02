// All javaScript
//----------------------------------------------------------------------------------------
//カレンダーアイコンをクリックしたら日付入力欄を開く
//----------------------------------------------------------------------------------------
window.addEventListener('DOMContentLoaded', () => {

    const icon = document.querySelector('.date_icon');
    const input = document.querySelector('.date_input');

    if (!icon || !input) return;

    icon.addEventListener('click', () => {
        if (input.showPicker) {
            input.showPicker();
        } else {
            input.click();
        }
    });

});