// すでにリダイレクト済みかどうかのフラグ / 何回もアラートやリダイレクトが出るのを防ぐ
let redirected = false;

// セッション確認の間隔（5分）分ごとにサーバーへ確認リクエストを送る
const checkInterval = 60 * 1000; // ← 1分 / (例) 5 * 60 * 1000; // 5分

// 一定間隔で処理を繰り返す（セッション監視）
setInterval(function () {

    // サーバーにAjaxリクエストを送信
    $.ajax({

        // セッション状態を確認する専用ルート / サーバー（Laravel）に用意した「セッション確認用のURL」 / 「今ログイン状態ですか？」ってサーバーに聞いてる
        url: '/ping-session',

        // GETリクエスト
        type: 'GET',

        // ===== 通信成功時 =====
        success: function (res) {

            // res.auth が false → セッション切れ（ログアウト状態）/ かつ まだリダイレクトしていない場合
            if (!res.auth && !redirected) {

                // リダイレクト済みにする（多重防止）
                redirected = true;

                // ユーザーに確認ダイアログを表示
                if (confirm('セッションが切れました。ログイン画面へ移動しますか？')) {

                    // OKならログイン画面へ移動
                    window.location.href = '/login';
                }

            } else {

                // セッションが生きている場合
                //console.log('session alive');
            }
        },

        // ===== 通信エラー時 =====
        error: function (xhr) {

            // HTTPステータスコードで判定 / 419 → CSRFトークン切れ / 401 → 未認証（ログアウト状態）
            if ((xhr.status === 419 || xhr.status === 401) && !redirected) {

                // リダイレクト済みにする（多重防止）
                redirected = true;

                // ユーザーに確認ダイアログを表示
                if (confirm('セッションが切れました。ログイン画面へ移動しますか？')) {

                    // OKならログイン画面へ移動
                    window.location.href = '/login';
                }
            }
        }
    });

// 指定した間隔で繰り返し実行
}, checkInterval);