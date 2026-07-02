------------------------------------------------------------------------------------------
🔴　進行中 [2026.6.26] [WIP]  肥大化system.js重複箇所修正・乗合チェック更新ページバグらんように、
------------------------------------------------------------------------------------------
◯ 環境別・進行状況
logute.com / NO
test-sistem.jp / NO
GitHub / NO
------------------------------------------------------------------------------------------
🔴　進行中 [2026.6.26] [WIP]  更新ページカレンダー左右矢印で変更・追加
------------------------------------------------------------------------------------------
◯ 環境別・進行状況
logute.com / NO
test-sistem.jp / OK
GitHub / OK
------------------------------------------------------------------------------------------
🟡　[2026.0.00] [WIP]  master・利用者・利用中のチェックボックスをつける。selectでヒットをわける
------------------------------------------------------------------------------------------
◯ 環境別・進行状況
logute.com / NO
test-sistem.jp / NO
GitHub / NO
------------------------------------------------------------------------------------------
🟡　[2026.0.00] [WIP]  resources / コンポーネント名 / .css .js　分ける
------------------------------------------------------------------------------------------
現在は resources/ js  css　すべてのコンポーネント入れてる状態。それをコンポーネントごとにフォルダ分ける
------------------------------------------------------------------------------------------
◯ 環境別・進行状況
logute.com / NO
test-sistem.jp / NO
GitHub / NO
------------------------------------------------------------------------------------------
🟡　[2026.0.00] [WIP]  社員登録フォームをReactで再実装（バリデ含む）
------------------------------------------------------------------------------------------
[2026.0.00]jQuery → JavaScriptへ移行
[2026.0.00-2026.0.00]Reactコンポーネント化
[2026.0.00]動作確認(logute / sistem / local)
------------------------------------------------------------------------------------------
◯ 環境別・進行状況
logute.com / NO
test-sistem.jp / NO
GitHub / NO
------------------------------------------------------------------------------------------
🟢 [2026.6.25] [FIX]  Push通知専用コントローラ

use App\Services\BadgeService;
private BadgeService $badgeService;
public function __construct(DashboardService $service, BadgeService $badgeService)
$this->badgeService = $badgeService;
////////////////////////////////////////////////////////////
$userId = auth()->id();

// 🔥 ホームを見たらバッジ0
$this->badgeService->reset($userId);
////////////////////////////////////////////////////////////

mysql> DESCRIBE users;
+-------------+------------------+------+-----+---------+----------------+
| Field       | Type             | Null | Key | Default | Extra          |
+-------------+------------------+------+-----+---------+----------------+
| id          | int(10) unsigned | NO   | PRI | NULL    | auto_increment |
| full_name   | varchar(100)     | NO   |     | NULL    |                |
| user_login  | varchar(30)      | NO   |     | NULL    |                |
| password    | varchar(60)      | NO   |     | NULL    |                |
| badge_count | int(11)          | NO   |     | 0       |                |←⭐️追加
+-------------+------------------+------+-----+---------+----------------+
5 rows in set (0.00 sec)

mysql> 

