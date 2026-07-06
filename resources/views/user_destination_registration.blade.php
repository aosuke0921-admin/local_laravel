<!doctype html>
<html>
<head>
<meta charset="utf-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0, minimum-scale=1.0">
<title>利用者・行き先・登録</title>
<script type="text/javascript" charset="UTF-8"></script>
<script src="https://code.jquery.com/jquery-3.6.0.min.js" integrity="sha256-/xUj+3OJU5yExlq6GSYGSHk7tPXikynS7ogEvDej/m4=" crossorigin="anonymous"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/jquery-csv/1.0.21/jquery.csv.min.js"></script>

{{-- ---------------------------------------------------------------------------------------- --}}
<script src="{{ asset('js/sessionMonitor.js') }}?v={{ time() }}" charset="utf-8"></script>

<script src="{{ asset('js/system.js') }}?v={{ rand() }}"></script>
<script src="{{ asset('js/mobile-dropdown.js') }}?v={{ time() }}" charset="utf-8"></script>
<script src="{{ asset('js/device.js') }}?v={{ time() }}" charset="utf-8"></script>
<script src="{{ asset('js/open_window_expansion.js') }}?v={{ time() }}" charset="utf-8"></script>
<script src="{{ asset('js/user_destination_registration.js') }}?v={{ time() }}" charset="utf-8"></script>
<script src="{{ asset('js/closeWindow.js') }}?v={{ time() }}" charset="utf-8"></script>
<script src="{{ asset('js/openWindow.js') }}?v={{ time() }}" charset="utf-8"></script>
{{-- ---------------------------------------------------------------------------------------- --}}

<link href="{{ asset('css/style.css') }}?v={{ rand() }}" rel="stylesheet">

</head>
<body class="user_destination_registration destination_registration">

    @include('components.user-group-list', [
        'groupedUsers' => $groupedUsers
    ])

<div id="wrapper">

    <form action="{{ route('user_destination_registration.post') }}" method="post" id="form">
    @csrf
    <div class="wrap">
        <div class="wrap__inner">
        <div class="f_box">
        <div class="tit">利用者・行き先・登録</div>
        <table>
            <tr>
                <th>利用者</th>
                <td>
                    <select class="input select user_name_select" name="user_name">
                        <option value="">選択してください</option>
                        @foreach($groupedUsers as $initial => $list)
                            @foreach($list as $item)
                                <option value="{{ $item->name }}">
                                    {{ $item->name }}
                                </option>
                            @endforeach
                        @endforeach
                    </select>
                    <div class="error_msg" id="error_user_name">
                        @error('error_user_name')
                            {{ $message }}
                        @enderror
                    </div>
                </td>
            </tr>
            <tr>
                <th>行き先</th>
                <td>
                    <select class="input select user_name_selects" name="destination">
                        <option value="">選択してください</option>
                        @foreach($groupedDestinations as $initial => $list)
                            @foreach($list as $item)
                                <option value="{{ $item->destination }}">
                                    {{ $item->destination }}
                                </option>
                            @endforeach
                        @endforeach
                    </select>
                    <div class="error_msg" id="error_destination">
                        @error('error_destination')
                            {{ $message }}
                        @enderror
                    </div>
                </td>
            </tr>
            <tr>
                <th>迎え地</th>
                <td>
                    <input type="text"  class="input" value="" name="pickup_location" placeholder="自宅と異なる時は入力してください">
                    <div class="error_msg" id="error_destination">
                        @error('error_destination')
                            {{ $message }}
                        @enderror
                    </div>     
                </td>
            </tr>
            <tr>
                <th>透析</th>
                <td>
                    <label><input type="radio" name="dialysis" value="1" class="inputs" checked="checked"> あり</label> 
                    <label><input type="radio" name="dialysis" value="0" class="inputs" > なし</label>
                </td>
            </tr>
            <tr>
                <th>移動支援費</th>
                <td>
                    <label><input type="radio" name="transport_fee" value="1" class="inputs" checked="checked"> あり </label>
                    <label><input type="radio" name="transport_fee" value="0" class="inputs" > なし</label>
                </td>
            </tr>
            <tr>
                <th>距離</th>
                <td>
                    <div class="google_map">
                        <a aria-label="Google マップでこの場所までのルートを検索できます。" target="_blank" jstcache="38" href="https://maps.google.com/maps/dir//%E3%82%B9%E3%83%9E%E3%82%A4%E3%83%AB%E3%83%8F%E3%83%BC%E3%83%88+%E3%80%92514-0821+%E4%B8%89%E9%87%8D%E7%9C%8C%E6%B4%A5%E5%B8%82%E5%9E%82%E6%B0%B4%EF%BC%92%EF%BC%99%EF%BC%98%EF%BC%95%E2%88%92%EF%BC%91/@34.6962266,136.5044791,16z/data=!4m5!4m4!1m0!1m2!1m1!1s0x60040c933b4e1581:0x72bcd52c05bfb752" class="navigate-link"> <div class="icon navigate-icon"></div> <div jstcache="39" class="navigate-text">ルート検索</div> </a>
                    </div>

                    <input type="number" value=""  class="input int" name="distance" placeholder="(例) 2.1" step="any">

                    <div class="error_msg" id="error_distance">
                        @error('error_distance')
                            {{ $message }}
                        @enderror
                    </div>
                </td>
            </tr>
        </table>
        </div>
        <div class="b_box">
            <div class="prevPage no-print">
                  <input type="submit" name="submit" class="submit" value="登録">&nbsp;&nbsp;&nbsp;
                  <a href="{{ route('dashboard') }}">戻る</a>
            </div>
        </div>
    </div>
    </div>
    </form>
</div>
<div id="overflow"><div class="conf"><img src="./image/404.webp"><p></p><button class="closeBtn">閉じる</button></div></div>

<x-recaptcha />

</body>
</html>