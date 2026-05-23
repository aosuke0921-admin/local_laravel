<?php
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Auth\LoginController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\PostController;
use App\Http\Controllers\InspectionController;
use App\Models\UserDestinationRecord;
use App\Services\UserService;
use App\Http\Controllers\PreviewController;
use App\Http\Controllers\UpdateController;
use App\Http\Controllers\DeleteController;
use App\Http\Controllers\ArchiveController;
use App\Http\Controllers\MonthArchiveController;
use App\Http\Controllers\PrintController;
use Illuminate\Support\Facades\Auth;
use App\Http\Controllers\UserRegistrationController;
use App\Http\Controllers\DestinationRegistrationController;
use App\Http\Controllers\UserDestinationRegistrationController;
use App\Http\Controllers\BoardingReservationController;
use App\Http\Controllers\CustomerImportController;
use App\Http\Controllers\ReservationController;
use App\Http\Controllers\PopSelectController;
use App\Http\Controllers\MasterController;
/*---------------------------------------------------------------------------------
 React
---------------------------------------------------------------------------------*/
Route::get('/hello', function () {
    return view('hello_world');
});

/*---------------------------------------------------------------------------------
 GET（表示）＋POST（登録）セットが基本
---------------------------------------------------------------------------------*/

//ログインは弾く、ログイン画面へリダイレクト
Route::middleware(['auth'])->group(function () { //GET と POST で 同じURLでも名前は別々

    //DashboardController の getStartDistance メソッドを呼ぶ ルート定義 です
    //Ajax などで 「指定した日付と車の始業距離を取得」 する用途で使用
    Route::get('/get-start-distance', [DashboardController::class, 'getStartDistance']);

    // 開きっぱなしページ用セッション確認ルート
    // Ajax で定期的にチェックしてセッション切れを検知
    Route::get('/ping-session', function() {
        return response()->json([
            'auth' => Auth::check()
        ]);
    })->middleware('auth');

    // フォーム送信・DB更新用ルート
    // bulk-update ボタン押下時に処理される
    Route::post('/post/bulk-update', [PostController::class, 'bulkUpdate'])->name('post.bulk-update');

    // 削除確認ページ
    Route::get('/delete', [DeleteController::class, 'index'])->name('delete.page');

    // delete実行処理
    Route::post('/smile_posts/delete', [DeleteController::class, 'destroyMultiple'])->name('smile_posts.deleteMultiple');

    // update実行処理
    Route::post('/ride/bulk-update', [UpdateController::class, 'bulkUpdate'])->name('ride.bulkUpdate');

    // 登録処理を受け取るPOSTルート
    Route::post('/posts', [PostController::class, 'store'])->name('post.store');

    // /post 用ルート（RideControllerのstore利用）
    Route::get('/post', [PostController::class, 'index'])->middleware('auth')->name('post.page');
    Route::post('/post', [PostController::class, 'store'])->name('post.store');

    //------------------------------------------------------------------------------
    // 社員（usersテーブル）管理
    //------------------------------------------------------------------------------

    // 社員一覧表示
    Route::get('/master', [MasterController::class, 'index'])
        ->middleware('auth')
        ->name('master.page');

    // 社員新規追加
    Route::post('/master', [MasterController::class, 'store'])
        ->middleware('auth')
        ->name('master.store');

    // 社員更新
    Route::put('/member/{id}', [MasterController::class, 'update'])
        ->middleware('auth')
        ->name('member.update');
        
    // 社員削除
    Route::delete('/member/{id}', [MasterController::class, 'destroy'])
        ->middleware('auth')
        ->name('member.delete');

//------------------------------------------------------------------------------

    // 頭文字タブ切替用のAjax取得ルート
    Route::get('/master/get-users', [MasterController::class, 'getUsers'])
        ->middleware('auth')
        ->name('master.getUsers');


//------------------------------------------------------------------------------

    // ラジオボタン切替時の Ajax取得 route
    Route::get('/master/change-mode', [MasterController::class, 'changeMode'])->name('master.changeMode');

//------------------------------------------------------------------------------













Route::put(
    '/master/customer/update/{id}',
    [MasterController::class, 'customerUpdate']
)->name('master.customer.update');

Route::delete(
    '/master/customer/delete/{id}',
    [MasterController::class, 'customerDelete']
)->name('master.customer.delete');


//------------------------------------------------------------------------------

Route::put(
    '/master/destination/update/{id}',
    [MasterController::class, 'destinationUpdate']
)->name('master.destination.update');

Route::delete(
    '/master/destination/delete/{id}',
    [MasterController::class, 'destinationDelete']
)->name('master.destination.delete');

//------------------------------------------------------------------------------
// 利用者・行き先 更新
//------------------------------------------------------------------------------
Route::put('/user_destination/update/{id}', [MasterController::class, 'userDestinationUpdate'])
    ->name('user_destination.update');

//------------------------------------------------------------------------------
// 利用者・行き先 削除
//------------------------------------------------------------------------------
Route::delete('/user_destination/delete/{id}', [MasterController::class, 'userDestinationDelete'])
    ->name('user_destination.delete');














    // ダッシュボード
    Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');
    Route::post('/dashboard', [DashboardController::class, 'submitForm'])->name('dashboard.post');

    // 点検チェック
    Route::get('/inspection_check', [InspectionController::class, 'index2'])->name('inspection.check');
    Route::post('/inspection_check', [InspectionController::class, 'index2'])->name('inspection.check.post');

    //更新ページ
    Route::get('/preview', [PreviewController::class, 'index'])->name('preview.page'); //nameの重複なしOK
    Route::post('/preview', [PreviewController::class, 'preview'])->name('preview.page.post'); //nameの重複なしOK    


    // カレンダー（日付）変更時の処理
    // onchangeで送信された日付を受け取り、
    // セッション更新・画面再描画・データ再取得などを行う
    Route::post('/preview/change/date', [PreviewController::class, 'changeDate'])->name('preview.change.date');




    // 検索ページ
    Route::get('/archive', [ArchiveController::class, 'index'])->name('archive.page');

    // 月報CSV
    Route::get('/month-archive', [MonthArchiveController::class, 'index'])->name('month_archive.page');
    
    // CSVダウンロード用
    Route::get('/archive/download', [ArchiveController::class, 'downloadCsv'])->name('archive.downloadCsv');

    // CSVダウンロード用
    Route::get('/month-archive/download', [MonthArchiveController::class, 'downloadCsv'])->name('month.archive.downloadCsv');

    // 運行日報・印刷
    Route::get('/print', [PrintController::class, 'index'])->name('print.page');

    // 利用車登録
    Route::get('/user_registration', [UserRegistrationController::class, 'index'])->name('user_registration.page');

    Route::post('/user_registration', [UserRegistrationController::class, 'store'])->name('user_registration.post');

    // 行き先登録
    Route::get('/destination_registration', [DestinationRegistrationController::class, 'index'])->name('destination_registration.page');

    Route::post('/destination_registration', [DestinationRegistrationController::class, 'store'])->name('destination_registration.post');

    // 利用車・行き先登録 
    Route::get('/user_destination_registration',[UserDestinationRegistrationController::class, 'index'])->name('user_destination_registration.page');
    Route::post('/user_destination_registration',[UserDestinationRegistrationController::class, 'store'])->name('user_destination_registration.post');



    // 乗降予約・キャンセル予約・乗降・支援・POP選択画面
    Route::get('/pop_select', [PopSelectController::class, 'index'])->name('pop_select.page');



    // 乗降予約・キャンセル予約
    Route::get('/boarding_reservation', [BoardingReservationController::class, 'index'])->name('boarding_reservation.page');
    Route::post('/boarding_reservation', [BoardingReservationController::class, 'store'])->name('boarding_reservation.store');









    // 乗降一覧検索・キャンセル一覧検索
    Route::get('/reservation_search', [ReservationController::class, 'index'])->name('reservation_search.page');
    Route::post('/reservation_search', [ReservationController::class, 'search'])->name('reservation_search.search');
 
    // URLにアクセスしたらCSV取り込み処理を実行するルート、CSV一括インポート
    Route::get('/import-customers', [CustomerImportController::class, 'import']);

    // JS（フロント側）で利用するための「利用者＋行き先データ」取得API
    // CSVではなくDB（user_destination_records）から取得する
    Route::get('/api/user-destinations', function (UserService $service) {
        return $service->getAllRecords();
    });

    // 予約データを「反映済みに更新する」処理
    Route::get('/yoyaku/reflect/{id}', [BoardingReservationController::class, 'reflect'])->name('yoyaku.reflect');

    // 予約データを削除する
    Route::delete('/yoyaku/{id}', [BoardingReservationController::class, 'destroy'])->name('yoyaku.delete');

    Route::delete('/cancel/delete/{id}', [ReservationController::class, 'deleteCancel'])
    ->name('cancel.delete');

    // 乗降予約・キャンセル受付・編集ページ
    Route::get('/boarding/edit/{id}', [BoardingReservationController::class, 'edit'])->name('boarding_reservation.edit');

    Route::post('/boarding/update/{id}', [BoardingReservationController::class, 'update'])->name('boarding_reservation.update');

    Route::post('/cancel/update/{id}', [ReservationController::class, 'updateCancel'])->name('cancel.update');

    // CSVファイルをJSONに変換して、JavaScriptから使えるようにしているAPIルート
    /*Route::get('/csv-json', function () {

        $path = public_path('csv/user.csv');

        if (!file_exists($path)) {
            return response()->json(['error' => 'CSV not found'], 404);
        }

        $csv = file_get_contents($path);

        $lines = array_filter(explode("\n", $csv));

        $data = array_map(function ($line) {
            return str_getcsv($line);
        }, $lines);

        return response()->json($data);
    });*/

    // ログアウト・データ送信処理POST
    Route::post('/logout', [LoginController::class, 'logout'])->name('logout');

    // GETで直接アクセスされた場合 → 404にする
    Route::get('/logout', function() {
        abort(404); // または abort(419, 'CSRF token missing or invalid.');
    });

    // ルートにアクセスした時はログインページ表示
    Route::get('/', function () {
        return redirect()->route('login');
    });

    // テストページ　hogeにアクセス hogeページ表示
    Route::get('/hoge', function () {
        return view('hoge');
    })->middleware('auth'); // ログイン済みの時だけ見れる設定

});

// ログイン処理・データ送信処理POST
Route::post('/login', [LoginController::class, 'login']);

// ログインフォームのページを表示
Route::get('/login', [LoginController::class, 'showLoginForm'])->name('login');