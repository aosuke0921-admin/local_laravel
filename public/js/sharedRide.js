$(function(){
    function controlSharedRide() {
        $('.classification').each(function () {

            let $row = $(this).closest('tr');
            let $check = $row.find('.sharedRide');

            if ($(this).val().trim() === '保険外') {
                $check.prop('checked', false);
                $check.prop('disabled', true);
            } else {
                $check.prop('disabled', false);
            }

        });
    }

    $(document).on('change', '.sharedRide', function () {

        const $row = $(this).closest('tr');

        // ★これだけでOK
        calcPrice($row);

        //total.js内の関数を実行▼
        total_amount();
        //total.js内の関数を実行▲

    });

    // ★ 初期表示時に実行・保険外ならチェックボックス選択不可にする
    controlSharedRide();
});