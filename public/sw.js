// SWファイルが読み込まれた確認
console.log('🔥 SW LOADED');

// =====================
// install / activate
// =====================

// Service Workerインストール時
self.addEventListener('install', event => {

    console.log('[SW] install');

    // 新SWを即有効化
    self.skipWaiting();
});

// Service Worker有効化時
self.addEventListener('activate', event => {

    console.log('[SW] activate');

    // 全クライアントを即制御下に置く
    event.waitUntil(
        self.clients.claim()
    );
});

// =====================
// push受信
// =====================

// Push通知受信時
self.addEventListener('push', event => {

    console.log('[SW] push received');

    // 非同期処理完了までSWを維持
    event.waitUntil(
        handlePush(event)
    );
});

// =====================
// push処理本体
// =====================
async function handlePush(event) {

    // デフォルト通知データ
    let data = {
        title: '通知',
        body: ''
    };

    // Pushペイロードが存在する場合
    if (event.data) {

        try {

            // テキスト取得
            const text =
                await event.data.text();

            // 空文字でなければJSON変換
            if (
                text &&
                text.trim() !== ''
            ) {
                data = JSON.parse(text);
            }

        } catch (e) {

            console.log(
                '[SW] JSON parse failed',
                e
            );
        }
    }

    // タイトル決定
    const title =
        data.title || '通知';

    console.log('[SW] BEFORE SHOW NOTIFICATION', data);

    // 通知表示
    await self.registration.showNotification(
        title,
        {
            body: data.body || '',

            // 通知アイコン
            //icon: '/icon-push_192.png',
            icon: '/image/icon-push_192.png',

            // Androidバッジ用
            //badge: '/icon-push_192.png',
            badge: '/image/icon-push_192.png',

            // ユーザーが閉じるまで残す
            requireInteraction: true
        }
    );

    console.log(
        '[SW] NOTIFICATION SHOWN'
    );

    // =====================
    // フロントへ通知
    // =====================

    // 開いているブラウザタブ取得
    const clientsList =
        await self.clients.matchAll({

            type: 'window',

            includeUncontrolled: true
        });

    console.log(
        '[SW] clients count =',
        clientsList.length
    );

    // 全タブへBADGEメッセージ送信
    for (const client of clientsList) {

        console.log(
            '[SW] sending message'
        );

        client.postMessage({

            type: 'BADGE',

            count: 1
        });
    }
}

// =====================
// 通知クリック
// =====================

// 通知クリック時
self.addEventListener(
    'notificationclick',
    event => {

        // 通知を閉じる
        event.notification.close();

        event.waitUntil((async () => {

            // 開いているタブ取得
            const clients =
                await self.clients.matchAll({

                    type: 'window',

                    includeUncontrolled: true
                });

            // タブがあれば最前面へ
            if (clients.length > 0) {

                clients[0].focus();
            }

            // サーバー側バッジリセット
            await fetch('/badge-reset');

        })());
    }
);