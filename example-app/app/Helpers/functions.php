<?php
//文字コード
header("Content-Type:text/html;charset=utf-8");

//セッション
session_start();

/*------------------------------------------------------
 アドレスバーがらファイル名取得・拡張子なし
------------------------------------------------------*/
$path = $_SERVER['PHP_SELF'];
$filename_no_ext = pathinfo($path, PATHINFO_FILENAME);

if($filename_no_ext != 'check_login' && $filename_no_ext != 'inspection_check' && $filename_no_ext != 'insert' && $filename_no_ext != 'post' && $filename_no_ext != 'preview' && $filename_no_ext != 'delete' && $filename_no_ext != 'archive' && $filename_no_ext != 'month_archive' && $filename_no_ext != 'print' && $filename_no_ext != 'user_destination_registration' && $filename_no_ext != 'user_registration' && $filename_no_ext != 'master' && $filename_no_ext != 'reservation_search' && $filename_no_ext != 'cancel_edit' && $filename_no_ext != 'reservation_edit'){
//DB
    /*
    function db_connection(){
      //サーバー
      $dsn = 'mysql:dbname=LAA1624884-contactform;host=mysql312.phy.lolipop.lan;charset=utf8';

      //ログイン情報
      $user = 'LAA1624884';
      $password = 'naoki593939';

      //DB接続
      $dbh = new PDO($dsn,$user,$password);
      //実行結果 var_dump($dbh);
      $dbh->query('SET NAMES utf8');
      $dbh->setAttribute(PDO::ATTR_EMULATE_PREPARES, false);
      return $dbh;
    }
    */

    //tagを特殊文字に変換
    function h($string) {
      //関数の外で定義したグローバル変数を使用
      global $encode;
      return htmlspecialchars($string, ENT_QUOTES,$encode);
    }
    //selectCheck
    /*
    function selectCheck($var){
      $checkArray = [
        '選択してください',
        'ア','イ','ウ','エ','オ',
        'カ','キ','ク','ケ','コ',
        'サ','シ','ス','セ','ソ',
        'タ','チ','ツ','テ','ト',
        'ナ','ニ','ヌ','ネ','ノ',
        'ハ','ヒ','フ','ヘ','ホ',
        'マ','ミ','ム','メ','モ',
        'ヤ','ユ','ヨ',
        'ラ','リ','ル','レ','ロ',
        'ワ','ヲ','ン'
      ];
      foreach($checkArray as $val){
        if(in_array($var,$checkArray)){
          $result = str_replace($var,'-', $var);
        }else{
          $result = $var;
        }
      }
      return $result;
    }
    */
}
/************************************************************************************************/
// 徐々にこっちへすべて入れてく
/************************************************************************************************/

$setting = [

    /*--------------------------------------------------------------------------------------
     修正済みページ
     post.php
    -------------------------------------------------------------------------------------- */
    'app' => 'logute',
    'version' => '2.0',
    'charset' =>'utf-8',
    'meta_name' =>'viewport',
    'viewport' =>'width=device-width, initial-scale=1.0, minimum-scale=1.0',
    'type_css' =>'text/css',
    'type_js' =>'text/javascript',
    'rel_css' =>'stylesheet',
    'jquery' =>'https://code.jquery.com/jquery-3.6.0.min.js',
    'integrity' =>'sha256-/xUj+3OJU5yExlq6GSYGSHk7tPXikynS7ogEvDej/m4=',
    'crossorigin' =>'anonymous',
    'jquery_csv' =>'https://cdnjs.cloudflare.com/ajax/libs/jquery-csv/1.0.21/jquery.csv.min.js',
    'system' =>'./js/system.js',
    'css' =>'./css/style.css',

    //データベース ------------------------------------------------------------------------------
    /*
    'db' => 'mysql:dbname=LA05539686-dailyreport;host=mysql321.phy.lolipop.lan;charset=utf8',
    'user' => 'LA05539686',
    'pass' => 'smile95195184',
    */
    'db' => 'mysql:dbname=LAA1624884-contactform;host=mysql312.phy.lolipop.lan;charset=utf8',
    'user' => 'LAA1624884',
    'pass' => 'naoki593939',
    //csv -------------------------------------------------------------------------------------

    //テスト用CSV
    'test.csv'=> './csv/test.csv',

    //行き先・登録
    'destination_name.csv'=> './csv/destination_name.csv',

    //利用者・登録
    'user_name.csv'=> './csv/user_name.csv',

    //利用者・行き先・登録
    'user.csv'=> './csv/user.csv',

    //請求コード登録・介護保険
    'billing_code_01.csv'=> './csv/billing_code_01.csv',

    //請求コード登録・障害福祉
    'billing_code_02.csv'=> './csv/billing_code_02.csv'

    //-----------------------------------------------------------------------------------------
];

