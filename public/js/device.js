// All javaScript
// =============================================================================================
// 端末判定（スマホ / タブレット / PC）
// =============================================================================================
const ua = navigator.userAgent;

// スマホ判定
const isSP =
  (ua.indexOf('Android') > -1 && ua.indexOf('Mobile') > -1) || // Androidスマホ
  ua.indexOf('iPhone') > -1 ||                                // iPhone
  ua.indexOf('iPod') > -1;                                    // iPod

// タブレット判定
const isTablet =
  (ua.indexOf('Android') > -1 && ua.indexOf('Mobile') === -1) || // Androidタブレット
  ua.indexOf('iPad') > -1;                                      // iPad

// =============================================================================================
// 分岐処理
// =============================================================================================
if (isSP) {

  // ===== スマホ =====
  // スマホ用の処理を書く（今回は特になし）

} else if (isTablet) {

  // ===== タブレット =====
  // タブレット用の処理を書く（今回は特になし）

} else {

  // ===== PC =====
  // PCのみリサイズ時にリロード処理を行う

  let timer = null;

  window.addEventListener('resize', () => {
    const width = window.innerWidth;

    if (width <= 1260) {
      if (timer) clearTimeout(timer);

      timer = setTimeout(() => {
        location.reload();
      }, 300);
    }
  });
}