$(function(){

    const w = $(window).width(); //現在のwindow幅を取得

    if(w < 768){

        let count = parseInt(sessionStorage.getItem('open_rows')) || 0;

        console.log(count);

        let num = count;

        // ① 全部閉じる（0以外）
        for (let i = 1; i <= 13; i++) {
            $('.input_area_c' + i).hide();
            $('.input_area_t' + i).hide();
        }

        // ② 保存分だけ開く
        for (let i = 1; i <= count; i++) {
            $('.input_area_c' + i).show();
            $('.input_area_t' + i).show();
        }
            
        $('.addition_button').on('click',function(){

            num++;
            for(var i = 1; i <= 14; i++){
            if(num == i){
                $('.input_area_c' + i).css('display','block');
                $('.input_area_t' + i).css('display','block');
            }
            }

            // ★ここ修正
            sessionStorage.setItem('open_rows', num);

        });
    }
});