class Myclass {

    public $v1;
    public $v2;
    public $v3;

    // コンストラクタ
    public function __construct($v1,$v2,$v3){

        $this->v1 = $v1;
        $this->v2 = $v2;
        $this->v3 = $v3;

    }
//------------------------------------------------------------------------

    // DB接続メソッド
    public function db_connection(){

      $dsn = $this->v1['db'];
      $user = $this->v1['user'];
      $password = $this->v1['pass'];

      //DB接続
      $dbh = new PDO($dsn,$user,$password);

      //実行結果 var_dump($dbh);

      $dbh->query('SET NAMES utf8');
      $dbh->setAttribute(PDO::ATTR_EMULATE_PREPARES, false);
      return $dbh;
    }

//------------------------------------------------------------------------

    // CSV値を配列へ
    public function get_csv_array() {

        // phpプログラムが扱うロケールを「ja_JP.UTF-8」に設定します。
        setlocale(LC_ALL, ".UTF8");

        // sample.csvの中身を取得し文字列に変換します。
        //$file = file_get_contents($this->csv);

        $file = file_get_contents($this->v1);

        // $fileの文字コードをUTF-8に変換し$utf8_dataに格納します。
        $utf8_data = mb_convert_encoding($file, 'UTF-8');

        // テンポラリファイル$tmpを作成します。
        $tmp = tmpfile();

        // $utf8_dataの中身を$tmpに書き込みます。
        fwrite($tmp, $utf8_data);

        // $tmpのファイルポインタの位置を先頭に戻します。
        rewind($tmp);

        // $tmpを一行ずつ読み込み、出力します。
        while ($data = fgetcsv($tmp)) {
           $array[] = $data;
        }

        // $tmpを削除します。
        fclose($tmp);

        return $array;

    }

//------------------------------------------------------------------------

    // 配列をjson形式へ
    public function json_encode() {

      $php_json = json_encode($this->v1);

      return $php_json;
      
    }
//------------------------------------------------------------------------
    //途中

    // <?php 変換
    public function php_tag_replace() {

        $filepath = './'.$this->v1;
        $file_content = file_get_contents($filepath);

        //置き換えたい文字の配列
        $search = array('[ ', ' ]');

        //置き換え後の文字の配列
        $replace = array('<?php ',' ?>'); 

        $new_content = str_replace($search, $replace, $file_content);

        // ファイルに書き込む
        file_put_contents($filepath, $new_content);

    }

//------------------------------------------------------------------------

    // CSVを配列へ・キー6削除
    public function billing_code_csv(){
        $data = file_get_contents($this->v1);
        $rows = explode("\n", $data);
        $get_csv_array = [];
        foreach ($rows as $row) {
            $file_array = str_getcsv($row);
            foreach($file_array as $val){
                $get_csv_array[] = $val;
            }
        }
        unset($get_csv_array[6]);

        return $get_csv_array;
    }

//------------------------------------------------------------------------

    // 所定単位・確認メソッド
    public function invoice_method1(){

      $a = ($this->v1 * $this->v2);

      //0.92881800
      $b = $a * 0.245;

      $c = floor($b);

      //echo $c;

      $d = $a + $c;

      echo $d; // 所定単位

      return $a;

    }

//------------------------------------------------------------------------

    // 請求書内の計算1
    public function invoice_method5(){

      $result = $this->v1 + $this->v2 + $this->v3;

      return $result;

    }

//------------------------------------------------------------------------

    public function setting($var){

        $result = $this->v1[$var];

        return $result;

    }

//------------------------------------------------------------------------

    // セレクトメニュー1
    public function user_hospital_list(){

            $array = [];

        if(!empty($this->v3)){

            foreach($this->v1 as $val){

                  $headStr = mb_substr($val[1],0,1);

                  $nameStr = $val[0];

                  //$nameStr = 'A';

                  $huriganaStr = $val[1];

                  if($this->v3 == 1){



                    if(!empty($nameStr)){

                      if($this->v2 == $nameStr){

                        echo '<option selected>'.$nameStr.'</option>';

                      }else{

                        echo '<option>'.$nameStr.'</option>';

                      }
                    }

                      //$head = $headStr;

                  }else if($this->v3 == 2){

                      $array[$headStr][] = $nameStr;
                  }
            }
            return $array;
            }

    }

//------------------------------------------------------------------------

