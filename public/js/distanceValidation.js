$(function(){
    $('.preview-page #form').on('submit', function(e){

        let error = false;

        $('.start_distance').each(function(index){

            let start = parseInt($(this).val());
            let end   = parseInt($('.end_distance').eq(index).val());

            if (start > end) {
                error = true;
            }
        });

        if (error) {

        e.preventDefault(); // ← これが本命

            //page-feedback.js内の関数を実行▼
            flashImage(10, 200,'./image/40041.webp','./image/40042.webp');
            alertMessage("終業距離は開始距離より<br>大きい値を入力してください");
            //page-feedback.js内の関数を実行▲

            syncRowDisabledState();
        }
    });
});