Route::get('/push-test', function () {←の内容を共通エンジンとしてServiceに引っ越す

------------------------------------------------------------------------------------------
◯ STEP1

app/Services/NotificationService.php

namespace App\Services;

use App\Models\PushSubscription;
use Minishlink\WebPush\WebPush;
use Minishlink\WebPush\Subscription;

class NotificationService
{
    public function send($title, $body)
    {
        $auth = [
            'VAPID' => [
                'subject' => 'mailto:test@example.com',
                'publicKey' => env('VAPID_PUBLIC_KEY'),
                'privateKey' => env('VAPID_PRIVATE_KEY'),
            ],
        ];

        $webPush = new WebPush($auth);

        $subs = PushSubscription::all();

        foreach ($subs as $sub) {

            $subscription = Subscription::create([
                'endpoint' => $sub->endpoint,
                'publicKey' => $sub->public_key,
                'authToken' => $sub->auth_token,
            ]);

            $webPush->queueNotification(
                $subscription,
                json_encode([
                    'title' => $title,
                    'body'  => $body,
                    'badge' => 1,
                ])
            );
        }

        foreach ($webPush->flush() as $report) {

            \Log::info('Push status', [
                'success' => $report->isSuccess(),
                'reason'  => $report->getReason(),
            ]);
        }
    }
}
------------------------------------------------------------------------------------------
◯ STEP2 web.phpを軽くする

use App\Services\NotificationService;

Route::get('/push-test', function (NotificationService $notification) {

    $notification->send(
        '配車変更',
        '山田さんの予定が更新されました'
    );

    return '送信完了';
});

------------------------------------------------------------------------------------------
🟢 [2026.6.9] [FIX]  Push通知機能の追加(バッチ)
メモ
https://test-sistem.jp/push-testページにアクセスで通知実行
------------------------------------------------------------------------------------------
    ◯ OK / login.blade.phpに追記

    @include('layouts.pwa')
    </head>

    ◯ OK / resources/layouts/pwa.blade.php

    <script>
    window.VAPID_PUBLIC_KEY = "{{ env('VAPID_PUBLIC_KEY') }}";
    </script>

    <script src="{{ asset('js/pwa.js') }}?v={{ time() }}"></script>


------------------------------------------------------------------------------------------
    ◯ OK / envに追記

    //Push通知の「本人確認用キー」

    VAPID_PUBLIC_KEY=BFLAV6NTipOUd7_cviHPYPDWYz2YLRYQ_0JzNNQXaynr3qKTRmnf0BVwb2qHG3VgTX08A7jcGAeozVMehkDAZEI
    VAPID_PRIVATE_KEY=mGMLz0DBf60uZ7OP_vJKan05lvYERRGEKWWMhXfdCDg
------------------------------------------------------------------------------------------
    ◯ OK 
    mysql> SHOW TABLES;
    +--------------------------+
    | Tables_in_laravel_local  |
    +--------------------------+
    | customers                |
    | destinations             |
    | migrations               |
    | push_subscriptions       |←⭐️追加
    | sessions                 |
    | smile_cancel             |
    | smile_check              |
    | smile_posts              |
    | smile_yoyaku             |
    | user_destination_records |
    | users                    |
    +--------------------------+
------------------------------------------------------------------------------------------
    ◯ OK
    ⭐️構造
    mysql> DESCRIBE push_subscriptions;
    +------------+-----------------+------+-----+---------+----------------+
    | Field      | Type            | Null | Key | Default | Extra          |
    +------------+-----------------+------+-----+---------+----------------+
    | id         | bigint unsigned | NO   | PRI | NULL    | auto_increment |
    | user_id    | bigint unsigned | YES  |     | NULL    |                |
    | endpoint   | text            | NO   |     | NULL    |                |
    | public_key | text            | NO   |     | NULL    |                |
    | auth_token | text            | NO   |     | NULL    |                |
    | created_at | timestamp       | YES  |     | NULL    |                |
    | updated_at | timestamp       | YES  |     | NULL    |                |
    +------------+-----------------+------+-----+---------+----------------+
------------------------------------------------------------------------------------------
    ◯ OK
    Service Worker（sw.js）
    追加・修正
    Service Worker登録
------------------------------------------------------------------------------------------
    ◯ OK
    ○ sw.js / Service Workerをすぐ有効化するための初期設定
    ○ サーバー / logute.com/sw.js
    ○ ローカル / public/sw.js
    ○ navigator.serviceWorker.register('/sw.js')←とpwa.jsで読み込んでいるので<script src="sw.js"></script>のように書かない
------------------------------------------------------------------------------------------
    self.addEventListener('install', () => self.skipWaiting());
    self.addEventListener('activate', () => self.clients.claim());
------------------------------------------------------------------------------------------
    ◯ OK
    Push受信処理追加
    self.addEventListener('push', event => {

        console.log('🔥 PUSH RECEIVED');

        const promise = self.registration.showNotification('🔥 テスト通知', {
            body: '表示テスト',
            requireInteraction: true,
            silent: false
        })
        .then(() => {
            console.log('✅ notification shown');
        })
        .catch(err => {
            console.error('❌ showNotification error:', err);
        });

        event.waitUntil(promise);
    });
------------------------------------------------------------------------------------------
    ◯ OK / フロント（pwa.js）
------------------------------------------------------------------------------------------
    ○ pwa.js / ブラウザに Service Worker(sw.js) を登録するコード
    ○ サーバー / logute.com/js/pwa.js
    ○ ローカル / public/js/pwa.js
------------------------------------------------------------------------------------------
    ◯ OK / フロント（pwa.js）
    Service Worker登録
    navigator.serviceWorker.register('/sw.js')
------------------------------------------------------------------------------------------
    通知許可取得
    Notification.requestPermission()
------------------------------------------------------------------------------------------
    Push購読（重要）
    const subscription = await registration.pushManager.subscribe({
    userVisibleOnly: true,
    applicationServerKey: urlBase64ToUint8Array(window.VAPID_PUBLIC_KEY)
    });
------------------------------------------------------------------------------------------
    Base64変換関数追加
    function urlBase64ToUint8Array(base64String) {
    const padding = '='.repeat((4 - base64String.length % 4) % 4);
    const base64 = (base64String + padding)
        .replace(/-/g, '+')
        .replace(/_/g, '/');

    const rawData = window.atob(base64);

    return Uint8Array.from([...rawData].map(c => c.charCodeAt(0)));
    }
------------------------------------------------------------------------------------------
    ◯ OK　丸コピした
    Laravel（web.php）
    subscription保存ルート追加

    //--------------------------------------------------------------
    use App\Models\PushSubscription;
    use Illuminate\Http\Request;
    use Minishlink\WebPush\WebPush;
    use Minishlink\WebPush\Subscription;
    //--------------------------------------------------------------
    Route::post('/save-subscription', function (Request $request) {

        \Log::info($request->all());

        $sub = PushSubscription::updateOrCreate(
            [
                'endpoint' => $request->endpoint,
            ],
            [
                'public_key' => $request->keys['p256dh'] ?? null,
                'auth_token' => $request->keys['auth'] ?? null,
            ]
        );

        return response()->json([
            'ok' => true,
            'id' => $sub->id ?? null,
        ]);
    })->withoutMiddleware([
        \Illuminate\Foundation\Http\Middleware\VerifyCsrfToken::class,
    ]);

    Route::get('/push-test', function () {

        $auth = [
            'VAPID' => [
                'subject' => 'mailto:test@example.com',
                'publicKey' => env('VAPID_PUBLIC_KEY'),
                'privateKey' => env('VAPID_PRIVATE_KEY'),
            ],
        ];

        $webPush = new WebPush($auth);

        $subs = PushSubscription::all();

        foreach ($subs as $sub) {

            $subscription = Subscription::create([
                'endpoint' => $sub->endpoint,
                'publicKey' => $sub->public_key,
                'authToken' => $sub->auth_token,
            ]);

            $webPush->queueNotification(
                $subscription,
                json_encode([
                    'title' => 'テスト通知',
                    'body' => 'Laravelから飛ばしてるよ'
                ])
            );
        }

        $reportList = $webPush->flush();

        foreach ($reportList as $report) {
            \Log::info('Push status', [
                'success' => $report->isSuccess(),
                'reason' => $report->getReason()
            ]);
        }

        return '送信完了';
    });
------------------------------------------------------------------------------------------
    ◯ OK
    CSRF設定（必須） / app/Http/Middleware/VerifyCsrfToken.php

    CSRF除外追加
    protected $except = [
        '/save-subscription',
    ];
------------------------------------------------------------------------------------------
    ④ fetch（フロント → Laravel送信）
    初期
    fetch('/save-subscription', {
    method: 'POST',
    headers: {
        'Content-Type': 'application/json'
    },
    body: JSON.stringify(window.subscription)
    });
    デバッグ用変更
    .then(res => res.text())
    .then(data => console.log(data));
------------------------------------------------------------------------------------------
    ◯ OK
    Middleware/VerifyCsrfToken.php

    // このURLはCSRFチェックしないでOK追加
    protected $except = [
        '/save-subscription',
    ];
------------------------------------------------------------------------------------------
    Models/PushSubscription.php

    <?php

    namespace App\Models;

    use Illuminate\Database\Eloquent\Model;

    class PushSubscription extends Model
    {
        //
    }
------------------------------------------------------------------------------------------
◯ 環境別・進行状況
logute.com / NO
test-sistem.jp / OK
GitHub / OK
------------------------------------------------------------------------------------------
✅️[2026.6.5] [FIX]  利用者登録・区分select・margin上下余白
------------------------------------------------------------------------------------------
◯ 環境別・進行状況
logute.com / OK
test-sistem.jp / OK
GitHub / OK
------------------------------------------------------------------------------------------
✅️[2026.6.5] [FIX]  頭文字ら行、やゆよわを、修正
------------------------------------------------------------------------------------------
○ user-group-list.blade.php
------------------------------------------------------------------------------------------
$groupClassMap = [
    'あ'=>'a','い'=>'a','う'=>'a','え'=>'a','お'=>'a',
    'か'=>'ka','き'=>'ka','く'=>'ka','け'=>'ka','こ'=>'ka',
    'さ'=>'sa','し'=>'sa','す'=>'sa','せ'=>'sa','そ'=>'sa',
    'た'=>'ta','ち'=>'ta','つ'=>'ta','て'=>'ta','と'=>'ta',
    'な'=>'na','に'=>'na','ぬ'=>'na','ね'=>'na','の'=>'na',
    'は'=>'ha','ひ'=>'ha',
    'ふ'=>'ha','へ'=>'ha','ほ'=>'ha',
    'ま'=>'ma','み'=>'ma','む'=>'ma','め'=>'ma','も'=>'ma',
    'や'=>'ya','ゆ'=>'ya','よ'=>'ya',
    'ら'=>'ra','り'=>'ra','る'=>'ra','れ'=>'ra','ろ'=>'ra',
    'わ'=>'wa','を'=>'wo','ん'=>'other', ⭐️修正
];

// 五十音の固定順（ここが重要）
$allKeys = [
    'あ','い','う','え','お',
    'か','き','く','け','こ',
    'さ','し','す','せ','そ',
    'た','ち','つ','て','と',
    'な','に','ぬ','ね','の',
    'は','ひ','ふ','へ','ほ',
    'ま','み','む','め','も',
    'ら','り','る','れ','ろ',　⭐️修正
    'や','ゆ','よ','わ','を',　⭐️修正
];

------------------------------------------------------------------------------------------
○ style.css / 806行目
------------------------------------------------------------------------------------------
/* や-よ わ を*/
.open_window ul.wo li.cap,　⭐️修正
.open_window ul.wa li.cap,　⭐️修正
.open_window ul.ya li.cap { background:#c9e9fc; }

------------------------------------------------------------------------------------------
◯ 環境別・進行状況
logute.com / OK
test-sistem.jp / OK
GitHub / OK
------------------------------------------------------------------------------------------
✅️[2026.6.4] [FIX]  利用者登録に区分を追加
------------------------------------------------------------------------------------------

mysql> DESCRIBE customers;
+----------------+-----------------+------+-----+---------+----------------+
| Field          | Type            | Null | Key | Default | Extra          |
+----------------+-----------------+------+-----+---------+----------------+
| id             | bigint unsigned | NO   | PRI | NULL    | auto_increment |
| name           | varchar(255)    | NO   |     | NULL    |                |
| kana           | varchar(255)    | NO   |     | NULL    |                |
| support_notes  | text            | YES  |     | NULL    |                |
| classification | varchar(100)    | YES  |     | NULL    |                |　←　⭐️ 追加　
| created_at     | timestamp       | YES  |     | NULL    |                |
| updated_at     | timestamp       | YES  |     | NULL    |                |
+----------------+-----------------+------+-----+---------+----------------+

------------------------------------------------------------------------------------------
利用者登録 / user_registration
------------------------------------------------------------------------------------------
◯ user_registration.blade.php

⭐️ 追加
区分
<select name="classification" class="select">

    <option value="">選択してください</option>
    <option value="介護保険">介護保険</option>
    <option value="障害福祉">障害福祉</option>
    <option value="保険外">保険外</option>

</select>

<div class="error_msg" id="error_classification">
    @error('classification')
        {{ $message }}
    @enderror
</div>

------------------------------------------------------------------------------------------
◯ UserRegistrationController.php
------------------------------------------------------------------------------------------
    public function store(Request $request)
    {

        $request->validate([
            'user1' => ['required', 'regex:/^[ぁ-んァ-ン一-龥々ー　]+$/u'],
            'user2' => ['required', 'regex:/^[ぁ-んァ-ン一-龥々ー　]+$/u'],
            'user_hurigana1' => ['required', 'regex:/^[ァ-ヶー　]+$/u'],
            'user_hurigana2' => ['required', 'regex:/^[ァ-ヶー　]+$/u'],

            ⭐️ 追加
            'classification' => [
                'required',
                'in:介護保険,障害福祉,保険外'
            ],
        ], [
            'user1.required' => '日本語で正しく入力してください',
            'user2.required' => '日本語で正しく入力してください',
            'user1.regex' => '日本語で正しく入力してください',
            'user2.regex' => '日本語で正しく入力してください',

            'user_hurigana1.required' => '全角カタカナで正しく入力してください',
            'user_hurigana2.required' => '全角カタカナで正しく入力してください',
            'user_hurigana1.regex' => '全角カタカナで正しく入力してください',
            'user_hurigana2.regex' => '全角カタカナで正しく入力してください',

            ⭐️ 追加
            'classification.required' => '区分を選択してください',
            'classification.in' => '区分を正しく選択してください',
        ]);

        $fullName = $request->user1 . ' ' . $request->user2;
        $fullNameKana = $request->user_hurigana1 . ' ' . $request->user_hurigana2;

        //DB::table('user_names')->insert([
        DB::table('customers')->insert([
            'name' => $fullName,
            'kana' => $fullNameKana,
            'support_notes' => $request->support_textarea,
            'classification' => $request->classification,⭐️ 追加
            'created_at' => now(),
            'updated_at' => now(),
        ]);

        /*return redirect()->route('user_registration.page')
            ->with('success', '登録完了しました');*/
            return redirect()->route('dashboard', ['success' => 'insert']);
    }
}

------------------------------------------------------------------------------------------
◯ customer_th.blade.php
------------------------------------------------------------------------------------------
⭐️ 追加
<tr>
    <th>ID</th>
    <th>利用者</th>
    <th>フリガナ</th>
    <th>区分</th>　⭐️追加
    <th>支援上の留意点</th>
    <th></th>
</tr>
------------------------------------------------------------------------------------------
◯ customer_rows.blade.php
------------------------------------------------------------------------------------------
    <td>
        <select name="classification" form="update_{{ $val->id }}">

            <option value=""
                {{ empty($val->classification) ? 'selected' : '' }}>
                選択してください
            </option>

            <option value="介護保険"
                {{ $val->classification == '介護保険' ? 'selected' : '' }}>
                介護保険
            </option>

            <option value="障害福祉"
                {{ $val->classification == '障害福祉' ? 'selected' : '' }}>
                障害福祉
            </option>

            <option value="保険外"
                {{ $val->classification == '保険外' ? 'selected' : '' }}>
                保険外
            </option>

        </select>
    </td>
------------------------------------------------------------------------------------------
◯ master.css
------------------------------------------------------------------------------------------
select, ⭐️ 追加
input[type="password"],
input[type="text"]{
    font-size:13px;
    width:100%;
    line-height:1em;
    padding:3px 2px 2px;
    background:#e5eefe;
    border:1px solid #999;
}
------------------------------------------------------------------------------------------
◯ MasterController.php
------------------------------------------------------------------------------------------

    //------------------------------------------------------------------------------
    // 利用者更新
    //------------------------------------------------------------------------------
    public function customerUpdate(Request $request, $id)
    {

        //dd($id);

        Customer::where('id', $id)->update([

            'name' => $request->name,
            'kana' => $request->kana,
            'support_notes' => $request->support_notes,
            'classification' => $request->classification,⭐️ 追加

        ]);

        //return redirect()->back();
        return redirect()->route('master.page', ['success' => 'master_update']);
    }

------------------------------------------------------------------------------------------
◯ UserService.php
------------------------------------------------------------------------------------------

class UserService
{
    /**
     * 利用者一覧（頭文字グルーピング）
     */
    public function getGroupedUsers()
    {
    $users = DB::table('customers')
        ->select('name', 'kana', 'support_notes','classification') // ⭐️classificationを追加
        ->distinct()
        ->whereNotNull('kana')
        ->where('kana', '!=', '')
        ->get()
        ->unique('name') // ←これ追加
        ->map(function ($item) {
            $item->kana = mb_convert_kana(trim($item->kana), 'c');
            return $item;
        });
------------------------------------------------------------------------------------------

  // 値がセットされていたら実行
  if (classification) {
    $(this)
        .closest('tr')
        .find('.classification')
        .val(classification || '');
  }

------------------------------------------------------------------------------------------
◯ 環境別・進行状況
logute.com / OK
test-sistem.jp / OK
GitHub / OK
------------------------------------------------------------------------------------------
✅️[2026.6.3] [FIX]  −・+ボタン高さ
------------------------------------------------------------------------------------------
◯ 環境別・進行状況
logute.com / OKOK
test-sistem.jp / OKOK
GitHub / OKOK
------------------------------------------------------------------------------------------
✅️[2026.6.3] [FIX]  点検項目ページの戻るボタンにも、日付、車種、開始走行距離のパラメ追加
------------------------------------------------------------------------------------------

◯ buttonからa hrefに変更
<?php /*<div class="flex-item"><input type="button" value="戻る" onclick="location.href='{{ url('/dashboard') }}';"></div>*/ ?>

------------------------------------------------------------------------------------------

<div class="flex-item"><a href="{{ route('dashboard', [
    'dates' => session('dates'),
    'car' => session('car'),
    'start_distance' => session('start_distance')
]) }}">戻る</a></div>

