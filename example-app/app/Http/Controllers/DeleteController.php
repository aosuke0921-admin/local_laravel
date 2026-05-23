<?php //95点

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Services\DeleteService;

class DeleteController extends Controller
{
    public function index(DeleteService $deleteService)
    {
        // セッション取得（Service側で変換済み）
        $session = $deleteService->getSessionData();

        $user  = $session['user'];
        $car   = $session['car'];
        $dates = $session['dates'];

        // DB取得（Serviceに移動）
        $posts = $deleteService->getPosts($user, $car, $dates);

        // データなし
        if ($posts->isEmpty()) {
            return redirect()->route('dashboard', [
                'error' => 'no_data'
            ]);
        }

        $headerPost = $posts->first();

        return view('delete', [

            'date' => \Carbon\Carbon::parse($dates)->format('Y年n月j日'),

            'posts'      => $posts,
            'headerPost' => $headerPost,
        ]);
    }

    public function destroyMultiple(Request $request, DeleteService $deleteService)
    {
        // チェックされたID配列
        $selectedIds = $request->input('delete_check', []);

        // Serviceで削除処理
        $deleteService->deleteByIds($selectedIds);

        return redirect()->route('dashboard', [
            'success' => 'delete'
        ]);
    }
}