// jQuery + javaScript
//----------------------------------------------------------------------------------------
$(function(){

  window.isInit = true;

  $.ajaxSetup({
    cache: false
  });

  window.fileName = window.location.pathname.split("/").pop();

});