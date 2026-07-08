<!doctype html>
<html>
<head>
<meta charset="utf-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0, minimum-scale=1.0">
<title>乗降予約・キャンセル受付・編集</title>
<x-app-data :records="$user_destination_records" />
<script type="text/javascript" charset="UTF-8"></script>
<script src="https://code.jquery.com/jquery-3.6.0.min.js" integrity="sha256-/xUj+3OJU5yExlq6GSYGSHk7tPXikynS7ogEvDej/m4=" crossorigin="anonymous"></script>
{{-- ---------------------------------------------------------------------------------------- --}}
<script src="{{ asset('js/sessionMonitor.js') }}?v={{ time() }}" charset="utf-8"></script>

<script src="{{ asset('js/system.js') }}?v={{ rand() }}"></script>
<script src="{{ asset('js/master-user-destination.js') }}?v={{ rand() }}"></script>
<script src="{{ asset('js/closeWindow.js') }}?v={{ time() }}" charset="utf-8"></script>

<script src="{{ asset('js/openWindow.js') }}?v={{ time() }}" charset="utf-8"></script>
{{-- ---------------------------------------------------------------------------------------- --}}
<link href="{{ asset('css/style.css') }}?v={{ rand() }}" rel="stylesheet">
</head>
<body class="reservation_edit" data-mode="{{ $mode ?? '' }}">

@include('components.user-group-list', [
    'groupedUsers' => $groupedUsers
    
])

@php
    $isCancelMode = ($mode === 'support');
@endphp

<div id="wrapper">
    <div id="form">

@php
$action = ($mode === 'support')
    ? route('cancel.update', $data->id)
    : route('boarding_reservation.update', $data->id);
@endphp

<form action="{{ $action }}" method="post">

        @csrf
            <div class="wrap">
                <div class="wrap__inner">
                    <div class="fbox">
                        <table class="boarding_reservation_tb">
                            <tr>
                                <th>
                                    @if($isCancelMode)
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
                                                    {{ $data->user == $item->name ? 'selected' : '' }}
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

@if(!empty($data->destination))
                                    <div class="destination_wrap">
                                        <span>行き先</span>

                                        <select class="destination input" name="destination" data-init="{{ $data->destination }}">
                                            <option value="">選択してください</option>
                                        </select>

                                        <div class="error_msg" id="error_destination">
                                            @error('error_destination')
                                                {{ $message }}
                                            @enderror
                                        </div>
                                    </div>
