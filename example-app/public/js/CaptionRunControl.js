  $(function(){
   
    //A
    $('span.toggle_run').on('click',function(){

        $(this).toggleClass("active");

        if($(this).hasClass('active')){

        $(this).text('＋');

        }else{

        $(this).text('ー');

        }

        $(this).closest('caption').next('tbody').toggle();

    });

    // .delete_runが存在するページだけtrue
    if($('.delete_run').length){
        //B
        $('span.delete_run').on('click', function(e){

            e.stopPropagation();

            if(!confirm('この運行を消しますか？')){
                return;
            }

            $(this).parent().next('tbody').remove();
            $(this).parent().remove();

            updateRunNumbers();

        });

        //C
        function updateRunNumbers(){

        $('caption.input_area_c').each(function(index){

            $(this).find('.run_title').text('運行' + (index + 1));

        });

        }
    }
});