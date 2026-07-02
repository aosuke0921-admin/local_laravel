  $(function(){
  /* sp / プルダウンメニュー
  ------------------------------------------------------------------------------*/
  if ($(window).width() <= 768) {

    // 初期
    $('.open_1 li:not(.cap)').hide();
    $('.open_2 li:not(.cap)').hide();

    // 利用者select押下
    $('.user_name_select').on('click', function () {


    $('.open_2').hide(); // ←追加
    $('.open_1').show(); // ←追加

      $('.open_2')
        .removeClass('active');

      $('.open_2 li:not(.cap)')
        .hide();

      $('.open_1 li.cap')
        .show();

    });

    // 行き先select押下
    $('.user_name_selects').on('click', function () {


    $('.open_1').hide(); // ←追加
    $('.open_2').show(); // ←追加

      $('.open_1')
        .removeClass('active');

      $('.open_1 li:not(.cap)')
        .hide();

      $('.open_2 li.cap')
        .show();

    });

    // 利用者
    $('.open_1').on('click', function () {

      const $this = $(this);

      $('.open_1')
        .not($this)
        .removeClass('active')
        .children('li:not(.cap)')
        .hide();

      $this.toggleClass('active');

      $this.children('li:not(.cap)')
        .toggle($this.hasClass('active'));

    });

    // 行き先
    $('.open_2').on('click', function () {

      const $this = $(this);

      $('.open_2')
        .not($this)
        .removeClass('active')
        .children('li:not(.cap)')
        .hide();

      $this.toggleClass('active');

      $this.children('li:not(.cap)')
        .toggle($this.hasClass('active'));

    });

    // 項目選択
    $('.open_window li:not(.cap)').on('click', function (e) {

      e.stopPropagation();

      $('.open_window ul')
        .removeClass('active');

      $('.open_window li:not(.cap)')
        .hide();

      $('.open_window li.cap')
        .show();

    });

  } // spプルダウンメニュー end
});