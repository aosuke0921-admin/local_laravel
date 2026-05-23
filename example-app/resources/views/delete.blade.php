<?php //95点 ?>
<!doctype html>
<html>
<head>
<meta charset="utf-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0, minimum-scale=1.0">
<title></title>
<script type="text/javascript" charset="UTF-8"></script>
<script src="https://code.jquery.com/jquery-3.6.0.min.js" integrity="sha256-/xUj+3OJU5yExlq6GSYGSHk7tPXikynS7ogEvDej/m4=" crossorigin="anonymous"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/jquery-csv/1.0.21/jquery.csv.min.js"></script>
<script src="{{ asset('js/system.js') }}" charset="utf-8"></script>
<script src="{{ asset('js/plugin/DeleteCheck/DeleteCheck.js') }}"></script>
<link href="{{ asset('css/style.css') }}" rel="stylesheet" type="text/css">
<script src="https://www.google.com/recaptcha/api.js?render={{ config('recaptcha.site_key') }}"></script>
<script>
$(function(){
    $('.delete').DeleteCheck_Plugin();
});
</script>
</head>
<body class="preview-page delete">
<div class="user_information">

  <ul>
    <li><dl><dt>乗務者</dt><dd><span>{{ session('user_name') }}</span></dd></dl></li>
    <li><dl><dt>日付</dt><dd><span>{{ $date }}</span></dd></dl></li>
    <li><dl><dt>車種</dt><dd><span>{{ $headerPost->car }}</span></dd></dl></li>
    <li><dl><dt>始業距離</dt><dd><span>{{ $headerPost->start_distance }}</span></dd></dl></li>
  </ul>

  <!--Laravelはセキュリティ（CSRF対策）のためにログアウトを POSTで行う-->
  <form method="POST" action="{{ route('logout') }}">
      @csrf
      <input type="hidden" name="recaptcha_token" class="recaptcha_token">

      <button type="submit">ログアウト</button>
  </form>
  <a href="{{ route('dashboard') }}">TOPへ</a>
</div>
<form action="{{ route('smile_posts.deleteMultiple') }}" method="POST" id="form">
    @csrf
    <input type="hidden" name="recaptcha_token" class="recaptcha_token">

    <table class="tb">
        
        <thead>
            <tr>
                <th>利用者</th><th>発時刻</th><th>着時刻</th>
                <th>行き/帰り</th><th>行先</th><th>任意行先（手入力）</th>
                <th>乗合</th><th>区分</th><th>備考欄</th><th>距離</th><th>料金</th>
            </tr>
        </thead>

        @foreach($posts as $index => $post)
        <caption>運行{{ $index + 1}}<span>ー</span></caption>
        <tbody>
            <tr>
                <td>
                    <div class="app_checkbox">
                        <input type="checkbox" id="checkbox_id{{ $index }}" name="delete_check[]" class="delete_check" value="{{ $post->id }}">
                        <label for="checkbox_id{{ $index }}"></label>
                    </div>
                    <select class="select user_name_select" style="pointer-events: none;" tabindex="-1">
                        <option>{{ $post->user }}</option>
                    </select>
                </td>
                <td><span class="time_val">{{ $post->departureTime }}</span></td>
                <td><span class="time_val">{{ $post->arrivalTime }}</span></td>
                <td>
                    <select class="to_and_from" name="goingBack[]" style="pointer-events: none;" tabindex="-1">
                        <option> - </option>
                        <option {{ $post->goingBack == '行き' ? 'selected' : '' }}>行き</option>
                        <option {{ $post->goingBack == '帰り' ? 'selected' : '' }}>帰り</option>
                    </select>
                </td>
                <td>
                    <input type="text" class="hospital_name" name="destination[]" value="{{ $post->destination }}">
                    <select class="select hospital_select" name="destinations[]" style="pointer-events: none;" tabindex="-1">
                        <option>{{ $post->destination }}</option>
                    </select>
                </td>
                <td>
                    <input type="text" name="any[]" class="input any" value="{{ $post->any }}" disabled="disabled">
                </td>
                <td>
                    <input type="hidden" name="shareRide[{{ $index }}]" value="0">
                    <input type="checkbox" name="shareRide[{{ $index }}]" class="sharedRide" disabled="disabled" value="乗合" {{ $post->shareRide ? 'checked' : '' }}>
                </td>
                <td>
                    <select name="classification[]" style="pointer-events: none;" tabindex="-1">
                        <option {{ $post->classification == '介護保険' ? 'selected' : '' }}>介護保険</option>
                        <option {{ $post->classification == '障害福祉' ? 'selected' : '' }}>障害福祉</option>
                        <option {{ $post->classification == '保険外' ? 'selected' : '' }}>保険外</option>
                    </select>
                </td>
                <td><input type="text" class="input remarks" name="remarks[]" value="{{ $post->remarks }}" disabled="disabled"></td>
                <td><input type="text" class="d0 distance" name="distance[]" value="{{ $post->distance }}" disabled="disabled"></td>
                <td><input type="text" class="p0 price" name="price[]" value="{{ $post->price }}" disabled="disabled"></td>
            </tr>
            </tbody>
            @endforeach
    </table>

    <div class="d-print-none pc">
        <input type="checkbox" id="checkall" class="checkall_box" title="すべてチェックする">
        <label for="checkall">すべてチェックする</label>
    </div>

    <div class="prevPage pc">
        <a href="{{ route('dashboard') }}">戻る</a>
    </div>

    <div class="fixed">
        <div class="inner">
            <div class="item">合計距離<input type="text" class="total_distance fixed_input" value="" readonly></div>
            <div class="item">合計金額<input type="text" class="total_amount fixed_input" value="" readonly></div>
        </div>
        <div class="insert_btn">
            <a href="{{ route('dashboard') }}"><img src="{{ asset('image/prev.png') }}" class="prev_btn"></a>
            <input type="submit" name="submit" class="post_btn delete_btn" value="削除">
            <span class="clone_btn">削除</span>
            <img src="{{ asset('image/pagetop.png') }}" class="pagetop_btn">
        </div>
    </div>
</form>

<x-recaptcha />

</body>
</html>