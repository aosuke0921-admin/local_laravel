◯　ローカル自動リロード

public/hot は Git管理しないのが普通。
毎回 npm run dev 時に自動生成される一時ファイルみたいなもの。


環境によって変わる
localhost:5176
localhost:5174

--------------------------------------------

◯　GitHubへ更新したファイルだけアップ

◯　変更があった時だけ

git pull --rebase
git add .
git commit -m "docs/検索ページ・時刻・降順修正・完了"
git push

--------------------------------------------

◯　変更がなかった時これだけ

git pull --rebase / 相手の変更を自分に取り込む

git push / 自分の変更を相手（GitHub）に送る


--------------------------------------------

◯　別の環境で実行ファイル取得

cd ~/Desktop

git clone https://github.com/aosuke0921-admin/local_laravel.git

cd local_laravel

cd example-app

composer install

# 以前の .env を配置

php artisan serve


--------------------------------------------

◯　お名前・開発環境

6012652
Tigers&Tigers&1207

--------------------------------------------

◯　お名前・本番環境

64067490
Tigers&1207&

--------------------------------------------
🔐 SSH接続（ターミナルでサーバ接続）
--------------------------------------------

◯ 本番環境・お名前

ホスト：www1130.onamae.ne.jp
ポート：8022
ユーザー：r9589205
鍵：aoyama_key.pem（デスクトップ）

ssh -i ~/Desktop/aoyama_key.pem -p 8022 r9589205@www1130.onamae.ne.jp

--------------------------------------------

◯　開発環境・お名前

ホスト：www1152.onamae.ne.jp
ポート：8022
ユーザー：r6092895
鍵：sistem.pem（デスクトップ）

ssh -i ~/Desktop/sistem.pem -p 8022 r6092895@www1152.onamae.ne.jp

--------------------------------------------

◯　本番環境DB・お名前

mysql -h mysql1034.onamae.ne.jp -u ujcva_aoyama -p

USE ujcva_logute

--------------------------------------------

◯　開発環境DB・お名前

mysql -h mysql1038.onamae.ne.jp -u kovsd_aoyama -p

USE kovsd_logute

--------------------------------------------

◯　開発環境DB・ローカル

mysql -u root -p

password:なし

SHOW DATABASES;

USE laravel_local; //←ローカル開発環境で現在使用してるDB

--------------------------------------------

ls

cd public_html
cd logute.com
cd example-app

--------------------------------------------

◯ サーバー接続

php artisan serve

--------------------------------------------

◯ サーバーから切断

ctrl + c

--------------------------------------------

◯ Laravelキャッシュ・クリア

Ctrl + c

php artisan route:clear
php artisan config:clear
php artisan cache:clear
php artisan view:clear
php artisan serve

--------------------------------------------

public_html/logute.com の中身を隠しファイル込みで表示

ls -la public_html/logute.com

--------------------------------------------

◯　usersテーブルの構造を確認

DESCRIBE users;

DESCRIBE smile_check;

DESCRIBE smile_posts;

DESCRIBE customers;

DESCRIBE smile_posts;

DESCRIBE destinations;

DESCRIBE migrations;

DESCRIBE sessions;

DESCRIBE user_destination_records;

DESCRIBE smile_yoyaku;

DESCRIBE smile_cancel;

--------------------------------------------

SHOW TABLES;

--------------------------------------------

SELECT * FROM users;

SELECT * FROM smile_check;

SELECT * FROM smile_posts;

SELECT * FROM customers;

SELECT * FROM destinations;

SELECT * FROM user_destination_records;

SELECT * FROM smile_yoyaku;

SELECT * FROM smile_cancel;

SELECT * FROM migrations;

--------------------------------------------

◯　指定した文字をハッシュ文字に変換

Hash::make('123');

--------------------------------------------

◯　パスワード更新

UPDATE users
SET password = '$2y$12$stWhsKXm4jp0P4DlY0z47uAe8h9xzLcpkB6.VrDsMcCQS4xICSZKq'
WHERE id = 2;

--------------------------------------------

◯　現在作業しているディレクトリまでのパスを取得

pwd

--------------------------------------------

◯　デスクトップへ移動

cd ~/Desktop

--------------------------------------------

◯　キーボードショートカット

Finder で Shift + Command + H

ホームディレクトリに一瞬で飛べます

--------------------------------------------