------------------------------------------------------------------------------------------

.inspection_check .inner form .button .flex-item a,　←追加
.inspection_check .inner form .button .flex-item input[type=button],
.inspection_check .inner form .button .flex-item input[type=submit]{

------------------------------------------------------------------------------------------
◯ 環境別・進行状況
logute.com / OKOK
test-sistem.jp / OKOK
GitHub / OKOK
------------------------------------------------------------------------------------------
✅️ [2026.6.3] [FIX] 利用者selectを選択したら突然selectが消えることがある修正
------------------------------------------------------------------------------------------

    if(fileName == "preview" || fileName == "post"){
      // アンカーリンク
      function linkscroll(target) {

        // コメントアウトした
        //$('html, body').animate({scrollTop: $(target).offset().top -86 }, 0, 'swing');//86px上にずらす

        // こっちを追加
        if (!$(target).length) {
            return;
        }

        $('html, body').animate({
            scrollTop: $(target).offset().top - 86
        }, 0, 'swing');

      }
    }

------------------------------------------------------------------------------------------

    // コメントアウトした
    //linkscroll('#' + tr_id);

    // こっちを追加した
    if (tr_id) {
        linkscroll('#' + tr_id);
    }

------------------------------------------------------------------------------------------
◯ 環境別・進行状況
logute.com / OK
test-sistem.jp / OK
GitHub / OK
------------------------------------------------------------------------------------------
✅️ [2026.5.29] [FIX]  乗降予約・キャンセル受付・備考文字数
------------------------------------------------------------------------------------------

varchar(100)→1000、さらに文字入るtext型に変換
現在使用しているロリポのsmile_yoyaku,remarks_txtの型をvarchar→textへ変換

◯　メモ
MySQL の TEXT は最大 65,535 bytes。

utf8mb4（今の日本語環境で多い）だと、1文字最大4bytes使う可能性あるから、実用上はだいたい：

日本語中心 → 約1.5万〜2万文字前後
英数字中心 → 約6万文字弱

くらいの感覚。
------------------------------------------------------------------------------------------
◯ 環境別・進行状況
logute.com / ujcva_logute / OK
test-sistem.jp / kovsd_logute / OK
自宅ローカルDB / OK
スマイルハートローカルDB / OK
2.0 ロリポ / OK
------------------------------------------------------------------------------------------
✅️ [2026.5.31] [FIX]  pop_select / React / tsx
------------------------------------------------------------------------------------------
○　部品をこの形で統一して増やしてく

resources / react / pop_select / index.tsx
resources / react / pop_select / PopSelect.tsx
resources / views / react / pop_select.blade.php

------------------------------------------------------------------------------------------
○ ローカルにだけ、tsconfig.json設置（typeScript設定ファイル）
------------------------------------------------------------------------------------------
○ ローカル修正後・ビルド実行
aosuke:example-app acode$ npm run build
------------------------------------------------------------------------------------------
○ ローカル
build/assets
build/manifest.json
------------------------------------------------------------------------------------------
○　サーバー
logute.com/build/assetsへアップ
logute.com/example-app/public/build/manifest.jsonへアップ
------------------------------------------------------------------------------------------
📝 メモ
Reactはローカル編集後、npm run build、して assets,manifest.jsonをアップする流れ
------------------------------------------------------------------------------------------
◯ 環境別・進行状況
logute.com / OKOK
test-sistem.jp / OKOK
GitHub / OKOK
------------------------------------------------------------------------------------------
✅️ [2026.5.28] [FIX]  始業距離・車両を選択後、登録画面で登録せず戻った時、始業距離維持 spでも確認
------------------------------------------------------------------------------------------
戻るボタンリンクに始業距離パラメータをつけてダッシュボードで受け取る
------------------------------------------------------------------------------------------
// dashboard.blade.php

運行日、乗降者、始業距離、URLにパラメータがついていたらURLのパラメータを優先に修正
------------------------------------------------------------------------------------------
// system.js・パラメータがついている時はURLパラメータから取得・２箇所

// 作業メモ1

const paramStartDistance = params.get('start_distance');

// #carの変更時だけ発火
//$('#car').on('change', updateStartDistance);
$('#car, #ymd').on('change', updateStartDistance);

// URLにパラメータがついていればパラメータ優先
if (paramStartDistance !== null) {

    $('#start_distance').val(paramStartDistance);

} else {

    // DBから取得
    updateStartDistance();

}
------------------------------------------------------------------------------------------
// 作業メモ2

const paramStartDistance = params.get('start_distance');

//alert(paramStartDistance);

// urlにパラメータがついていればパラメータ優先
if (paramStartDistance !== null) {

    $('#start_distance').val(paramStartDistance);

} else {

    // changeイベント登録
    $('#ymd, #car').on('change', updateStartDistance);

    // 初回実行
    updateStartDistance();
}
------------------------------------------------------------------------------------------
// post.blade.php　・パラメータつきリンク

○　PC
<a href="{{ route('dashboard', [
    'dates' => session('dates'),
    'car' => session('car'),
    'start_distance' => session('start_distance')
]) }}">戻る</a>
------------------------------------------------------------------------------------------
○　SP　←画像矢印ボタンのリンク修正
<?php /*<a href="{{ route('dashboard') }}"><img src="{{ asset('image/prev.png') }}" alt="" class="prev_btn"></a>*/ ?>

