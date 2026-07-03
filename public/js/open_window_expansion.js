// All javaScript
//------------------------------------------------------------------------------------------------------------------------
// open_window 利用者・行き先 切り替え処理
function open_window_expansion() {

  // 画像・CSS・iframeなど全て読み込み完了後に実行
  window.addEventListener('load', () => {

    // PCのみ対象（768px以下は実行しない）
    if (window.innerWidth <= 768) return;

    // open_window（ポップアップ全体）
    const openWindow = document.querySelector('.open_window');

    // ボタン（利用者・行き先切替ボタン群）
    const buttons = document.querySelectorAll('.user_name_selects');

    // ボタンが存在しない場合は処理しない
    if (!buttons.length) return;

    // 各ボタンにクリックイベントを付与
    buttons.forEach(button => {

      button.addEventListener('click', function () {

        // クリックした場所の親要素 → その前のthのテキスト取得
        const thText =
          this.parentElement.previousElementSibling.textContent.trim();

        // thの文字でモード判定
        // 「利用者」なら user、それ以外は destination
        const mode = (thText === '利用者') ? 'user' : 'destination';

        // open_1（利用者）と open_2（行き先）を一旦すべて非表示
        document.querySelectorAll('.open_1, .open_2').forEach(item => {
          item.style.display = 'none';
        });

        // モードに応じて表示対象を決定
        const target = (mode === 'user') ? '.open_1' : '.open_2';

        // 対象だけ表示
        document.querySelectorAll(target).forEach(item => {
          item.style.display = 'block';
        });

        // open_window（全体ポップアップ）をフェード表示
        if (openWindow) {
          openWindow.style.opacity = 0;      // 初期は透明
          openWindow.style.display = 'flex'; // 表示状態にする

          // 少し遅らせてフェードイン開始
          setTimeout(() => {
            openWindow.style.transition = 'opacity 200ms';
            openWindow.style.opacity = 1;
          }, 10);
        }
      });
    });
  });
}

// 実行
open_window_expansion();