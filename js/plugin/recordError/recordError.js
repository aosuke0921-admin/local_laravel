(function($) {
	$.fn.recordError_Plugin = function(){
	/*------------------------------------------------------------*/
		function recordError(){

		      $("td:last-child span").each(function(index, element) {

		          var eleval = $(element).text();

		          if(eleval == 0){

		            $(this).parent('td').parent('tr').children().css('backgroundColor','#f9b4b4');

		          }

		      });
		}
	recordError();
	/*------------------------------------------------------------*/
	};
})(jQuery);