    // セレクトメニュー2
    public function option_set(){

          if($this->v3 == "1日〜10日"){
            //$selected1 = "selected";

            $selected_flg = 1;
          }
          if($this->v3 == "10日〜20日"){
            //$selected2 = "selected";

            $selected_flg = 2;
          }
          if($this->v3 == "20日〜31日"){
            //$selected3 = "selected";

            $selected_flg = 3;
          }
          if($this->v3 == "1日〜15日"){
            //$selected4 = "selected";

            $selected_flg = 4;
          }
          if($this->v3 == "15日〜20日"){
            //$selected5 = "selected";

            $selected_flg = 5;
          }
          if($this->v3 == "1日〜31日"){
            //$selected6 = "selected";

            $selected_flg = 6;
          }

          return $this->v3.','.$selected_flg;

    }

//------------------------------------------------------------------------

    // 特殊文字エスケープ
    public function h(){

      if(is_array($this->v1)){

        //配列
        $result = array_map('htmlspecialchars',$this->v1);

      }else{

        $result = htmlspecialchars($this->v1, ENT_QUOTES);

      }

      return $result;

    }

//------------------------------------------------------------------------

    //URL id パラメータ取得
    public function get_parameter(){

      if(isset($this->v1)){
        $id = $this->v1;
      }
      return $id;
      
    }

//------------------------------------------------------------------------

    //重複の値を1つにして配列
    public function duplicationCheck(){

            //配列のコピー
            $arrayCopy = $this->v1;

            $newArray = [];

            foreach($this->v1 as $val){

                $newArray[] = $val[0];

                //重複を消した後の配列
                //$arrayUnique = array_unique($newArray);

                //重複を消す前の配列
                $arrayUnique = $newArray;

            }
            //配列の中で1つしかない容要素
            $aaa = array_keys(array_count_values($arrayUnique), 1);

            //重複した要素を探す
            $bbb = array_filter(array_count_values($arrayUnique), function($v){return --$v;});

            foreach($bbb as $key => $val){
                $eee[] = $key;
            }


            if(!empty($bbb)){

                //配列を結合する
                $ccc = array_merge($aaa, $eee);

            }else{

                $ccc = $aaa;
            }

//echo '<pre>';
//print_r($arrayUnique);
//print_r($aaa);
//echo '</pre>';



                $newArray = [];

                $i = 0;
                foreach($ccc as $kkk => $vvv){

                    foreach($arrayCopy as $key => $val){

                        foreach($val as $k => $name){

                            if($vvv == $name){
                                $newArray[$i][0] = $name;
                                $newArray[$i][1] = $val[1];
                            }

                        }

                    }
                    $i++;

                }
                return $newArray; 

            
    }
/*------------------------------------------------------------------------*/





public function kana_sort(){
    /* ----------------------------------------------------------------------------
     カタカナだけの配列を昇順にソート
    ---------------------------------------------------------------------------- */
    $kana_array = [];

    $new_array = [];

    $i = 0;
    foreach($this->v1 as $val){

        foreach($val as $cval){

            foreach($cval as $k => $v){

                if($k == 1){

                    $kana_array[] = $v;

                }
            }
        }

        $i++;
    }

    sort($kana_array);

    foreach($kana_array as $val){

        foreach($this->v1 as $cval){

            foreach($cval as $v){

                if($val == $v[1]){

                    $head_str = mb_substr($v[1], 0, 1, 'UTF-8'); // 頭文字

                    $new_array[$head_str][] = $v[0];

                }

            }
        }
    }

    return $new_array;
}

























 /*------------------------------------------------------------------------
 //メソッドの中でメソッド test
 ------------------------------------------------------------------------*/
    
    public function method1() {
        $aaa = $this->v1;
        $ccc = $this->method2($aaa); // ← test


        $test = 100;


        return $ccc;
    }

    public function method2($aaa) {
        $res = $aaa * 2;
        return $res;
    }

}

/************************************************************************************************/
/************************************************************************************************/

/*------------------------------------------------------------------------*/
// Class
/*------------------------------------------------------------------------*/

class Myclass1 {