<a href="{{ route('dashboard', [
    'dates' => session('dates'),
    'car' => session('car'),
    'start_distance' => session('start_distance')
]) }}"><img src="{{ asset('image/prev.png') }}" alt="" class="prev_btn"></a>
------------------------------------------------------------------------------------------
◯ 環境別・進行状況
logute.com / OKOK
test-sistem.jp / OKOK
GitHub / OKOK
------------------------------------------------------------------------------------------
✅️ [2026.5.29] [FIX]  登録画面・一瞬追加した値をその場で消せるように
------------------------------------------------------------------------------------------
○ style.css追加

.post-page form table.tb caption span:last-child{
    position:absolute;
    right:20px;
}
.post-page form table.tb caption span.delete_run{
    position:absolute;
    left:20px;
    display:inline;
}
------------------------------------------------------------------------------------------

○　CaptionRunControl.js追加

------------------------------------------------------------------------------------------
○ preview.blade.php修正

<caption class="input_area_c input_area_c{{ $loop->index }}">
    <?php /*運行{{ $loop->iteration }}<span>ー</span>*/ ?>

            運行{{ $loop->iteration }}             

        <span class="toggle_run">ー</span>

</caption>
------------------------------------------------------------------------------------------
○ post.blade.php修正

<caption class="input_area_c input_area_c{{ $i }}">

    <span class="delete_run">✕</span>
