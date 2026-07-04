<?php //93点 ?>
@php
use Carbon\Carbon;
@endphp
<!doctype html>
<html>
<head>
<meta charset="utf-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0, minimum-scale=1.0">
<title></title>
<script type="text/javascript" charset="UTF-8"></script>
<script src="https://code.jquery.com/jquery-3.6.0.min.js" integrity="sha256-/xUj+3OJU5yExlq6GSYGSHk7tPXikynS7ogEvDej/m4=" crossorigin="anonymous"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/jquery-csv/1.0.21/jquery.csv.min.js"></script>

{{-- ---------------------------------------------------------------------------------------- --}}
<script src="{{ asset('js/sessionMonitor.js') }}?v={{ time() }}" charset="utf-8"></script>

<script src="{{ asset('js/system.js') }}?v={{ time() }}"></script>
<script src="{{ asset('js/ui-table-style.js') }}?v={{ time() }}"></script>
{{-- ---------------------------------------------------------------------------------------- --}}

<link href="{{ asset('css/style.css') }}?v={{ time() }}" rel="stylesheet">

</head>
<body class="month_archive">

@include('components.user-group-list', [
    'groupedUsers' => $groupedUsers
])
  
  <div id="wrapper">

    <div class="user_information">
      <form name="form" action="" method="get" id="form"><?php // GET ?>

        <div class="sarch_t">

          年度 
          @php
              $selectedYear = request('year_select') ?? date('Y');
          @endphp

          <select class="select month_select" name="year_select">
              <option value="">選択してください</option>
              <option value="2025" {{ $selectedYear == "2025" ? 'selected' : '' }}>2025</option>
              <option value="2026" {{ $selectedYear == "2026" ? 'selected' : '' }}>2026</option>
          </select>
          
            &nbsp;&nbsp;月度 

            @php
                $selectedMonth = request('month_select') ?? date('m');
            @endphp

            <select class="select month_select" name="month_select">
                <option value="">選択してください</option>
                @for($m = 1; $m <= 12; $m++)
                    @php
                        $monthValue = sprintf('%02d', $m);
                    @endphp
                    <option value="{{ $monthValue }}" 
                        {{ $selectedMonth == $monthValue ? 'selected' : '' }}>
                        {{ $m }}月
                    </option>
                @endfor
            </select>

            &nbsp;&nbsp;日付 

            @php
                $searchDay = request('day_select') ?? '';
            @endphp

            <select class="select day_select" name="day_select">
                <option value="">選択してください</option>
                <option value="1日〜10日" {{ $searchDay == "1日〜10日" ? 'selected' : '' }}>1日〜10日</option>
                <option value="10日〜20日" {{ $searchDay == "10日〜20日" ? 'selected' : '' }}>10日〜20日</option>
                <option value="20日〜31日" {{ $searchDay == "20日〜31日" ? 'selected' : '' }}>20日〜31日</option>
                <option value="1日〜15日" {{ $searchDay == "1日〜15日" ? 'selected' : '' }}>1日〜15日</option>
                <option value="15日〜20日" {{ $searchDay == "15日〜20日" ? 'selected' : '' }}>15日〜20日</option>
                <option value="1日〜31日" {{ $searchDay == "1日〜31日" ? 'selected' : '' }}>1日〜31日</option>
            </select>

          <?php /*--------------------------------------------------------*/ ?>
            &nbsp;&nbsp;利用者 

            <select class="select user_name_select" name="user_select">
                <option value="">選択してください</option>

                @foreach($groupedUsers as $initial => $list)
                    @foreach($list as $item)
                        <option 
                            value="{{ $item->name }}"
                            {{ ($selectedUser ?? '') == $item->name ? 'selected' : '' }}
                            data-notes="{{ $item->support_notes ?? '' }}"
                        >
                            {{ $item->name }}
                        </option>
                    @endforeach
                @endforeach
            </select>

          <?php /*--------------------------------------------------------*/ ?>
          <input type="submit" value="検索">
        </div>
      </form>

      <!--Laravelはセキュリティ（CSRF対策）のためにログアウトを POSTで行う-->
      <form method="POST" action="{{ route('logout') }}">
          @csrf
          <input type="hidden" name="recaptcha_token" class="recaptcha_token">
          
          <button type="submit">ログアウト</button>
      </form>
      <a href="{{ route('dashboard') }}">TOPへ</a>
    </div>

