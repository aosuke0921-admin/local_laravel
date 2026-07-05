$(function(){


  $('.close_btn').on('click', function () {

    // ウィンドウ閉じる
    $('.open_window').hide();

    // 背景戻す
    $('#wrapper').show();

    // 月報系戻す
    if (
      fileName == "archive" ||
      fileName == "achievements.php" ||
      fileName == "month-archive"
    ) {
      $('.month_select').show();
    }

    // ボタン復活
    $('.user_name_select, .user_name_selects, .cap').show();

    // active解除
    $('.open_window ul').removeClass('active');

    // 一旦全部閉じる
    $('.open_window ul li').hide();

    // 見出しだけ戻す
    $('.open_window ul li.cap').show();

  });


});