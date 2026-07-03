{{-- バリデーションエラー確認用
@if ($errors->any())
    <div style="color:red;">
        @foreach ($errors->all() as $error)
            <p>{{ $error }}</p>
        @endforeach
    </div>
@endif
--}}
<!doctype html>
<html>
<head>
<meta charset="utf-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0, minimum-scale=1.0">
<title>logute.com</title>
<link rel="shortcut icon" href="./image/favicon.ico" type="image/x-icon"><!--favicon pc-->
<link rel="apple-touch-icon" href="./image/apple-touch-icon.png" sizes="180x180"><!--favicon iOS-->
<link rel="icon" type="image/png" href="./image/android-touch-icon.png" sizes="192x192"><!--favicon Android-->
<script type="text/javascript" charset="UTF-8"></script>
<script src="https://code.jquery.com/jquery-3.6.0.min.js" integrity="sha256-/xUj+3OJU5yExlq6GSYGSHk7tPXikynS7ogEvDej/m4=" crossorigin="anonymous"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/jquery-csv/1.0.21/jquery.csv.min.js"></script>

{{-- ---------------------------------------------------------------------------------------- --}}
<script src="{{ asset('js/device.js') }}?v={{ time() }}" charset="utf-8"></script>
<script src="{{ asset('js/system.js') }}?v={{ time() }}" charset="utf-8"></script>
<script src="{{ asset('js/page-feedback.js') }}?v={{ time() }}" charset="utf-8"></script>
<script src="{{ asset('js/total.js') }}?v={{ time() }}" charset="utf-8"></script>
<script src="{{ asset('js/mobile-dropdown.js') }}?v={{ time() }}" charset="utf-8"></script>

<script src="{{ asset('js/form_load_save.js') }}?v={{ time() }}" charset="utf-8"></script>
<script src="{{ asset('js/time_validation.js') }}?v={{ time() }}" charset="utf-8"></script>
{{-- ---------------------------------------------------------------------------------------- --}}

<link href="{{ asset('css/style.css') }}?id={{ time() }}" rel="stylesheet" type="text/css">

<script src="{{ asset('js/CaptionRunControl.js') }}?id={{ time() }}" charset="utf-8"></script>

<script src="https://www.google.com/recaptcha/api.js?render={{ config('recaptcha.site_key') }}"></script>
</head>
<body class="post-page">

  @include('components.user-group-list', [
      'groupedUsers' => $groupedUsers
  ])

