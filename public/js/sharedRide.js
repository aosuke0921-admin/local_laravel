$(function(){
    $(document).on('change', '.sharedRide', function () {

        const $row = $(this).closest('tr');

        // ★これだけでOK
        calcPrice($row);

        //total.js内の関数を実行▼
        total_amount();
        //total.js内の関数を実行▲

    });
});