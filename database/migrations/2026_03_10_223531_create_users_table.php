<?php

//テーブルを作ったり削除したりするクラスを使うための準備
use Illuminate\Database\Migrations\Migration;

//テーブルのカラム定義に使う
use Illuminate\Database\Schema\Blueprint;

//実際にテーブルを作成・削除するために使う
use Illuminate\Support\Facades\Schema;

//無名クラスで Migration を作る宣言
//Laravel はこれを実行して up() や down() を呼ぶ
return new class extends Migration
{

    //マイグレーションを 適用するとき に実行されるメソッド

/*  MySQLデータベースの構造 = マイグレーション
+-------------------+-----------------+------+-----+---------+----------------+
| Field             | Type            | Null | Key | Default | Extra          |
+-------------------+-----------------+------+-----+---------+----------------+
| id                | bigint unsigned | NO   | PRI | NULL    | auto_increment |
| name              | varchar(255)    | NO   |     | NULL    |                |
| email             | varchar(255)    | NO   | UNI | NULL    |                |
| email_verified_at | timestamp       | YES  |     | NULL    |                |
| password          | varchar(255)    | NO   |     | NULL    |                |
| remember_token    | varchar(100)    | YES  |     | NULL    |                |
| created_at        | timestamp       | YES  |     | NULL    |                |
| updated_at        | timestamp       | YES  |     | NULL    |                |
+-------------------+-----------------+------+-----+---------+----------------+
*/

    //新しいテーブルを作る処理をここに書く
    public function up(): void
    {
        if (Schema::hasTable('users')) {
            return;
        }
        Schema::create('users', function (Blueprint $table) {
            $table->id();
            $table->string('full_name', 100);
            $table->string('user_login', 30);
            $table->string('user_pass', 255);
            $table->timestamps();
        });
        /*
        //データベースに users という名前のテーブル を作る、という命令
        Schema::create('users', function (Blueprint $table) {

            $table->id(); //自動連番(auto-increment) + 主キー（PRIMARY KEY）
            $table->string('name'); //カラムの型string、文字数は未指定なので最大文字数は255文字
            $table->string('password'); //カラムの型string、文字数は未指定なので最大文字数は255文字
            $table->timestamps(); // (例)2026-03-11 10:20:30

        });
        */
    }

    /* 処理を変更前に巻き戻す処理 */
    /*public function down(): void
    {
        Schema::dropIfExists('users');
    }
    */
};
