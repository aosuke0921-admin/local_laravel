// All javaScript
/*-------------------------------------------------------------------------------------*/
/* time_validation_check
-------------------------------------------------------------------------------------*/
document.addEventListener('DOMContentLoaded', function () {

   let ErrorCheck = 0;

   document.querySelectorAll('.start').forEach(el => {

        el.addEventListener('blur', function () {

            // startTime
            const startTime = this.value;

            // endTime（親 → 次の要素 → input）
            const endTime = this.parentElement
            ?.nextElementSibling
            ?.querySelector('input')?.value;

            if (!endTime) return;

            if (startTime <= endTime) {
                // 正常
                ErrorCheck = -1;
            } else {
                // 異常
                alertMessage("開始時刻・終了時刻を正しく選択してください");
                ErrorCheck = 0;
            }
        });
    });

    //----------------------------------------------------------------------------------------
    document.querySelectorAll('.end').forEach(el => {

        el.addEventListener('blur', function () {

            // endTime
            const endTime = this.value;

            // startTime（親 → 前の要素 → input）
            const startTime = this.parentElement
            ?.previousElementSibling
            ?.querySelector('input')?.value;

            if (!startTime) return;

            if (startTime <= endTime) {
                // 正常
                ErrorCheck = -1;
            } else {
                // 異常
                alertMessage("開始時刻・終了時刻を正しく選択してください");
                ErrorCheck = 0;
            }
        });
    });
    //----------------------------------------------------------------------------------------
    document.querySelectorAll('.departure').forEach(el => {

        el.addEventListener('click', function () {

            const now = new Date();
            // 時（2桁）
            const h = String(now.getHours()).padStart(2, '0');
            // 分（2桁）
            const m = String(now.getMinutes()).padStart(2, '0');
            // "HH:MM"形式
            const his = `${h}:${m}`;
            // 値をセット
            this.value = his;
        });
    });
});