<!doctype html>
<html>
<head>
<meta charset="utf-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0, minimum-scale=1.0">
<title>更新</title>
<script type="text/javascript" charset="UTF-8"></script>
<script src="https://code.jquery.com/jquery-3.6.0.min.js" integrity="sha256-/xUj+3OJU5yExlq6GSYGSHk7tPXikynS7ogEvDej/m4=" crossorigin="anonymous"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/jquery-csv/1.0.21/jquery.csv.min.js"></script>

{{-- ---------------------------------------------------------------------------------------- --}}
<script src="{{ asset('js/sessionMonitor.js') }}?v={{ time() }}" charset="utf-8"></script>

<script src="{{ asset('js/device.js') }}?v={{ time() }}" charset="utf-8"></script>

<script src="{{ asset('js/calcPrice.js') }}?v={{ time() }}" charset="utf-8"></script><!--まだsystem.jsにあるsystemより上に書く-->
<script src="{{ asset('js/system.js') }}?v={{ time() }}" charset="utf-8"></script>
<script src="{{ asset('js/page-feedback.js') }}?v={{ time() }}" charset="utf-8"></script>
<script src="{{ asset('js/total.js') }}?v={{ time() }}" charset="utf-8"></script>
<script src="{{ asset('js/mobile-dropdown.js') }}?v={{ time() }}" charset="utf-8"></script>
<?php /*<script src="{{ asset('js/form_load_save.js') }}?v={{ time() }}" charset="utf-8"></script>*/ ?>
<script src="{{ asset('js/time_validation.js') }}?v={{ time() }}" charset="utf-8"></script>
<script src="{{ asset('js/scroll.js') }}?v={{ time() }}" charset="utf-8"></script>

<script src="{{ asset('js/distanceValidation.js') }}?v={{ time() }}" charset="utf-8"></script>

<script src="{{ asset('js/sharedRide.js') }}?v={{ time() }}" charset="utf-8"></script>
<script src="{{ asset('js/checkboxControl.js') }}?v={{ time() }}" charset="utf-8"></script>
<script src="{{ asset('js/hospitalSelectHandler.js') }}?v={{ time() }}" charset="utf-8"></script>

<script src="{{ asset('js/classification.js') }}?v={{ time() }}" charset="utf-8"></script>

<script src="{{ asset('js/userDestination.js') }}?v={{ time() }}" charset="utf-8"></script>

<script src="{{ asset('js/closeWindow.js') }}?v={{ time() }}" charset="utf-8"></script>

<script src="{{ asset('js/openWindow.js') }}?v={{ time() }}" charset="utf-8"></script>
{{-- ---------------------------------------------------------------------------------------- --}}
<script src="{{ asset('js/CaptionRunControl.js') }}?id={{ time() }}" charset="utf-8"></script>
<script src="{{ asset('js/date_picker.js') }}?v={{ time() }}"></script>
<link href="{{ asset('css/date_picker.css') }}?v={{ time() }}" rel="stylesheet">
<link href="{{ asset('css/style.css') }}?v={{ time() }}" rel="stylesheet" type="text/css">
{{-- ---------------------------------------------------------------------------------------- --}}
<script src="{{ asset('js/Calendar/Calendar.js') }}?v={{ time() }}" charset="utf-8"></script>
<link rel="stylesheet" href="{{ asset('js/Calendar/Calendar.css') }}?v={{ time() }}" charset="utf-8">
{{-- ---------------------------------------------------------------------------------------- --}}

@viteReactRefresh

@vite('resources/react/index.tsx')

</head>
<body class="preview-page">

<div class="react" data-component="LogoTitle"></div>

    @include('components.user-group-list', [

        'groupedUsers' => $groupedUsers
        
    ])

<div id="wrapper">

{{-- --------- PC --------- --}}

<form method="POST" action="{{ route('change.date') }}" id="change_date">
    @csrf

    @include('components.date-change')

    <input type="hidden" name="redirect_to" value="preview">
    <input type="hidden" name="referer" value="preview_referer">

</form>

{{-- --------- sp --------- --}}
<div class="user_information">

  <ul>
    
    <li><dl><dt>乗務者</dt><dd><span>{{ session('user_name') }}</span></dd></dl></li>

    <li><dl><dt>車種</dt><dd><span>{{ session('car') }}</span></dd></dl></li>

    <li><dl><dt>日付</dt><dd><span>{{ \Carbon\Carbon::parse($date)->format('Y年n月j日') }}</span></dd></dl></li>
  </ul>
  
  {{-- Laravelはセキュリティ（CSRF対策）のためにログアウトを POSTで行う --}}
    <form method="POST" action="{{ route('logout') }}">
      @csrf

      <input type="hidden" name="recaptcha_token" class="recaptcha_token">

      <button type="submit">ログアウト</button>
  </form>
  <a href="{{ route('dashboard') }}">TOPへ</a>
</div>


<div class="react" data-component="GlobalNav"></div>