0
    <span class="run_title">
        運行{{ $startIndex + $i }}
    </span>

    <span class="toggle_run">ー</span>

</caption>
------------------------------------------------------------------------------------------
○ system.js　から　CaptionRunControl.jsへ移行

$('span.toggle_run').on('click',function(){

    $(this).toggleClass("active");

    if($(this).hasClass('active')){

    $(this).text('＋');

    }else{

    $(this).text('ー');

    }

    $(this).closest('caption').next('tbody').toggle();

});
------------------------------------------------------------------------------------------
◯ 環境別・進行状況
logute.com / OK
test-sistem.jp / OK
GitHub / OK
------------------------------------------------------------------------------------------
✅️ [2026.5.28] [FIX]  乗降予約・反映させた人の名前標示
------------------------------------------------------------------------------------------
登録押した時に一瞬表示される「あーん」表を修正
------------------------------------------------------------------------------------------

// style.css 760行目

.open_window{
    width: 80%;
    margin: 0 auto 0;
    display:none;　←最初から非表示へ修正
    /*display: flex;*/　←コメントアウト
    flex-direction: column;
    flex-wrap: wrap;
    height: calc(5 * 125px + 4 * 10px);
    gap: 10px;
    direction: rtl;
    margin:70px auto 0;
}
------------------------------------------------------------------------------------------

