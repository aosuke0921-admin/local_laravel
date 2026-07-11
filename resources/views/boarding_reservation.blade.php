<!doctype html>
<html>
<head>
<meta charset="utf-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0, minimum-scale=1.0">
<title>乗降予約・キャンセル受付</title>
<script type="text/javascript" charset="UTF-8"></script>
<script src="https://code.jquery.com/jquery-3.6.0.min.js" integrity="sha256-/xUj+3OJU5yExlq6GSYGSHk7tPXikynS7ogEvDej/m4=" crossorigin="anonymous"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/jquery-csv/1.0.21/jquery.csv.min.js"></script>
<script>
    // boarding_reservation.jsで使用するためPHP配列をjs配列に変換
    // window.をつけるとグローバルになりboarding_reservation.js内で使用できる
    window.records = @json($records);
</script>
{{-- ---------------------------------------------------------------------------------------- --}}
<script src="{{ asset('js/sessionMonitor.js') }}?v={{ time() }}" charset="utf-8"></script>

<script src="{{ asset('js/system.js') }}?v={{ rand() }}"></script>
<script src="{{ asset('js/mobile-dropdown.js') }}?v={{ time() }}" charset="utf-8"></script>
<script src="{{ asset('js/device.js') }}?v={{ time() }}" charset="utf-8"></script>
<script src="{{ asset('js/boarding_reservation.js') }}?v={{ time() }}" charset="utf-8"></script>
<script src="{{ asset('js/closeWindow.js') }}?v={{ time() }}" charset="utf-8"></script>

<script src="{{ asset('js/openWindow.js') }}?v={{ time() }}" charset="utf-8"></script>
{{-- ---------------------------------------------------------------------------------------- --}}
<link href="{{ asset('css/style.css') }}?v={{ rand() }}" rel="stylesheet">

@viteReactRefresh

@vite('resources/react/index.tsx')

</head>
<body class="reservation_edit" data-mode="{{ $mode }}">

<div class="react" data-component="LogoTitle"></div>

    @include('components.user-group-list', [
        'groupedUsers' => $groupedUsers
    ])

<div id="wrapper">

