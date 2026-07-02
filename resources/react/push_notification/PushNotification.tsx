import { useEffect, useState } from 'react';

import './PushNotification.css';


function urlBase64ToUint8Array(
    base64String: string
) {

    const padding =
        '='.repeat(
            (4 - base64String.length % 4) % 4
        );

    const base64 =
        (base64String + padding)
            .replace(/-/g, '+')
            .replace(/_/g, '/');

    const rawData =
        window.atob(base64);

    return Uint8Array.from(
        [...rawData].map(
            c => c.charCodeAt(0)
        )
    );
}

//----------------------------------------------------------------------------------------------------

export default function PushNotification() {

    const [permission, setPermission] = useState<NotificationPermission | null>(null);
    const [loading, setLoading] = useState(false);

    // 初期化
    useEffect(() => {
        if (typeof window !== 'undefined') {
            setPermission(Notification.permission);
        }
    }, []);

    // select_page の表示切り替え
    useEffect(() => {

        const selectPage = document.querySelector<HTMLElement>('.select_page');

        if (!selectPage || permission === null) {
            return;
        }

        if (permission === 'granted') {

            selectPage.style.display = 'flex';

        } else {

            selectPage.style.display = 'none';

        }

    }, [permission]);

    const handleClick = async () => {

        setLoading(true);

        try {

            const result = await Notification.requestPermission();

            console.log('permission', result);

            const reg =
                await navigator.serviceWorker.ready;

            console.log('reg', reg);

            const sub =
                await reg.pushManager.getSubscription();

            console.log('sub', sub);

            if (!sub) {

                const subscription =
                    await reg.pushManager.subscribe({

                        userVisibleOnly: true,

                        applicationServerKey:
                            urlBase64ToUint8Array(
                                (window as any).VAPID_PUBLIC_KEY
                            )
                    });

                console.log(
                    'subscription',
                    subscription
                );

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

                console.log(
                    'SAVE RESPONSE STATUS =',
                    res.status
                );
            }

            setPermission(result);

        } finally {

            setLoading(false);

        }
    };

    // 初回ロード中
    if (permission === null) {
        return null;
    }

    // 許可済みなら通知画面は非表示
    if (permission === 'granted') {
        return null;
    }

    return (
        <div className="notification">
            <div className="character">
            <img
                src="/image/wanko_haru8.png"
                alt="わんこ"
            />

            <p>
                PUSH通知を許可しますか？
                <br />
                情報のお知らせを受け取るため
                <br />
                通知を許可してください。。
            </p>

            <button
                id="pushBtn"
                onClick={handleClick}
                disabled={loading}
            >
                {loading ? '処理中...' : 'PUSH通知をオン'}
            </button>
        </div>
        </div>
    );
}