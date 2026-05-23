(function($) {
  $.fn.osiraseMessage_Plugin = function(){
  /*------------------------------------------------------------*/
    function osiraseMessage(){
      /*var $w = $(window).width(); //現在のwindow幅を取得
      if($w <= 768){*/
        $('.osirase img').animate({margin:'-5px auto 0 auto'},10);
        $('.osirase img').animate({margin:'0 auto -5px auto'},20);
        $('.osirase img').animate({margin:'-5px auto 0 auto'},30);
        $('.osirase img').animate({margin:'0 auto -5px auto'},40);
        $('.osirase img').animate({margin:'-5px auto 0 auto'},50);
        $('.osirase img').animate({margin:'0 auto -5px auto'},60);
        $('.osirase img').animate({margin:'-5px auto 0 auto'},70);
        $('.osirase img').animate({margin:'0 auto -5px auto'},80);
        $('.osirase img').animate({margin:'-5px auto 0 auto'},90);
        $('.osirase img').animate({margin:'0 auto -5px auto'},100);
        $('.osirase img').animate({margin:'-5px auto 0 auto'},110);
        $('.osirase img').animate({margin:'0 auto -5px auto'},120);
        $('.osirase img').animate({margin:'-5px auto 0 auto'},130);
        $('.osirase img').animate({margin:'0 auto -5px auto'},140);
        $('.osirase img').animate({margin:'0 auto 0 auto'},100);
        setTimeout(function() {
          $('.osirase b').fadeIn(1000);
        }, 1500);
        /*setTimeout(function() {
          $('.osirase b').fadeOut(1000);
        }, 3000);*/
      //}
    }
  osiraseMessage();
  /*------------------------------------------------------------*/
  };
})(jQuery);