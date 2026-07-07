@php
    $mode = request('mode', 'boarding');
@endphp
<!doctype html>
<html>
<head>
<meta charset="utf-8">
<meta name="viewport" content="width=1300">
<title>@if($mode === 'boarding')乗降予約一覧検索@elseキャンセル一覧検索@endif</title>
<script type="text/javascript" charset="UTF-8"></script>
<script src="https://code.jquery.com/jquery-3.6.0.min.js" integrity="sha256-/xUj+3OJU5yExlq6GSYGSHk7tPXikynS7ogEvDej/m4=" crossorigin="anonymous"></script>
{{-- ---------------------------------------------------------------------------------------- --}}
<script src="{{ asset('js/sessionMonitor.js') }}?v={{ time() }}" charset="utf-8"></script>

<script src="{{ asset('js/recordError.js') }}?v={{ rand() }}"></script>
<script src="{{ asset('js/system.js') }}?v={{ time() }}" charset="utf-8"></script>
<script src="{{ asset('js/closeWindow.js') }}?v={{ time() }}" charset="utf-8"></script>
<script src="{{ asset('js/openWindow.js') }}?v={{ time() }}" charset="utf-8"></script>
{{-- ---------------------------------------------------------------------------------------- --}}
<link href="{{ asset('css/style.css') }}?v={{ time() }}" rel="stylesheet" type="text/css">
</head>
<body class="reservation_search {{ $mode === 'support' ? 'cancel_true' : 'cancel_false' }}">

@include('components.user-group-list', [
    'groupedUsers' => $groupedUsers
    
])

<div id="wrapper">

<div class="user_information no-print">

    <form method="GET">

        <input type="hidden" name="mode" value="{{ request('mode', 'boarding') }}">

        <div class="sarch_t">

            @php
                $year  = (int) request('year_select', now()->year);
                $month = (int) request('month_select', now()->month);
            @endphp

            {{-- 年度 --}}
            <div class="inner">
                年度
                <select class="year_select" name="year_select">
                    <option value="">選択してください</option>

                    @for($y = now()->year; $y >= now()->year - 2; $y--)
                        <option value="{{ $y }}"
                            {{ $year == $y ? 'selected' : '' }}>
                            {{ $y }}年
                        </option>
                    @endfor

                </select>
            </div>

            {{-- 月 --}}
            <div class="inner">
                月度
                <select class="moth_select" name="month_select">
                    <option value="">選択してください</option>

                    @for($m = 1; $m <= 12; $m++)
                        <option value="{{ $m }}"
                            {{ $month == $m ? 'selected' : '' }}>
                            {{ $m }}月
                        </option>
                    @endfor

                </select>
            </div>

            {{-- 利用者 --}}
            <div class="inner">
                利用者名
                <select class="select user_name_select" name="user_name_select">
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
            </div>

            {{-- 留意点 --}}
            <div class="inner">


                {{-- dd($attentions); --}}

                @if($mode === 'boarding')
                    <span>支援上の留意点 </span>
                    <select class="sien_select" name="sien_select">

                        <option>選択してください</option>
                        @foreach($attentions as $a)
                        
                            <option {{ request('sien_select') == $a ? 'selected' : '' }}>
                                {{ $a }}
                            </option>
                        @endforeach

                    </select>
                @endif

                <input type="submit" value="検索">

                <div class="link_box">

                    @if($mode === 'support')
                        <a href="{{ route('pop_select.page', ['mode' => 'support']) }}">キャンセル受付</a>    
                    @else
                        <?php /*<a href="{{ route('boarding_reservation.page', ['mode' => 'boarding']) }}">乗降予約</a>*/ ?>
                        <a href="{{ route('boarding_reservation.page') }}">乗降予約</a>
                    @endif

                </div>

            </div>
        </form>

    </div>

        <!--Laravelはセキュリティ（CSRF対策）のためにログアウトを POSTで行う-->
        <form method="POST" action="{{ route('logout') }}">
            @csrf
            <button type="submit">ログアウト</button>
        </form>
        <a href="{{ route('dashboard') }}">TOPへ</a>

