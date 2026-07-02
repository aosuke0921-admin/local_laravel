<?php
// http://127.0.0.1:8000/import-customers　←のURLにアクセスCSVをDBへインポート

namespace App\Http\Controllers;

use Illuminate\Support\Facades\DB;

class CustomerImportController extends Controller
{
    public function import()
    {
        //$filePath = public_path('csv/user_name.csv');
        //$filePath = public_path('csv/destination_name.csv');
        //$filePath = public_path('csv/user.csv');

        if (!file_exists($filePath)) {
            return 'CSVが見つかりません';
        }

        if (($file = fopen($filePath, 'r')) === false) {
            return 'CSVを開けません';
        }

        // ヘッダー読み飛ばし
        fgetcsv($file);

        while (($row = fgetcsv($file)) !== false) {

            // 空行対策
            if (empty($row[0])) {
                continue;
            }

            /*DB::table('customers')->insert([
                'name'          => $row[0] ?? null,
                'kana'          => $row[1] ?? null,
                'support_notes' => $row[2] ?? null,
                'created_at'    => now(),
                'updated_at'    => now(),
            ]);*/

            /*DB::table('destinations')->insert([
                'destination'           => $row[0] ?? null,
                'destination_hurigana'  => $row[1] ?? null,
                'created_at'            => now(),
                'updated_at'            => now(),
            ]);*/

            /*DB::table('user_destination_records')->insert([
                'user'            => $row[0] ?? null,
                'destination'     => $row[1] ?? null,
                'pickup_location' => $row[2] ?? null,

                // 透析（ありなら1 / 空なら0）
                'dialysis'        => !empty($row[3]) ? 1 : 0,

                // 移動支援費フラグ（ありなら1 / 空なら0）
                'transport_fee'   => (!empty($row[4]) && $row[4] === 'あり') ? 1 : 0,

                // 距離
                //'distance'        => $row[5] ?? 0,
                'distance' => !empty($row[5]) ? (float)$row[5] : 0,

                'created_at'      => now(),
                'updated_at'      => now(),
            ]);*/


        }

        fclose($file);

        return 'インポート完了';
    }
}