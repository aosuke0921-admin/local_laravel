(function($) {
   $.fn.DeleteCheck_Plugin = function() {
      $('.delte_btn').on('click', function(){
         var result = window.confirm('本当に削除してよろしいですか？');
         if(result == true){
           return true;
         }else{
           return false; //exit();
         }
      });

      $('.delete_btn').hide();

      $('.delete_check').on('click',function(){

         var cnt_checked = $('.delete_check:checkbox:checked').length;
         if(cnt_checked > 0){
         $('.delete_btn').show();
         $('.clone_btn').hide();
         $('.checkall_box').prop('checked', false);
         }else{
         $('.delete_btn').hide();
         $('.clone_btn').show();
         $('.checkall_box').prop('checked', false);
         }
      });

      $('.checkall_box').on('click',function(){
         if($(this).prop('checked')) {
         $('.delete_check').prop('checked', true);
         $('.delete_btn').show();
         $('.clone_btn').hide();
         }else{
         $('.delete_check').prop('checked', false);
         $('.delete_btn').hide();
         $('.clone_btn').show();
         }
      });
   };
})(jQuery);