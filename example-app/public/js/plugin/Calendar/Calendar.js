(function($) {
   $.fn.Calendar_Plugin = function() {
      /*------------------------------------------------------------*/

      //Dateオブジェクト取得
      const now = new Date();

      // 2025
      var year = now.getFullYear();

      //月の値を 0 から 11 の数値で返します。 0 が 1 月、 1 が 2月、 11 が 12 月
      var month = now.getMonth() + 1;

      //$('#calendar')←セレクタ、CreateCalendar関数を実行
      this.html(CreateCalendar(year,month));

      //$('#calendar')←先頭外に追加
      this.before('<div class="wp"><div class="yprev"> ◀ </div><div class="yy">' + year + '</div><div class="ynext"> ▶ </div><div class="prev"> ◀ </div><div class="mm">' + month + '</div><div class="next"> ▶ </div></div>');
      /*------------------------------------------------------------*/

      //前の年に戻るボタン関数
      function CalendarYPrev(){
         $('.yprev').on('click', function(){

               //年の値から1引く
               year = year - 1;

               //年のdivに値を入れる
               $('.yy').text(year);

               //0じゃないので月の値をそのままdivに入れる
               $('.mm').text(month);

            //#calendarにhtmlを入れる・CreateCalendar関数へyear,month引数の実行結果
            $("#calendar").html(CreateCalendar(year,month));

            //カレンダーのtdをクリック関数の実行
            CalendarChange();

            toDay();
         });
      }
      CalendarYPrev();
      /*------------------------------------------------------------*/
      //次の年に進むボタン関数
      function CalendarYNext(){
         $('.ynext').on('click', function(){

               //年の値に1足す
               year = year + 1;

               //年のdivに値を入れる
               $('.yy').text(year);
               
               //0じゃないので月の値をそのままdivに入れる
               $('.mm').text(month);

            //#calendarにhtmlを入れる・CreateCalendar関数へyear,month引数の実行結果
            $("#calendar").html(CreateCalendar(year,month));

            //カレンダーのtdをクリック関数の実行
            CalendarChange();

            toDay();
         });
      }
      CalendarYNext();      
      /*------------------------------------------------------------*/
      //前の月に戻るボタン関数
      function CalendarPrev(){
         $('.prev').on('click', function(){

            //月の値から1引く
            month = month - 1;

            //0だったら12に置き換え
            if(month == 0){
               month = 12;

               //月のdivに12入れる
               $('.mm').text(12);

               //年の値から1引く
               year = year - 1;

               //年のdivに値を入れる
               $('.yy').text(year);
            }else{
               //0じゃないので月の値をそのままdivに入れる
               $('.mm').text(month);
            }

            //#calendarにhtmlを入れる・CreateCalendar関数へyear,month引数の実行結果
            $("#calendar").html(CreateCalendar(year,month));

            //カレンダーのtdをクリック関数の実行
            CalendarChange();

            toDay();
         });
      }
      //前の月に戻るボタン関数の実行
      CalendarPrev();
      /*------------------------------------------------------------*/
      //次の月に進むボタン関数
      function CalendarNext(){
         $('.next').on('click', function(){

            //月の値に1足す
            month = month + 1;
            if(month == 13){
               month = 1;

               //月のdivに1入れる
               $('.mm').text(1);

               //年の値に1足す
               year = year + 1;

               //年のdivに値を入れる
               $('.yy').text(year);
            }else{

               //13じゃないので月の値をそのままdivに入れる
               $('.mm').text(month);
            }

            //#calendarにhtmlを入れる・CreateCalendar関数へyear,month引数の実行結果
            $("#calendar").html(CreateCalendar(year,month));

            //カレンダーのtdをクリック関数の実行
            CalendarChange();

            toDay();
         });
      }

      //次の月に進むボタン関数の実行
      CalendarNext();
      /*------------------------------------------------------------*/
      function CalendarChange(){
         $('table td').on('click',function(){
            var selectDay = $(this).text();
            if(selectDay != ""){
               //数値でない場合true
               if(!isNaN(selectDay)){
                  var y = String(year);
                  var m = String(month);
                  var d = String(selectDay);
                  selectYmd = y + '年' + m + '月' + d + '日';

                  //$('.ymd').val(selectYmd);

                  $('.ymd').val(selectYmd).trigger('change');

                  $('.cl_toggle').hide();
               }
            }
         });
         $('table td').hover(
           function (){
            // 要素にマウスを載せたときの処理
            var get_text = $(this).text();
            if(get_text == "" || get_text == "月" || get_text == "火" || get_text == "水" || get_text == "木" || get_text == "金" || get_text == "土" || get_text == "日"){
               $(this).css('cursor','crosshair');
            }
         });
      }
      CalendarChange();
      /*------------------------------------------------------------*/   
      function CalendarShow(){
         $('.ymd').on('click', function(){
            $('.cl_toggle').show();
         });
      }
      CalendarShow();
      /*------------------------------------------------------------*/
      function CreateCalendar(year,month){
          const weeks = ['日', '月', '火', '水', '木', '金', '土'];
          //取得する月の1日の情報
          const startDateOfMonth = new Date(year, month - 1, 1);
          //取得する月の最終日の情報
          const lastDateOfMonth = new Date(year, month, 0);
          //1日の曜日
          const startDay = startDateOfMonth.getDay();
          //取得する月のカレンダーの行数
          const Calendarline = CalendarLine(startDay,lastDateOfMonth.getDate());
          var CalendarElement = "<table>";
          //カレンダーの曜日の行を作成
          CalendarElement += "<tr>";
          for (let w = 0; w < 7; w++) {
              CalendarElement += "<td>" + weeks[w] + "</td>";
          }
          CalendarElement += "</tr>";
          var currentDate = 1;
          for (let line = 0; line < Calendarline;line++){
              CalendarElement += "<tr>";
              for (let w = 0; w < 7; w++){
                  //カレンダーの一行目の場合は1日の曜日より前の枠は空欄にする
                  //最終日を超えた場合の枠も空欄にする
                  if ((line == 0 && w < startDay)){
                      CalendarElement += "<td></td>";
                      
                  }
                  else if (currentDate > lastDateOfMonth.getDate()){
                      CalendarElement += "<td></td>"
                      currentDate++
                  }
                  else{
                      CalendarElement += "<td>"+currentDate+"</td>";
                      currentDate++
                  }
                  
              }
              CalendarElement += "</tr>";
          }
          return CalendarElement;
      }
      /*------------------------------------------------------------*/
      // //1日の曜日とその月の日数を引数にしてその月の行を返す関数
      function CalendarLine(startDay,lastDateOfMonth){
          //(例)1日が(金)で最終日が31日の場合6行になる。→31日(日)
          if (startDay + lastDateOfMonth >= 36){
              return 6;
          }
          //うるう年でない2月の1日が日曜日の場合
          else if (startDay + lastDateOfMonth <= 28){
              return 4
          }
          //それ以外はすべて5行
          else {
              return 5
          }
      }
      /*------------------------------------------------------------*/
      //追加
      /*------------------------------------------------------------*/
      function toDay(){
         var i = 0;
         $('#calendar table td').each(function(index,element,d) {
               var dt = new Date();
               var y = new Date().getFullYear();
               var m = new Date().getMonth() + 1;
               var d = new Date().getDate();

               //------------------------------
               var today__y = $('.yy').text();
               var today__m = $('.mm').text();
               var today__d = $(this).text();
               //------------------------------

               if(today__y == y && today__m == m && today__d == d){
                  $(this).addClass('today');
                  $('.today').css('backgroundColor','#F2B46B');
               }
            i++;
         });
      }
      toDay();
      /*------------------------------------------------------------*/
      /*function calendar__removeClass__addClass(){
         $('#calendar table td').on('click',function(){
            $('#calendar table td').removeClass('today');
            $('#calendar table td').css('backgroundColor','#FFFFFF');
            $(this).addClass('today');
            $('.today').css('backgroundColor','#F2B46B');
         });
      }
      calendar__removeClass__addClass();*/
      /*------------------------------------------------------------*/
   };
})(jQuery);