//system.js 604行目

//$('.open_window').css('opacity',1).fadeIn(500);

$('.open_window').css({
    opacity: 1,
    display: 'flex' ←追加
}).hide().fadeIn(500);

------------------------------------------------------------------------------------------
◯ 環境別・進行状況
logute.com / OK
test-sistem.jp / OK
GitHub / OK
------------------------------------------------------------------------------------------
✅️　[2026.5.29] [FIX]  乗降予約・反映させた人の名前標示
------------------------------------------------------------------------------------------
smile_yoyaku テーブルに reflected_by カラム追加
------------------------------------------------------------------------------------------
◯ SmileYoyaku.php

app/Models/SmileYoyaku.php
protected $fillable = [
    'user',
    'destination',
    'reservation_datetime',
    'client_name',
    'receptionist',
    'input_date',
    'attention',
    'remarks_txt',
    'place',
    'is_reflected',
    'reflected_at',
    'reflected_by',　←追加（反映者）
];
------------------------------------------------------------------------------------------
◯ BoardingReservationController.php　139行目

    // =========================
    // 反映
    // =========================
    public function reflect($id)
    {
        $yoyaku = SmileYoyaku::findOrFail($id);

        $yoyaku->update([
            'is_reflected' => 1,
            'reflected_at' => now(),
            //'receptionist' => auth()->user()->full_name ?? null,
            'reflected_by' => auth()->user()->full_name ?? null,　←こっちに修正
        ]);

        return back()->with('success', '反映しました');
    }