<div id="wrapper">
  <div class="user_information">

  <?php //---------------------------------------------------------------------------------------?>
    <ul><!-- 登録画面は、登録してあるデータを取得して表示でないので、全てsession -->
      <li><dl><dt>乗務者</dt><dd><span>{{ session('user_name') }}</span></dd></dl></li>
      <li><dl><dt>日付</dt><dd><span>{{ $displayDate }}</span></dd></dl></li>
      <li><dl><dt>車種</dt><dd><span>{{ session('car') }}</span></dd></dl></li>
      <li><dl><dt>始業距離</dt><dd><span>{{ session('start_distance') }}</span></dd></dl></li>
    </ul>

    <!--Laravelはセキュリティ（CSRF対策）のためにログアウトを POSTで行う-->
    <form method="POST" action="{{ route('logout') }}">
        @csrf
        <input type="hidden" name="recaptcha_token" class="recaptcha_token">

        <button type="submit">ログアウト</button>
    </form>

    <a href="{{ route('dashboard') }}">TOPへ</a>
  </div>

  <!--Laravelはセキュリティ（CSRF対策）のためにPOSTで行う-->
  <form action="{{ route('post.store') }}" method="POST">
  @csrf
  <input type="hidden" name="recaptcha_token" class="recaptcha_token">

    <table class="tb">

        @php
            $rows = range(0, 13);
        @endphp

        @foreach ($rows as $i)

        <div class="run_block">

          <caption class="input_area_c input_area_c{{ $i }}">

              <span class="delete_run">✕</span>

              <span class="run_title">
                  運行{{ $startIndex + $i }}
              </span>

              <span class="toggle_run">ー</span>

          </caption>

          <tbody class="input_area_t input_area_t{{ $i }}">  
            @if($i === 0)
            <tr>
              <th>利用者</th>
              <th>発時刻</th>
              <th>着時刻</th>
              <th>行き/帰り</th>
              <th>行先</th>
              <th>任意行先（手入力）</th>
              <th>乗合</th>
              <th>区分</th>
              <th>備考欄</th>
              <th>距離</th>
              <th>料金</th>
            </tr>
            @endif
            <tr id="name{{ $i }}" class="row">  
              <td>

              <?php /*
                  <select class="select user_name_select" name="user[{{ $i }}]">
                    <option value="">選択してください</option>
                    @foreach($groupedUsers as $initial => $list)
                        @foreach($list as $item)
                            <option 
                                value="{{ $item->name }}"
                                data-notes="{{ $item->support_notes ?? '' }}"
                            >
                                {{ $item->name }}
                            </option>
                        @endforeach
                    @endforeach
                </select>
              */ ?>

              <select class="select user_name_select" name="user[{{ $i }}]">
                  <option value="">選択してください</option>

                  @foreach($groupedUsers as $initial => $list)
                      @foreach($list as $item)
                          <option
                              value="{{ $item->name }}"
                              data-notes="{{ $item->support_notes ?? '' }}"
                              data-classification="{{ $item->classification ?? '' }}"
                          >
                              {{ $item->name }}
                          </option>
                      @endforeach
                  @endforeach
              </select>


              </td>
              <td><input type="time" value="" class="departure start" name="departureTime[{{ $i }}]"></td>
              <td><input type="time" value="" class="departure end" name="arrivalTime[{{ $i }}]"></td>
              <td>
                <select class="to_and_from" name="goingBack[{{ $i }}]">
                  <option> - </option>
                  <option>行き</option>
                  <option>帰り</option>
                </select>             
              </td>
              <td>
                <select class="select hospital_select" name="destinations[{{ $i }}]">
                  <option>選択してください</option>
                </select>              
              </td>
              <td>
                <input type="text" name="any[{{ $i }}]" class="input any" value="">
              </td>
              <td>
                <input type="hidden" name="shareRide[{{ $i }}]" value="0">
                <input type="checkbox" name="shareRide[{{ $i }}]" class="sharedRide" value="1">
              </td>
              <td>
                <select name="classification[{{ $i }}]" class="classification">
                  <option value="">選択してください</option>
                  <option value="介護保険">介護保険</option>
                  <option value="障害福祉">障害福祉</option>
                  <option value="保険外">保険外</option>
                </select>
              </td>
              <td>
                <input type="text" class="input remarks" name="remarks[{{ $i }}]" value="">
              </td>
              <td>
                <input type="text" class="d{{ $i }} distance" name="distance[{{ $i }}]" value="" readonly>
              </td>
              <td>
                <input type="text" class="p{{ $i }} price" name="price[{{ $i }}]" value="" readonly>
              </td>
            </tr>
          </tbody>


        </div><!---追記--->


        @endforeach
      </table>
    <div class="addition_button">+</div>
    <div class="prevPage">

      <a href="{{ route('dashboard', [
          'dates' => session('dates'),
          'car' => session('car'),
          'start_distance' => session('start_distance')
      ]) }}">戻る</a>

    </div>
    <div class="fixed">
      <div class="inner">
        <div class="item">合計距離<input type="text" name="total_distance" class="total_distance fixed_input" value="0" readonly></div>
        <div class="item">合計金額<input type="text" name="total_amount" class="total_amount fixed_input" value="0" readonly></div>  
      </div>
      <div class="insert_btn">
        <?php /*<a href="{{ route('dashboard') }}"><img src="{{ asset('image/prev.png') }}" alt="" class="prev_btn"></a>*/ ?>

        <a href="{{ route('dashboard', [
            'dates' => session('dates'),
            'car' => session('car'),
            'start_distance' => session('start_distance')
        ]) }}"><img src="{{ asset('image/prev.png') }}" alt="" class="prev_btn"></a>

        <input type="submit" name="submit" class="post_btn" value="登録">
        <img src="{{ asset('image/pagetop.png') }}" alt="" class="pagetop_btn">
      </div>
    </div>
  </form>
</div>
<div id="overflow"><div class="conf"><img src="{{ asset('image/404.webp') }}" alt=""><p></p><button class="closeBtn">閉じる</button></div></div>

<x-recaptcha />

</body>
</html>