@endif
                                </td>
                                <td>
                                    <span>
                                        @if($isCancelMode)
                                            キャンセル日時
                                        @else
                                            予約日時
                                        @endif
                                    </span>

                                    @php
                                        $dt = $isCancelMode
                                            ? optional($data->cancel_date ? \Carbon\Carbon::parse($data->cancel_date) : null)
                                            : optional($data->reservation_datetime ? \Carbon\Carbon::parse($data->reservation_datetime) : null);
                                    @endphp

                                    @foreach ($selects as $select)

                                        {{-- キャンセル時は「時・分」をスキップ --}}
                                        @if($isCancelMode && ($select['label'] === '時' || $select['label'] === '分'))
                                            @continue
                                        @endif

                                        <select class="ymdselect" name="ymdselect[]">

                                            @for ($i = $select['start']; $i <= $select['end']; $i += $select['step'])

                                                <option value="{{ $i }}"
                                                    @if(
                                                        ($select['label'] === '年' && $i == $dt->year) ||
                                                        ($select['label'] === '月' && $i == $dt->month) ||
                                                        ($select['label'] === '日' && $i == $dt->day) ||
                                                        ($select['label'] === '時' && $i == $dt->hour) ||
                                                        ($select['label'] === '分' && $i == (int)$dt->format('i'))
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
                                                {{ (old('client_name', $data->client_name) == $client) ? 'selected' : '' }}>
                                                {{ $client }}
                                            </option>
                                        @endforeach
                                    </select>  

                                    <div class="error_msg" id="error_client_name">
                                        @error('client_name')
                                            {{ $message }}
                                        @enderror
                                    </div>
                                </td>
                                <td>
                                    <span>受付者</span>

                                    <?php /*              
                                    <select class="input receptionist" name="receptionist">
                                        <option value="">選択してください</option>

                                        @foreach(config('employees.list') as $name)
                                            <option value="{{ $name }}"
                                                {{ (old('receptionist', $data->receptionist) == $name) ? 'selected' : '' }}>
                                                {{ $name }}
                                            </option>
                                        @endforeach

                                    </select>
                                    */ ?>

<select class="input receptionist" name="receptionist">
    <option value="">選択してください</option>

    @foreach($members as $name)
        <option value="{{ $name }}"
            {{ old('receptionist', $data->receptionist) == $name ? 'selected' : '' }}>
            {{ $name }}
        </option>
    @endforeach
</select>

                                    <div class="error_msg" id="error_receptionist">
                                        @error('receptionist')
                                            {{ $message }}
                                        @enderror
                                    </div>
                                </td>


                                <?php /*
                                <td>
                                    <span>入力日</span>
                                    @php
                                        $inputDate = $data->input_date
                                            ? \Carbon\Carbon::parse($data->input_date)->format('Y/m/d H:i')
                                            : '';
                                    @endphp

                                    <input type="text"
                                        class="readonly_i input"
                                        name="input_date"
                                        value="{{ old('input_date', $inputDate) }}"
                                        readonly>
                                </td>
                                */ ?>

                                <td>
                                    <span>入力日</span>

                                    @php
                                    // 受付日時(input_date)と更新日時(updated_at)を比較
                                    // 異なる場合は編集・反映などで更新されたと判断してupdated_atを表示
                                    // 同じ場合は初回登録なのでinput_dateを表示
                                        $displayDate = $data->updated_at != $data->input_date
                                            ? $data->updated_at
                                            : $data->input_date;
                                            
                                    // 表示形式を YYYY/MM/DD HH:mm に変換
                                        $displayDate = $displayDate
                                            ? \Carbon\Carbon::parse($displayDate)->format('Y/m/d H:i')
                                            : '';
                                    @endphp

                                    <input type="text"
                                        class="readonly_i input"
                                        name="input_date"
                                        value="{{ old('input_date', $displayDate) }}"
                                        readonly>
                                </td>
                                <td>
                                    @if($isCancelMode)

                                        <span>キャンセル日時と費用</span>

                                        <select class="input place" name="place">
                                            <option value="">選択してください</option>

                                            @foreach(config($isCancelMode ? 'place.cancel' : 'place.normal') as $item)
                                                <option value="{{ $item }}"
                                                    @selected(old('place', $data->place ?? '') == $item)
                                                >
                                                    {{ $item }}
                                                </option>
                                            @endforeach

                                        </select>

                                    @else

                                        <span>場所</span>

                                        <select class="input place" name="place">
                                            <option value="">選択してください</option>

                                            @foreach(config('place.normal') as $item)
                                                <option value="{{ $item }}"
                                                    {{ (old('place', $data->place) == $item) ? 'selected' : '' }}>
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
                                    <input type="text"
                                        class="attention readonly_i input"
                                        name="attention"
                                        value="{{ old('attention', $data->attention) }}"
                                        readonly>
                                </td>
                                <td>
                                    <span>備考</span>
                                    <textarea class="inputextarea" name="remarks_txt">{{ old('remarks_txt', $data->remarks_txt) }}</textarea>
                                </td>
                            </tr>
                        </table>
                    </div>
                    <div class="b_box">
                        <div class="prevPage no-print">
                            @if($isCancelMode)
                                <input type="submit" name="submit" class="submit" value="更新">&nbsp;&nbsp;&nbsp;
                                <?php /*<a href="{{ route('reservation_search.page', ['cancel' => 1]) }}">戻る</a>*/ ?>
                                <a href="{{ route('dashboard') }}">戻る</a>
                            @else
                                <input type="submit" name="submit" class="submit" value="更新">&nbsp;&nbsp;&nbsp;
                                <a href="{{ route('dashboard') }}">戻る</a>
                            @endif
                        </div>
                    </div>
                </div>
            </div>
            <input type="hidden" name="submit_value" id="submit_value" value="default">
        </form>
    </div>
    <div id="overflow"><div class="conf"><img src="{{ asset('image/404.webp') }}"><p></p><button class="closeBtn">閉じる</button></div></div>
</div>

<x-recaptcha />

</body>
</html>