<div id="wrap">
    <div id="form">
        <form action="{{ route('boarding_reservation.store') }}" method="post">
        @csrf
            <div class="wrap">
                <div class="wrap__inner">
                    <div class="fbox">
                        <table class="boarding_reservation_tb">
                            <tr>
                                <th>
                                    @if($mode === 'support')
                                        キャンセル予約
                                    @else
                                        乗降予約
                                    @endif
                                </th>
                                <td>
                                    <span>利用者名</span>
                                    <select class="select user_name_select input" name="user_name">
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
                                    <div class="error_msg" id="error_user_name">
                                        @error('error_user_name')
                                            {{ $message }}
                                        @enderror
                                    </div>
                                </td>
                                <td>

                                    <div class="destination_wrap">
                                        <span>行き先</span>
                                        <select class="destination input" name="destination">
                                            <option value="">選択してください</option>
                                        </select>

                                        <div class="error_msg" id="error_destination">
                                            @error('error_destination')
                                                {{ $message }}
                                            @enderror
                                        </div>
                                    </div>

                                </td>
                                <td>
                                    <span>
                                        @if($mode === 'support')
                                            キャンセル日時
                                        @else
                                            予約日時
                                        @endif
                                    </span>
                                    @php
                                        $now = \Carbon\Carbon::now();

                                        $roundedMinute = round($now->minute / 5) * 5;

                                        if ($roundedMinute == 60) {
                                            $roundedMinute = 55;
                                        }
                                    @endphp

                                    @foreach ($selects as $select)

                                        {{-- キャンセル時は「時・分」をスキップ --}}
                                        @if(($mode === 'support' || $mode === 'boarding') && ($select['label'] === '時' || $select['label'] === '分'))
                                            @continue
                                        @endif

                                        <select class="ymdselect" name="ymdselect[]">

                                            @for ($i = $select['start']; $i <= $select['end']; $i += $select['step'])

                                                <option value="{{ $i }}"
                                                    @if(
                                                        ($select['label'] === '年' && $i == $now->year) ||
                                                        ($select['label'] === '月' && $i == $now->month) ||
                                                        ($select['label'] === '日' && $i == $now->day) ||

                                                        (
                                                            ($mode !== 'boarding' && $mode !== 'support')
                                                            && $select['label'] === '時'
                                                            && $i == $now->hour
                                                        ) ||

                                                        (
                                                            ($mode !== 'boarding' && $mode !== 'support')
                                                            && $select['label'] === '分'
                                                            && $i == $roundedMinute
                                                        )
                                                    )
                                                        selected
                                                    @endif
                                                >
                                                    {{ $i }}
                                                </option>

                                            @endfor

                                        </select>

                                        &nbsp;{{ $select['label'] }}&nbsp;

                                    @endforeach
                                </td>
                                <td>
                                    <span>依頼者</span>
                                    <select class="input client_name" name="client_name">
                                        <option value="">選択してください</option>
                                        @foreach(['本人','家族','ケアマネ','病院','その他'] as $client)
                                            <option value="{{ $client }}" 
                                                {{ old('client_name') == $client ? 'selected' : '' }}>
                                                {{ $client }}
                                            </option>
                                        @endforeach
                                    </select>  
                                    <div class="error_msg" id="error_client_name">
                                        @error('error_client_name')
                                            {{ $message }}
                                        @enderror
                                    </div>
                                </td>
                                <td>
                                    <span>受付者</span>

                                    <input type="text" class="readonly_i input" name="receptionist" value="{{ auth()->user()->full_name }}" readonly>
                                    
                                    <?php /*
                                    <select class="input receptionist" name="receptionist" readonly_i readonly>
                                        <option value="">選択してください</option>

                                        @foreach($members as $name)
                                            <option value="{{ $name }}"
                                                {{ old('receptionist', auth()->user()->full_name) == $name ? 'selected' : '' }}>
                                                {{ $name }}
                                            </option>
                                            <?php /*
                                            <option value="{{ $name }}"
                                                {{ old('receptionist') == $name ? 'selected' : '' }}>
                                                {{ $name }}
                                            </option>
                                        @endforeach
                                    </select>*/ ?>

                                    <div class="error_msg" id="error_receptionist">
                                        @error('error_receptionist')
                                            {{ $message }}
                                        @enderror
                                    </div>
                                </td>
                                <td>
                                    <span>入力日</span>
                                    <input type="text" class="readonly_i input" name="input_date" value="{{ date('Y/m/d H:i') }}" readonly>
                                </td>
                                <td>
                                    @if($mode === 'support')

                                        <span>キャンセル日時と費用</span>

                                        <select class="input place" name="place">
                                            <option value="">選択してください</option>

                                            @foreach(config('place.cancel') as $item)
                                                <option value="{{ $item }}" {{ old('place') == $item ? 'selected' : '' }}>
                                                    {{ $item }}
                                                </option>
                                            @endforeach

                                        </select>

                                    @else

                                        <span>場所</span>

                                        <select class="input place" name="place">
                                            <option value="">選択してください</option>

                                            @foreach(config('place.normal') as $item)
                                                <option value="{{ $item }}" {{ old('place') == $item ? 'selected' : '' }}>
                                                    {{ $item }}
                                                </option>
                                            @endforeach

                                        </select>

                                    @endif

                                    <div class="error_msg" id="error_place">
                                        @error('error_place')
                                            {{ $message }}
                                        @enderror
                                    </div>
                                </td>
                                <td class="both">
                                    <span>支援上の留意点</span>
                                    <input type="text" class="attention readonly_i input" value="" name="attention" readonly>
                                </td>
                                <td>
                                    <span>備考</span>
                                    <textarea class="inputextarea" name="remarks_txt"></textarea>
                                </td>
                            </tr>
                        </table>
                    </div>
                    <div class="b_box">
                        <div class="prevPage no-print">
                            @if($mode === 'support')
                                <input type="submit" name="submit" class="submit" value="キャンセル受付">
                                &nbsp;&nbsp;&nbsp;

                                <a href="{{ route('reservation_search.page', [
                                    'mode' => 'support'
                                ]) }}" class="no-print">
                                    キャンセル一覧
                                </a>

                                &nbsp;&nbsp;&nbsp;<a href="{{ route('dashboard') }}" class="no-print">戻る</a>

                            @else
                                <input type="submit" name="submit" class="submit" value="登録">&nbsp;&nbsp;&nbsp;

                                <a href="{{ route('reservation_search.page') }}" class="no-print">乗降予約一覧</a>&nbsp;&nbsp;&nbsp;

                                <a href="{{ route('dashboard') }}">戻る</a>
                            @endif
                        </div>
                    </div>
                </div>
            </div>
            @php
                $submitValue = ($mode === 'support')
                    ? 'cancel'
                    : 'reserve';
            @endphp

            <input type="hidden" name="submit_value" id="submit_value" value="{{ $submitValue }}">
        </form>
    </div>
    <div id="overflow"><div class="conf"><img src="./image/404.webp"><p></p><button class="closeBtn">閉じる</button></div></div>
</div>

</div>

<x-recaptcha />

</body>
</html>