    // メソッド
    public function db_connection(){
      //サーバー
      /*
      $dsn = 'mysql:dbname=LA05539686-dailyreport;host=mysql321.phy.lolipop.lan;charset=utf8';
      $user = 'LA05539686';
      $password = 'smile95195184';
      */

      $dsn = 'mysql:dbname=LAA1624884-contactform;host=mysql312.phy.lolipop.lan;charset=utf8';
      $user = 'LAA1624884';
      $password = 'naoki593939';

      //DB接続
      $dbh = new PDO($dsn,$user,$password);

      //実行結果 var_dump($dbh);

      $dbh->query('SET NAMES utf8');
      $dbh->setAttribute(PDO::ATTR_EMULATE_PREPARES, false);
      return $dbh;
    }
}

/*------------------------------------------------------------------------*/

class Myclass2 {

    public $csv;
    public $json;

    // コンストラクタ
    public function __construct($csv,$json) {
        $this->csv = $csv;
        $this->json = $json;
    }

    // メソッド
    public function get_csv_array() {

        // phpプログラムが扱うロケールを「ja_JP.UTF-8」に設定します。
        setlocale(LC_ALL, ".UTF8");

        // sample.csvの中身を取得し文字列に変換します。
        $file = file_get_contents($this->csv);

        // $fileの文字コードをUTF-8に変換し$utf8_dataに格納します。
        $utf8_data = mb_convert_encoding($file, 'UTF-8');

        // テンポラリファイル$tmpを作成します。
        $tmp = tmpfile();

        // $utf8_dataの中身を$tmpに書き込みます。
        fwrite($tmp, $utf8_data);

        // $tmpのファイルポインタの位置を先頭に戻します。
        rewind($tmp);

        // $tmpを一行ずつ読み込み、出力します。
        while ($data = fgetcsv($tmp)) {
           $array[] = $data;
        }

        // $tmpを削除します。
        fclose($tmp);

        return $array;

    }

    public function json_encode() {

      $php_json = json_encode($this->json);

      return $php_json;
      
    }

    public function billing_code_csv(){
        $data = file_get_contents($this->csv);
        $rows = explode("\n", $data);
        $get_csv_array = [];
        foreach ($rows as $row) {
            $file_array = str_getcsv($row);
            foreach($file_array as $val){
                $get_csv_array[] = $val;
            }
        }
        unset($get_csv_array[6]);

        return $get_csv_array;
    }
}

//-------------------------------------------------------

class Myclass3 {

    public $array;

    // コンストラクタ
    public function __construct($array) {
        $this->array = $array;
    }

    // メソッド
    public function h(){

      if(is_array($this->array)){

        //配列・特殊文字エスケープ
        $result = array_map('htmlspecialchars',$this->array);

      }else{

        $result = htmlspecialchars($this->array, ENT_QUOTES);

      }

      return $result;

    }


    //URL id パラメータ取得
    public function get_parameter(){

      if(isset($this->array)){
        $id = $this->array;
      }
      return $id;
      
    }


    //重複の値を1つにして配列
    public function duplicationCheck(){

            //配列のコピー
            $arrayCopy = $this->array;

            $newArray = [];

            foreach($this->array as $val){

                $newArray[] = $val[0];

                //重複を消した後の配列
                //$arrayUnique = array_unique($newArray);

                //重複を消す前の配列
                $arrayUnique = $newArray;

            }
            //配列の中で1つしかない容要素
            $aaa = array_keys(array_count_values($arrayUnique), 1);

            //重複した要素を探す
            $bbb = array_filter(array_count_values($arrayUnique), function($v){return --$v;});

            foreach($bbb as $key => $val){
                $eee[] = $key;
            }
            //配列を結合する
            $ccc = array_merge($aaa, $eee);

            $newArray = [];

            $i = 0;
            foreach($ccc as $kkk => $vvv){

                foreach($arrayCopy as $key => $val){

                    foreach($val as $k => $name){

                        if($vvv == $name){
                            $newArray[$i][0] = $name;
                            $newArray[$i][1] = $val[1];
                        }

                    }

                }
                $i++;

            }
            return $newArray;    
    }

}

//-------------------------------------------------------

class Myclass4 {

    public $array;
    public $user;
    public $flg;