------------------------------------------------------------------------------------------
◯　reservation_search.blade.php

{{-- 反映日 --}}
@if($mode !== 'support')
<td>
    {{ $row->reflected_at
        ? \Carbon\Carbon::parse($row->reflected_at)->format('Y/n/j G:i') . '【反映者 : ' . $row->reflected_by . '】'
        : '—' }}
</td>
@endif
------------------------------------------------------------------------------------------
◯ 環境別・進行状況
logute.com / OKOK
test-sistem.jp / OKOK
GitHub / OKOK

------------------------------------------------------------------------------------------
✅️ [2026.5.29] [FIX]  削除画面・確認アラート追加
------------------------------------------------------------------------------------------
before
<form action="{{ route('smile_posts.deleteMultiple') }}" method="POST" id="form">
after
<form action="{{ route('smile_posts.deleteMultiple') }}" method="POST" id="form" onsubmit="return confirm('削除しますか？')">
------------------------------------------------------------------------------------------
DeleteCheck.jsのアラートはもう使用してる箇所ないコメントアウト

/*$('.delete_btn').on('click', function(){
    var result = window.confirm('本当に削除してよろしいですか？');
    if(result == true){
    return true;
    }else{
    return false; //exit();
    }
});*/
------------------------------------------------------------------------------------------