</div>
<div id="form">
    <table class="reservation_search_tb">
        <tbody>

        <tr>
            <th>{{ $mode === 'support' ? 'キャンセル日' : '予約日時' }}</th>
            <th>利用者</th>
            <th>行き先</th>
            <th>{{ $mode === 'support' ? 'キャンセル費用' : '場所' }}</th>
            <th>受付者</th>
            <th>依頼者</th>
            <th>受付日時</th>
            @if($mode !== 'support')
                <th>反映日 / 反映者</th>
            @endif
            <th>支援上の留意点</th>
            <th>備考</th>
            <th></th>
            @if($mode !== 'support')
                <th>反映</th>
            @endif
        </tr>

        @php $count = 0; @endphp

        @foreach($data as $row)

        <tr>
                {{-- 日付 --}}
                <td>
                    @if($mode === 'support')
                        {{ optional($row->cancel_date)->format('Y/n/j') }}
                    @else
                        {{ optional($row->reservation_datetime)->format('Y/n/j G:i') }}
                    @endif
                </td>

                <td>{{ $row->user }}</td>

                <td>{{ $row->destination ?? 'なし' }}</td>

                <td>{{ $row->place }}</td>

                <td>{{ $row->receptionist }}</td>

                <td>{{ $row->client_name }}</td>

                {{-- 受付日時 --}}

                <td>
                    {{-- optional($row->created_at)->format('Y/n/j G:i') --}}

                    @if($row->input_date != $row->updated_at)
                        {{ optional($row->updated_at)->format('Y/n/j G:i') }}
                    @else
                        {{ optional($row->input_date)->format('Y/n/j G:i') }}
                    @endif
                </td>

                {{-- 反映日 --}}
                @if($mode !== 'support')
                <td>
                    {{ $row->reflected_at
                        ? \Carbon\Carbon::parse($row->reflected_at)->format('Y/n/j G:i') . '【反映者 : ' . $row->reflected_by . '】'
                        : '—' }}
                </td>
                @endif

                <td>{{ $row->attention }}</td>

                <td>{{ $row->remarks_txt }}</td>

                {{-- 操作 --}}
                <td>
                    @if($mode === 'support')

                        {{-- キャンセル一覧 --}}
                        <a href="{{ route('boarding_reservation.edit', ['id' => $row->id, 'mode' => 'support']) }}">
                            編集
                        </a>

                        <form action="{{ route('cancel.delete', $row->id) }}"
                              method="POST"
                              style="display:inline;"
                              onsubmit="return confirm('削除しますか？')">

                            @csrf
                            @method('DELETE')

                            <button type="submit" class="delete">削除</button>
                            <input type="hidden" name="mode" value="{{ $mode }}">
                        </form>

                    @else

                        {{-- 乗降予約一覧 --}}
                        <a href="{{ route('yoyaku.reflect', $row->id) }}">反映</a>

                        <a href="{{ route('boarding_reservation.edit', $row->id) }}">編集</a>

                        <form action="{{ route('yoyaku.delete', $row->id) }}"
                              method="POST"
                              onsubmit="return confirm('削除しますか？')">

                            @csrf
                            @method('DELETE')

                            <button type="submit" class="delete">
                                削除
                            </button>
                            <input type="hidden" name="mode" value="{{ $mode }}">

                        </form>

                    @endif
                </td>

                {{-- 反映 --}}
                @if($mode !== 'support')
                    <td>

                        @if((int)$row->is_reflected === 1)
                            <span class="hanei1">反映済</span>
                        @else
                            <span class="hanei2">未反映</span>
                        @endif


                    </td>
                @endif

            </tr>

            @php $count++; @endphp

        @endforeach

        </tbody>
    </table>
</div>

<div class="hit">
    @if($count > 0)
        [合計：{{ $count }}件]
    @endif
</div>

@if($count == 0)
    <div class="page404">
        <img src="{{ asset('image/404.webp') }}"><br>
        <p>該当する検索結果がありません</p>
    </div>

    <div class="prevPage no-print">
        <a href="{{ route('dashboard') }}" class="link">戻る</a>
    </div>
@endif
</div>

<x-recaptcha />

</body>
</html>