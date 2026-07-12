$(function(){
    
    $('.select').change(function(){

    //if (isInit) return;
    if (window.isInit) return;

    var className = $(this).attr('class');
      
    if(className == 'select user_name_select'){ //利用者

        var keyword1 = $(this).val();

        var keyword2 = $(this).parent().next().next().next().next().children().next().val();

        var $row = $(this).closest('tr');

        var distance__val = $row.find('.distance'); // 変数はそのまま

        var price__val    = $row.find('.price');

        if (!distance__val.val()) {
            distance__val.val(0);
        }

        if (!price__val.val()) {
            price__val.val(0);
        }
        //total.js内の関数を実行▼
        total_amount();
        total_distance();
        //total.js内の関数を実行▲
    }

    //------------------------------------------------------------------------------------------------------------------------------

    if(className == 'select hospital_select'){ //行先
        var keyword2 = $(this).val();
        $(this).prev().val(keyword2);

        // 同じ行の利用者 select 値を取得
        var keyword1 = $(this).closest('tr').find('.user_name_select').val();

        // 同じ行の distance と price を取得
        var $row = $(this).closest('tr');
        var distance__val = $row.find('.distance');
        var price__val    = $row.find('.price');
            
        // 距離取得、なければ0
        const distance = parseFloat(distance__val.val() || 0);
        // 区分取得
        const classification = $row.find('.classification').val();
        // 乗合取得
        const $check = $row.find('.sharedRide');
        // 距離が0と違う　　区分が保険外
        if (distance !== 0 && classification === '保険外') {
            // 乗合選択不可ON
            $check.prop('disabled', true).prop('checked', false);
        } else {
            // 乗合選択不可OFF
            $check.prop('disabled', false);
            syncRowDisabledState();
        }

        // ★ 既に値があるときは触らない
        if (!distance__val.val()) {
            distance__val.val(0);
        }

        if (!price__val.val()) {
            price__val.val(0);
        }
    }

    //------------------------------------------------------------------------------------------------------------------------------

    if(className == 'select user_name_select'){ //利用者
        var $row = $(this).closest('tr');

        if(fileName == "post"){
            // 区分選択を有効化
            $row.find('.classification').prop('disabled', false).css('opacity',1);
        }
    }

    //------------------------------------------------------------------------------------------------------------------------------

    if(className == 'select hospital_select'){ //行先
        var $row = $(this).closest('tr');

        hospital_select_next_checkbox = $row.find('.sharedRide');
        hospital_select__price = $row.find('.price').val();
    }
  });
});