reservation_search.blade.phpのページで「本当に削除してよろしいですか？」もう使ってないコメントアウト

/*
<script src="{{ asset('js/plugin/DeleteCheck/DeleteCheck.js') }}?v={{ time() }}" charset="utf-8"></script>
*/

------------------------------------------------------------------------------------------
◯ メモ
DeleteCheck.jsは、delete.blade.phpのチェックボックスにチェック後に削除ボタンが灰色からオレンジ色押せる動作だけdelete.blade.phpで使用

------------------------------------------------------------------------------------------
◯ 環境別・進行状況
logute.com / OKOK
test-sistem.jp / OKOK
GitHub / OKOK
------------------------------------------------------------------------------------------
✅️　[2026.0.00] [FIX]  削除ページ・距離と料金が表示されていない修正
------------------------------------------------------------------------------------------

test-sistem.jpとGitHubローカルだけ表示されていない

style.css 691行目コメントアウト

/*.preview-page .distance,
.preview-page .price {
  visibility: hidden;
}*/
------------------------------------------------------------------------------------------
◯ 環境別・進行状況
logute.com / OKOK
test-sistem.jp / OKOK
GitHub / OKOK
------------------------------------------------------------------------------------------
✅️　[2026.5.24] [FIX] 検索archiveページの発時刻も含めて昇順にする
------------------------------------------------------------------------------------------
◯ ArchiveController.phpの39行目修正
//before
//return $query->orderBy('dates')->get();
------------------------------------------------------------------------------------------
//after
return $query
    ->orderBy('dates', 'asc')
    ->orderBy('departureTime', 'asc')
    ->get();
------------------------------------------------------------------------------------------
◯ 環境別・進行状況
logute.com / OKOK
test-sistem.jp / OKOK
GitHub / OKOK
------------------------------------------------------------------------------------------