<?php
//------------------------------------------------------------------------------------------------------------------
// 配列をオブジェクトに変換
$select_tb = array_map(function($item){
    return (object) $item;
}, $select_tb);
//------------------------------------------------------------------------------------------------------------------
/*       ここは回数通り入ってる　　　　*/
echo '<pre>';
//print_r($select_tb);
echo '</pre>';
/*       ここは回数通り入ってる　　　　*/
//------------------------------------------------------------------------------------------------------------------
$data = $select_tb;
//------------------------------------------------------------------------------------------------------------------
$result = [];

// 料金ルール
$singlePrice = 200;      // 単独初乗り
$sharedPrice = 100;      // 乗合初乗り（半額）
$extraKmPriceSingle = 60; // 単独 5km超え1kmごと
$extraKmPriceShared = 30; // 乗合 5km超え1kmごと

foreach ($data as $item) {

    $name = $item->user;

    if (!isset($result[$name])) {
        $result[$name] = (object)[
            '名前' => $name,
            '単独・回数' => 0,
            '単独・金額' => 0,
            '単独・km加算' => 0,
            '乗合・回数' => 0,
            '乗合・金額' => 0,
            '乗合・km加算' => 0,
            '保険外・回数' => 0,
            '保険外・金額' => 0,
            '保険外・km加算' => 0,
            '合計金額' => 0,
        ];
    }

    // --- ★ここが重要 ---
    $distance = is_numeric($item->distance) ? (float)$item->distance : null;
    $extraKm = ($distance !== null) ? max(0, ceil($distance - 5)) : 0;
    // --------------------


    // 単独 / 乗合 / 保険外 振り分け
    if ($item->classification === '保険外') {
        $result[$name]->保険外・回数 += 1;
        $result[$name]->保険外・金額 += $singlePrice;
        $result[$name]->保険外・km加算 += $extraKm * $extraKmPriceSingle;
        $result[$name]->合計金額 += $singlePrice + $extraKm * $extraKmPriceSingle;
    } else { // 介護保険・障害福祉
        if ($item->shareRide) {
            // 乗合
            $result[$name]->乗合・回数 += 1;
            $result[$name]->乗合・金額 += $sharedPrice;
            $result[$name]->乗合・km加算 += $extraKm * $extraKmPriceShared;
            $result[$name]->合計金額 += $sharedPrice + $extraKm * $extraKmPriceShared;
        } else {
            // 単独
            $result[$name]->単独・回数 += 1;
            $result[$name]->単独・金額 += $singlePrice;
            $result[$name]->単独・km加算 += $extraKm * $extraKmPriceSingle;
            $result[$name]->合計金額 += $singlePrice + $extraKm * $extraKmPriceSingle;
        }
    }
}

// foreachで使いやすい形に変換
$finalTable = array_values($result);

// 確認
//print_r($finalTable);


