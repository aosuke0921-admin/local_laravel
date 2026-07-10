$(function(){

  //------------------------------------------------------------------------------------------------

  $(document).on('submit', 'form[id^="update_"]', function () {

      let initial = $('.initial_tab.active').data('initial');
      let mode = $('input[name="mode"]:checked').val();

      $(this).find('input[name="initial"]').remove();
      $(this).find('input[name="mode"]').remove();

      $('<input>').attr({
          type: 'hidden',
          name: 'initial',
          value: initial
      }).appendTo(this);

      $('<input>').attr({
          type: 'hidden',
          name: 'mode',
          value: mode
      }).appendTo(this);

  });

  $(document).on('change', 'input[name="mode"]', function () {

      let mode = $(this).val();
      let initial = $('.initial_tab.active').data('initial');

      $.ajax({
          url: '/master/change-mode',
          method: 'GET',
          data: {
              mode: mode,
              initial: initial
          },
          success: function (res) {

              $('.table_head').html(res.head);
              $('.table_area').html(res.body);
          }
      });
  });

  $(document)
  .off('click', '.initial_tab')
  .on('click', '.initial_tab', function (e) {

      e.preventDefault();

      let $tab = $(this);
      let initial = $tab.data('initial');
      let mode = $('input[name="mode"]:checked').val();

      if (!initial || !mode) {
          alert('条件が不足しています');
          return false;
      }

      $.ajax({
          url: '/master/change-mode',
          method: 'GET',
          data: {
              mode: mode,
              initial: initial
          },
          success: function (res) {

              // 👇データなし判定（ら・わ対策ここ）
              if (!res || !res.body || res.body.trim() === '') {

                  alert(`「${initial}」には該当データがありません`);

                  return; // ←ここで完全ストップ（タブも変えない）
              }

              // ✅ ここで初めてタブ切り替え
              $('.initial_tab').removeClass('active');
              $tab.addClass('active');

              $('.table_head').html(res.head);
              $('.table_area').html(res.body);
          }
      });

  });

});