    // コンストラクタ
    public function __construct($array,$user,$flg){

        $this->array = $array;
        $this->user = $user;
        $this->flg = $flg;

    }
    // メソッド
    public function user_hospital_list(){
         //$array = "";
            $array = [];

        if(!empty($this->flg)){

            foreach($this->array as $val){

                  $headStr = mb_substr($val[1],0,1);

                  $nameStr = $val[0];

                  $huriganaStr = $val[1];

                  if($this->flg == 1){

                      if($this->user == $nameStr){

                        echo '<option selected>'.$nameStr.'</option>';

                      }else{

                        echo '<option>'.$nameStr.'</option>';

                      }

                      //$head = $headStr;

                  }else if($this->flg == 2){

                      $array[$headStr][] = $nameStr;
                  }
            }
            return $array;
            }

    }

    public function option_set(){

          if($this->flg == "1日〜10日"){
            //$selected1 = "selected";

            $selected_flg = 1;
          }
          if($this->flg == "10日〜20日"){
            //$selected2 = "selected";

            $selected_flg = 2;
          }
          if($this->flg == "20日〜31日"){
            //$selected3 = "selected";

            $selected_flg = 3;
          }
          if($this->flg == "1日〜15日"){
            //$selected4 = "selected";

            $selected_flg = 4;
          }
          if($this->flg == "15日〜20日"){
            //$selected5 = "selected";

            $selected_flg = 5;
          }
          if($this->flg == "1日〜31日"){
            //$selected6 = "selected";

            $selected_flg = 6;
          }

          return $this->flg.','.$selected_flg;

    }
}
/*-------------------------------------------------------
 共通処理
-------------------------------------------------------*/

/*
//データベース接続
$dbh = new Myclass1();
$dbh = $dbh->db_connection();
/*-------------------------------------------------------*/
/*
//すべてこっちに移行していく
$db = new Myclass($setting,null,null);
$db = $db->db_connection();
/*-------------------------------------------------------*/


/*
//使用する配列を指定
$csv1 = new Myclass($setting['destination_name.csv'],null,null);

$csv2 = new Myclass($setting['user_name.csv'],null,null);

$csv3 = new Myclass($setting['user.csv'],null,null);

$testcsv = new Myclass($setting['test.csv'],null,null);

//CSVを配列へ
$csv1_array = $csv1->get_csv_array();
$csv2_array = $csv2->get_csv_array();
$csv3_array = $csv3->get_csv_array();


$testcsv_array = $testcsv->get_csv_array();

//CSV配列を整えて再び配列へ

//achievements.phpで使用
$achievenentsArray = $csv2_array;

//PHP配列をJS配列へ・使用する配列を指定
$csv4 = new Myclass($csv3_array,null,null);

//PHP配列をJS配列へ・実行
$php_json = $csv4->json_encode();

//-------------------------------------------------------

//重複の値を1つにして配列・使用する配列を指定
$csv1_ary = new Myclass($csv1_array,null,null);
$csv2_ary = new Myclass($csv2_array,null,null);
$csv3_ary = new Myclass($csv3_array,null,null);

$csv1_array = $csv1_ary->duplicationCheck();
$csv2_array = $csv2_ary->duplicationCheck();
$csv3_array = $csv3_ary->duplicationCheck();

//-------------------------------------------------------

$csv4_array = $csv3_ary;

//-------------------------------------------------------

//行き先・array 配列へ取得・使用する配列を指定
$csv1_aryy = new Myclass($csv1_array,null,2);
$csv1_aryy = $csv1_aryy->user_hospital_list();

//-------------------------------------------------------

//利用者・array 配列へ取得・使用する配列を指定
//$csv2_aryy = new Myclass4($csv2_array,null,2);
$csv2_aryy = new Myclass($csv2_array,null,2);
$csv2_aryy = $csv2_aryy->user_hospital_list();

//-------------------------------------------------------

//行き先・select option val 出力・使用する配列を指定
//$csv4_aryy = new Myclass4($csv1_array,$sarch4,1);
$csv4_aryy = new Myclass($csv1_array,null,1);

//-------------------------------------------------------

//利用者・select option val 出力・使用する配列を指定
//$csv3_aryy = new Myclass4($csv2_array,$sarch4,1);
$csv3_aryy = new Myclass($csv2_array,null,1);

//-------------------------------------------------------

//利用者・カタカナ全文字でソート
$new_array = new Myclass($csv3_aryy,null,null);
$new_kana_array = $new_array->kana_sort();

//-------------------------------------------------------

//行き先・カタカナ全文字でソート
$new_array = new Myclass($csv1_aryy,null,null);
$new_kana_ikisaki_array = $new_array->kana_sort();

//-------------------------------------------------------
*/

?>