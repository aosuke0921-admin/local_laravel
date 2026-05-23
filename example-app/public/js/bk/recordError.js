(function($) {
	$.fn.recordError_Plugin = function(){

		function recordError(){

            //alert('a');

            $('tr').each(function () {

                const text = $(this).find('td').text();

                if (text.includes('現地3,000円発生') || text.includes('当日1,500円発生')) {

                    $(this).css('background-color', 'rgb(228, 239, 163)');

                }else if(text == 0){

                    $(this).css('background-color', '#f9b4b4');

                }

            });
		}
	    recordError();
	};
})(jQuery);