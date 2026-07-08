$(function(){
    if(fileName == "reservation_search"
    || fileName == "boarding_reservation"
    || fileName == "user_destination_registration"
    || fileName == "preview"
    || fileName == "post"
    || fileName == "archive"
    || fileName == "month-archive"
    || location.pathname.includes("/edit")){

    $('.open_window').hide();

    //------------------------------------------------------------------------------------------------

    $('.user_name_select,.user_name_selects').on('click',function(){

    let $w = $(window).width(); //現在のwindow幅を取得

    //月報
    if(fileName == "month-archive"){

        $('.month_archive .prevPage a').hide();
        $('.month_archive .prevPage').hide();

    }

    if($w > 768){

        $('.open_window').css('margin','70px auto 0');

        $('.open_window ul').removeClass('active');

        $('.open_window ul').css({
        display:'block'
        });

        $('.open_window ul').css('display','block');

        $('.open_window ul').children('li').css('display','none');

        const className = $(this).attr('class');

        if (className.includes('selects')) {
            //alert('A');
            $('.open_window ul.open_2 li.cap').css('display','block'); 
        }else{
            //alert('B');
            $('.open_window ul.open_1 li.cap').css('display','block'); 
        }

        //------------------------------------------------------------------------------------------------

        $('.open_window ul').on('click',function(){  

        $('.open_window').css('margin','0 auto');

        $('.open_window ul').removeClass('active');

        $('.open_window ul').toggleClass('active');

        if($('.open_window ul').hasClass('active')){

            // あーん・表示
            $('.open_window ul').css('display','none');

        }

        $(this).css({
            display:'block',
        });

        $('.open_window ul li').css('display','none');

        $(this).children('li:nth-child(n+2)').show();

        $(this).children('li').css('display','inline-block');

        //数を取得
        const count = $(this).children('li').length;

        //頭文字だけマイナス
        const count_check = count - 1;

        if(count_check == 0){

            $('.open_window').append('<div class="nasi">該当する検索結果はありません</div>');
            
            $('.nasi').css({
            fontFamily:'"ヒラギノ丸ゴ Pro W4","ヒラギノ丸ゴ Pro","Hiragino Maru Gothic Pro","ヒラギノ角ゴ Pro W3","Hiragino Kaku Gothic Pro","HG丸ｺﾞｼｯｸM-PRO","HGMaruGothicMPRO"',
            width:'100%',
            height:'90vh',
            lineHeight:'90vh',
            position:'fixed',
            top:'0',
            textAlign:'center',
            fontSize:'50px'
            });

        }
        $('.open_window ul li.cap').css('display','none');
        }); // open_window ul onclick
        
    } // 768以上 ここまで

    //var tr_id = $(this).parent().parent('tr').attr('id');

    $('.open_window').css({
        opacity: 1,
        display: 'flex'}).hide().fadeIn(500);

    $('#wrapper').hide();

    if(fileName == "archive" || fileName == "achievements.php" || fileName == "month-archive"){

        $('.month_select').hide();

    }

    $(this).parent().addClass('active');

    $(this).hide();

    var user_select = $(this);

    //------------------------------------------------------------------------------------------------
    if(
        fileName == "reservation_search"
        || fileName == "boarding_reservation"
        || fileName == "user_destination_registration"
        || fileName == "preview"
        || fileName == "post"
        || fileName == "archive"
        || fileName == "month-archive"
        || location.pathname.includes("/edit")
    ){

        var li_tag = $('.open_window').find('li:not(.cap)');

    }else if(fileName == "month-archive"){

        $('.page404').hide();

    }

    li_tag.on('click',function(){

        const text = $(this).text().trim();

        //alert(text);

        if(text == "—"){

            $('#wrapper').show();
            user_select.show();
            $('.open_window').hide();
            
            return;
        }

        let $target;

        if (fileName == "post" || fileName == "preview" ) {
            $target = $('.active').find('.user_name_select');
            $('.active').removeClass('active');
        } else {
            const $row = $(this).closest('tr');
            $target = $row.find('.user_name_select');
        }

        if ($target.length) {

            $target.val(text);

            // ★ 初期だけ止める
            //if (!isInit) {
            if (!window.isInit) {
                $target.trigger('change');
            }
        }

        if(fileName == "archive" || fileName == "month-archive"){

        $('.month_select').show();

        }

        $('#wrapper').show();

        user_select.show();

        $('.open_window').hide();

        //月報
        if(fileName == "month-archive"){

        $('.month_archive .prevPage a').show();
        $('.month_archive .prevPage').show();

        }

        if(fileName == "reservation_search" || fileName == "archive" || fileName == "month-archive"){

        const text = $(this).text().trim();

        $('.user_name_select').val(text).trigger('change'); // かわらない       

        }else if(
        fileName == "boarding_reservation"
        || fileName == "user_destination_registration"
        || location.pathname.includes("/edit")
        ){

        let type = $(this).data('type');
        let val = $(this).data('value') || $(this).text().trim();

        if(type === 'user'){

            const $select = $('.user_name_select');

            $select.val(val);

            const notes = $select.find(':selected').data('notes') ?? '';

            $('.attention').val(notes);

        }else if(type === 'destination'){
            $('.user_name_selects').val(val).trigger('change');
        }
        }

        $('.user_name_select').parent().removeClass('active');

    });
    });
    //------------------------------------------------------------------------------------------------
    $(document).on('click', '.open_window ul li:not(.cap)', function () {

    const val = $(this).text().trim();

    const isUser = $(this).closest('ul').hasClass('open_1');
    const isDest = $(this).closest('ul').hasClass('open_2');

    // ★ rowを「クリック元から取る」方式に変更
    const $row = $(this).closest('.open_window').data('target-row');

    if (!$row || $row.length === 0) return;

    if (isUser) {
        $row.find('.user_name_select').val(val);
    }

    if (isDest) {
        $row.find('.destination_select').val(val);
    }

    $('.open_window').hide();
    });
    //------------------------------------------------------------------------------------------------
    }/*  if(fileName == "reservation_search"・・・・・・*/
});