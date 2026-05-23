(function($) {
	$.fn.loginError_Plugin = function(){
	/*------------------------------------------------------------*/
		function loginError(){
			// URLのクエリ文字列を取得
			const queryString = window.location.search;

			// URLSearchParamsオブジェクトを作成してクエリ文字列を解析
			const params = new URLSearchParams(queryString);

			// 特定のパラメータの値を取得
			const paramValue = params.get('id');

			if(paramValue == "error1" || paramValue == "error2"){

				if(paramValue == "error1"){

					$('.kaipotikun2').show(300);					

				}else if(paramValue == "error2"){

					$('.kaipotikun1').show(300);					

				}

				document.querySelector('.login_page').animate(
					[
						{
							offset: 0.00,
							transform: 'translate(0, 0)'
						},
						{
							offset: 0.05,
							transform: 'translate(-5%, 0)'
						},
						{
							offset: 0.10,
							transform: 'translate(5%, 0)'
						},
						{
							offset: 0.15,
							transform: 'translate(-5%, 0)'
						},
						{
							offset: 0.20,
							transform: 'translate(5%, 0)'
						},
						{
							offset: 0.25,
							transform: 'translate(-5%, 0)'
						},
						{
							offset: 0.30,
							transform: 'translate(0, 0)'
						},
						{
							offset: 1.00,
							transform: 'translate(0, 0)'
						}
					],{	duration: 1200 }
				);
			}
		}
	loginError();
	/*------------------------------------------------------------*/
	};
})(jQuery);