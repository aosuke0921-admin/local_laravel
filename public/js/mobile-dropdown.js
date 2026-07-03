/*
document.addEventListener('DOMContentLoaded', () => {
  if (window.innerWidth > 768) return;

  const open1 = document.querySelector('.open_1');
  const open2 = document.querySelector('.open_2');

  const userBtn = document.querySelector('.user_name_select');
  const destBtn = document.querySelector('.user_name_selects');

  const openWindowItems = document.querySelectorAll('.open_window li:not(.cap)');

  // ------------------------
  // 初期状態
  // ------------------------
  openWindowItems.forEach(el => {
    el.style.display = 'none';
  });

  // ------------------------
  // 共通関数（状態リセット）
  // ------------------------
  const resetOpen = (openEl) => {
    if (!openEl) return;

    openEl.classList.remove('active');

    openEl.querySelectorAll('li:not(.cap)').forEach(li => {
      li.style.display = 'none';
    });

    openEl.querySelectorAll('li.cap').forEach(li => {
      li.style.display = 'block';
    });
  };

  // ------------------------
  // 利用者select
  // ------------------------
  if (userBtn) {
    userBtn.addEventListener('click', () => {

      resetOpen(open2);

      if (open2) {
        open2.style.display = 'none';
      }

      if (open1) {
        open1.style.display = 'block';

        resetOpen(open1);
      }
    });
  }

  // ------------------------
  // 行き先select
  // ------------------------
  if (destBtn) {
    destBtn.addEventListener('click', () => {

      resetOpen(open1);

      if (open1) {
        open1.style.display = 'none';
      }

      if (open2) {
        open2.style.display = 'block';

        resetOpen(open2);
      }
    });
  }

  // ------------------------
  // 利用者（open_1）
  // ------------------------
  if (open1) {
    open1.addEventListener('click', function () {

      const isActive = open1.classList.contains('active');

      document.querySelectorAll('.open_1').forEach(el => {
        if (el !== open1) {
          resetOpen(el);
        }
      });

      open1.classList.toggle('active');

      open1.querySelectorAll('li:not(.cap)').forEach(li => {
        li.style.display = open1.classList.contains('active') ? 'block' : 'none';
      });
    });
  }

  // ------------------------
  // 行き先（open_2）
  // ------------------------
  if (open2) {
    open2.addEventListener('click', function () {

      const isActive = open2.classList.contains('active');

      document.querySelectorAll('.open_2').forEach(el => {
        if (el !== open2) {
          resetOpen(el);
        }
      });

      open2.classList.toggle('active');

      open2.querySelectorAll('li:not(.cap)').forEach(li => {
        li.style.display = open2.classList.contains('active') ? 'block' : 'none';
      });
    });
  }

  // ------------------------
  // 項目選択（open_window）
  // ------------------------
  document.querySelectorAll('.open_window li:not(.cap)').forEach(li => {
    li.addEventListener('click', (e) => {
      e.stopPropagation();

      document.querySelectorAll('.open_window ul').forEach(ul => {
        ul.classList.remove('active');
      });

      document.querySelectorAll('.open_window li:not(.cap)').forEach(item => {
        item.style.display = 'none';
      });

      document.querySelectorAll('.open_window li.cap').forEach(item => {
        item.style.display = 'block';
      });
    });
  });
});
*/

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