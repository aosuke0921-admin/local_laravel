// =====================
// VAPID変換
// =====================
function urlBase64ToUint8Array(base64String) {

    // Base64文字列の長さを4の倍数にするためのパディング追加
    const padding = '='.repeat((4 - base64String.length % 4) % 4);

    // URLセーフBase64を通常のBase64へ変換
    const base64 = (base64String + padding)
        .replace(/-/g, '+')
        .replace(/_/g, '/');

    // Base64デコード
    const rawData = window.atob(base64);

    // Uint8Arrayへ変換して返す
    return Uint8Array.from([...rawData].map(c => c.charCodeAt(0)));
}


// =====================
// バッジ初期化
// =====================

// localStorageから保存済みバッジ数を取得
// なければ0
let badgeCount = Number(localStorage.getItem('badgeCount') || 0);


// =====================
// SWメッセージ受信（重要：即登録）
// =====================
if ('serviceWorker' in navigator) {

    // Service Workerからのmessageイベントを登録
    navigator.serviceWorker.addEventListener(
        'message',
        handleSWMessage
    );

    console.log('SW message listener ready');
}


// =====================
// SW → フロント受信処理
// =====================
function handleSWMessage(event) {

    // SWから届いたデータ確認
    console.log('SW message:', event.data);

    // BADGE以外は無視
    if (event.data?.type !== 'BADGE') return;

    // 現在の保存値を取得して+1
    badgeCount = Number(localStorage.getItem('badgeCount') || 0) + 1;

    // 保存
    localStorage.setItem('badgeCount', badgeCount);

    console.log('badge updated:', badgeCount);

    // App Badge API対応ブラウザのみ実行
    if ('setAppBadge' in navigator) {

        navigator.setAppBadge(badgeCount)
            .then(() => {

                console.log(
                    'setAppBadge executed:',
                    badgeCount
                );
            })
            .catch(console.error);
    }
}


// =====================
// SW登録
// =====================
if ('serviceWorker' in navigator) {

    // ページロード後にService Worker登録
    window.addEventListener('load', async () => {

        try {

            const reg =
                await navigator.serviceWorker.register('/sw.js');

            console.log(
                'SW registered:',
                reg.scope
            );

        } catch (e) {

            console.error(
                'SW register failed:',
                e
            );
        }
    });
}


// =====================
// Push購読
// =====================
$(document).on('click', '#pushBtn', async function () {

    // Bladeから渡されたVAPID公開鍵確認
    console.log(
        'VAPID KEY =',
        window.VAPID_PUBLIC_KEY
    );

    try {

        // 通知許可ダイアログ表示
        const permission =
            await Notification.requestPermission();

        console.log('permission:', permission);

        // 許可されなければ終了
        if (permission !== 'granted') return;

        // SW準備完了待ち
        const reg =
            await navigator.serviceWorker.ready;

        // 既存購読取得
        let subscription =
            await reg.pushManager.getSubscription();

        // 未購読なら新規購読
        if (!subscription) {

            subscription =
                await reg.pushManager.subscribe({

                    userVisibleOnly: true,

                    applicationServerKey:
                        urlBase64ToUint8Array(
                            window.VAPID_PUBLIC_KEY
                        )
                });

            console.log('subscribe success');
        }

        // 購読内容確認
        console.log(
            'subscription =',
            subscription
        );

        // Laravelへ購読情報保存
        const res = await fetch(
            '/save-subscription',
            {
                method: 'POST',

                headers: {
                    'Content-Type':
                        'application/json'
                },

                body: JSON.stringify(
                    subscription
                )
            }
        );

        // レスポンス確認用ログ
        console.log(
            'content-type =',
            res.headers.get('content-type')
        );

        console.log(
            'RAW TEXT =',
            await res.text()
        );

        console.log(
            'SAVE RESPONSE STATUS =',
            res.status
        );

        // JSONなら取得
        const data =
            await res.json().catch(
                () => null
            );

        console.log(
            'SAVE RESPONSE BODY =',
            data
        );

        console.log(
            'subscription saved'
        );

    } catch (e) {

        console.error(e);
    }
});


// =====================
// バッジクリア
// =====================
window.clearBadge = function () {

    // カウント初期化
    badgeCount = 0;

    // localStorage更新
    localStorage.setItem(
        'badgeCount',
        0
    );

    // App Badge API対応時のみ実行
    if ('clearAppBadge' in navigator) {

        navigator.clearAppBadge();
    }

    console.log('badge cleared');
};