{{-- Laravelはセキュリティ（CSRF対策）のためにPOSTで行う --}}
<form name="form" action="{{ route('ride.bulkUpdate') }}" method="POST" id="form">
    @csrf
    <input type="hidden" name="recaptcha_token" class="recaptcha_token">
 
    <table class="tb">

        <tr>
            <th>利用者</th><th>発時刻</th><th>着時刻</th><th>行き/帰り</th><th>行先</th>
            <th>任意行先（手入力）</th><th>乗合</th><th>区分</th><th>備考欄</th><th>距離</th><th>料金</th>
        </tr>

        {{-- @if(request('error') == 'no_data') --}}
        
        @if($posts->isEmpty())
            <tr>
                <td colspan="11">該当データなし</td>
            </tr>
        @endif

        @foreach($posts as $index => $post)

        <caption class="input_area_c input_area_c{{ $loop->index }}">

                  運行{{ $loop->iteration }}             

              <span class="toggle_run">ー</span>

        </caption>

        <tbody class="input_area_t input_area_t{{ $loop->index }}">

                <tr id="name{{ $loop->index }}">
                <input type="hidden" name="id[]" value="{{ $post->id }}">
                    <td>
                        <select class="select user_name_select" name="user[]">
                            
                            @foreach($groupedUsers as $kana => $users)

                                @foreach($users as $user)
                                    <option 
                                        value="{{ $user->name }}"
                                        data-classification="{{ $user->classification }}"
                                        @selected($post->user === $user->name)
                                    >
                                        {{ $user->name }}
                                    </option>
                                @endforeach

                            @endforeach

                        </select>

                    </td>
                    <td>
                        <input type="time" class="departure start" name="departureTime[]" value="{{ old('departureTime', $post->departureTime ?? '') }}" required>
                    </td>
                    <td>
                        <input type="time" class="departure end" value="{{ $post->arrivalTime }}" name="arrivalTime[]">
                    </td>
                    <td>
                        <select class="to_and_from" name="goingBack[]">
                            <option value="">-</option>
                            <option value="行き" @if($post->goingBack === '行き') selected @endif>行き</option>
                            <option value="帰り" @if($post->goingBack === '帰り') selected @endif>帰り</option>
                        </select>
                    </td>
                    <td>
                        <select class="select hospital_select" name="destinations[]">
                            <option value="">選択してください</option>
                            @foreach($posts as $optionPost)
                                <option value="{{ $optionPost->destination }}"
                                    @if($optionPost->destination === $post->destination) selected @endif>
                                    {{ $optionPost->destination }}
                                </option>
                            @endforeach
                        </select>
                    </td>
                    <td>
                        <input type="text" name="any[]" class="input any" value="{{ $post->any }}">
                    </td>
                    <td>

                        {{-- checkboxはチェックある値しか送信できないのでチェックなし初期値を0設定 --}}
                        <input type="hidden" name="shareRide[{{ $loop->index }}]" value="0">

                        {{-- DBの値が1だったらチェックをつける --}}
                        <input type="checkbox" class="sharedRide" name="shareRide[{{ $loop->index }}]" value="1" @if($post->shareRide == 1) checked @endif>

                    </td>
                    <td>
                        <select name="classification[]" class="classification">
                            <option value="介護保険" @if($post->classification == '介護保険') selected @endif>介護保険</option>
                            <option value="障害福祉" @if($post->classification == '障害福祉') selected @endif>障害福祉</option>
                            <option value="保険外" @if($post->classification == '保険外') selected @endif>保険外</option>
                        </select>
                    </td>
                    <td>
                        <input type="text" class="input remarks" name="remarks[]" value="{{ $post->remarks }}"><!-- 備考欄 -->
                    </td>
                    <td>
                        <input type="text" class="d{{ $loop->index }} distance" name="distance[]" value="{{ $post->distance ?? '' }}" readonly>
                    </td>
                    <td>
                        <input type="text" class="p{{ $loop->index }} price" name="price[]" value="{{ $post->price ?? '' }}" readonly>
                    </td>
                </tr>

            @endforeach
        </tbody>
        </table>

    <div class="prevPage">
        <a href="{{ route('dashboard') }}">戻る</a>
    </div>
    <div class="fixed">
        <div class="inner">
            @if(!$posts->isEmpty())
                <div class="item">合計距離<input type="text" name="" class="total_distance fixed_input" value="" readonly></div>
                <div class="item">合計金額<input type="text" name="" class="total_amount fixed_input" value="" readonly></div> 
                <div class="item">
                    始業距離<input type="number"
                        maxlength="6"
                        name="start_distance"
                        class="start_distance fixed_input"
                        value="{{ old('start_distance', $post->start_distance ?? '') }}"
                        step="0.1">
                </div>
                <div class="item">
                    終業距離<input type="number"
                        maxlength="6"
                        name="end_distance"
                        class="end_distance fixed_input"
                        value="{{ old('end_distance', $post->end_distance ?? '') }}"
                        step="0.1">
                </div>
            @endif
        </div>
        <div class="insert_btn">
            <a href="{{ route('dashboard') }}"><img src="{{ asset('image/prev.png') }}" alt="" class="prev_btn"></a>

            @if(!$posts->isEmpty())

            <input type="submit" name="submit" class="post_btn" value="更新">

            @endif

            <img src="{{ asset('image/pagetop.png') }}" alt="" class="pagetop_btn">
        </div>
    </div>
    <input type="hidden" name="dates" value="{{ $date }}">
</form>
</div>

<div id="overflow"><div class="conf"><img src="{{ asset('image/404.webp') }}"><p></p><button class="closeBtn">閉じる</button></div></div>

<x-recaptcha />

</body>
</html>