if(!empty($finalTable)){
//------------------------------------------------------------------------------------------------------------------
echo '<table class="table_content">';

// ① ヘッダー（そのまま使う）
echo '<tr>
<th></th>
<th colspan="4">単独</th>
<th colspan="4">乗合</th>
<th colspan="4">保険外</th>
<th>合計</th>
<th colspan="2">移動支援費</th>
</tr>';

// ② サブヘッダー
echo '<tr class="bg_color1">
<th>利用者</th>
<th>回数</th><th>金額</th><th>1km加算</th><th>金額</th>
<th>回数</th><th>金額</th><th>1km加算</th><th>金額</th>
<th>回数</th><th>金額</th><th>1km加算</th><th>金額</th>
<th>金額</th>
<th>回数</th><th>金額</th>
</tr>';

// ③ データ
foreach ($finalTable as $row) {

    // ---- 加算金額（すでに金額が入ってる） ----
    $tandoku_extra = $row->{'単独・km加算'};
    $norai_extra   = $row->{'乗合・km加算'};
    $hoken_extra   = $row->{'保険外・km加算'};

    // ---- 合計（初乗り＋加算）※ただし加算0なら空欄 ----
    $tandoku_total = ($tandoku_extra > 0)
        ? $row->{'単独・金額'} + $tandoku_extra
        : '';

    $norai_total = ($norai_extra > 0)
        ? $row->{'乗合・金額'} + $norai_extra
        : '';

    $hoken_total = ($hoken_extra > 0)
        ? $row->{'保険外・金額'} + $hoken_extra
        : '';

    echo '<tr>';

    echo '<td>'.$row->名前.'</td>';

    // ---------------- 単独 ----------------
    echo '<td>'.$row->{'単独・回数'}.'</td>';
    echo '<td class="bg_color2">'.$row->{'単独・金額'}.'</td>';

    // km加算（0なら空）
    echo '<td>'.($tandoku_extra > 0 ? $tandoku_extra / 60 : '').'</td>';

    // km加算の金額（0なら空）
    echo '<td class="bg_color2">'.($tandoku_extra > 0 ? $tandoku_extra : '').'</td>';

    // ---------------- 乗合 ----------------
    echo '<td>'.$row->{'乗合・回数'}.'</td>';
    echo '<td class="bg_color2">'.$row->{'乗合・金額'}.'</td>';

    echo '<td>'.($norai_extra > 0 ? $norai_extra / 30 : '').'</td>';
    echo '<td class="bg_color2">'.($norai_extra > 0 ? $norai_extra : '').'</td>';

    // ---------------- 保険外 ----------------
    echo '<td>'.$row->{'保険外・回数'}.'</td>';
    echo '<td class="bg_color2">'.$row->{'保険外・金額'}.'</td>';

    echo '<td>'.($hoken_extra > 0 ? $hoken_extra / 60 : '').'</td>';
    echo '<td class="bg_color2">'.($hoken_extra > 0 ? $hoken_extra : '').'</td>';

    // ---------------- 移動支援費 ----------------
    $moveCount = $row->{'保険外・回数'};
    $movePrice = $moveCount * 1500;

    // ---------------- 合計（全体） ----------------
    echo '<td class="bg_color3">'.$row->{'合計金額'} + $movePrice.'</td>';



    echo '<td>'.$moveCount.'</td>';
    echo '<td>'.$movePrice.'</td>';

    echo '</tr>';
}

echo '</table>';
?>
<?php
//------------------------------------------------------------------------------------------------------------------
  if(!empty($sarch_user)){
?>
  <div class="place">
    <div class="inner">
      <table>
      <tr><th colspan="3">単独</th></tr>
        <?php
          foreach($arrayArea1 as $users => $val){
            $value = array_count_values($val);
            foreach($value as $key => $val){
              echo '<tr><td>'.$users.'</td><td>'.$key.'</td><td class="ct">'.$val.'</td></tr>';
            }
          }
        ?>
      </table>
    </div>
    <div class="inner">
      <table>
      <tr><th colspan="3">乗合</th></tr>
        <?php
          foreach($arrayArea2 as $users => $val){
            $value = array_count_values($val);
            foreach($value as $key => $val){
              echo '<tr><td>'.$users.'</td><td>'.$key.'</td><td class="ct">'.$val.'</td></tr>';
            }
          }
        ?>
      </table>
    </div>
    <div class="inner">
      <table>
      <tr><th colspan="3">保険外</th></tr>
        <?php
          foreach($arrayArea3 as $users => $val){
            $value = array_count_values($val);
            foreach($value as $key => $val){
              echo '<tr><td>'.$users.'</td><td>'.$key.'</td><td class="ct">'.$val.'</td></tr>';
            }
          }
        ?>
      </table>
    </div>
  </div>
<?php
  }
?>
<?php
  echo '<div class="prevPage">';
  echo '<a href="./dashboard">戻る</a>';
  if(empty($sarch_user)){
?>
<form action="{{ route('month.archive.downloadCsv') }}" method="get">
    <input type="hidden" name="user_select" value="{{ $selectedUser }}">
    <input type="hidden" name="year_select" value="{{ $selectedYear }}">
    <input type="hidden" name="month_select" value="{{ $selectedMonth }}">
    <input type="hidden" name="day_select" value="{{ $sarch_day }}">
    <button type="submit">CSVダウンロード</button>
</form>
<?php
    //echo '&nbsp;&nbsp;<a class="btn">CSVダウンロード</a>';
  }
  echo '</div>';
?>
<a style="display:none;" id="downloader" href="#"></a>
<?php
}else{
?>
<div class="page404">
  <img src="./image/404.webp" alt="404"><br>
  該当する検索結果がありません
</div>
<div class="prevPage"><a href="./dashboard">戻る</a></div>
<?php } ?>
</div>
<div class="sp">
  <div class="page404">
    <img src="./image/404.webp" alt="404"><br>
    NO Responsive
    <div class="prevPage sp"><a href="./dashboard">戻る</a></div>
  </div>
</div>

</div>

<x-recaptcha />

</body>
</html>