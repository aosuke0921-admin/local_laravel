// 各行のチェックボックス状態を見て、同じ行の入力欄を有効/無効に揃える関数
function syncRowDisabledState() {
    $("input[type='checkbox']").each(function(){
        let row = $(this).closest('tr');
        let isChecked = $(this).prop('checked');
        row.find('.user_name_select, .hospital_select').prop('disabled', isChecked);
    });
}

$(function(){

    //if(fileName === "post" || fileName === "preview" || fileName === "delete"){
    //total.js内の関数を実行▼
    total_amount();
    total_distance();
    //total.js内の関数を実行▲
    //}
    //----------------------------------------------------------------------------------------

    syncRowDisabledState();

    // チェックON/OFF時
    $("input[type='checkbox']").on('change', function(){

        /*let row = $(this).closest('tr');
        let isChecked = $(this).prop('checked');

        row.find('.user_name_select, .hospital_select').prop('disabled', isChecked);*/

        syncRowDisabledState();

    });
});