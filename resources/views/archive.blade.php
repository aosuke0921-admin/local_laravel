<?php // 92点 ?>
<!doctype html>
<html>
<head>
<meta charset="utf-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0, minimum-scale=1.0">
<title>検索</title>
<script type="text/javascript" charset="UTF-8"></script>
<script src="https://code.jquery.com/jquery-3.6.0.min.js" integrity="sha256-/xUj+3OJU5yExlq6GSYGSHk7tPXikynS7ogEvDej/m4=" crossorigin="anonymous"></script>
<!--<script src="https://cdnjs.cloudflare.com/ajax/libs/jquery-csv/1.0.21/jquery.csv.min.js"></script>-->
{{-- ---------------------------------------------------------------------------------------- --}}
<script src="{{ asset('js/sessionMonitor.js') }}?v={{ time() }}" charset="utf-8"></script>
{{-- ---------------------------------------------------------------------------------------- --}}
<!-- ajax_config.js master.js の $.ajax 使用箇所が残っているため masterのみ使用 fetch化後に削除予定-->
<!--<script src="{{ asset('js/ajax_config.js') }}?v={{ time() }}" charset="utf-8"></script>-->
{{-- ---------------------------------------------------------------------------------------- --}}
<script src="{{ asset('js/system_init.js') }}?v={{ time() }}" charset="utf-8"></script><!-- 使用中 -->
{{-- ---------------------------------------------------------------------------------------- --}}
<script src="{{ asset('js/closeWindow.js') }}?v={{ time() }}" charset="utf-8"></script>
<script src="{{ asset('js/openWindow.js') }}?v={{ time() }}" charset="utf-8"></script>
<script src="{{ asset('js/highlightRows.js') }}?v={{ time() }}" charset="utf-8"></script>
{{-- ---------------------------------------------------------------------------------------- --}}
<link href="{{ asset('css/style.css') }}?v={{ time() }}" rel="stylesheet" type="text/css">

@viteReactRefresh

@vite('resources/react/index.tsx')

</head>
<body class="archive-page">

<div class="react" data-component="LogoTitle"></div>

    @include('components.user-group-list', [
        'groupedUsers' => $groupedUsers
    ])
    
<div id="wrapper">
<div class="user_information no-print">
  <form name="form" action="" method="get"><?php //←GET ?>


    <div class="sarch_t">
      利用者

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

    &nbsp;&nbsp;乗降者
    <select class="select member_select" name="member_name_select">
        <option value="">選択してください</option>
        @foreach($members as $member)
            <option value="{{ $member }}"
                {{ ($selectedMember ?? '') == $member ? 'selected' : '' }}>
                {{ $member }}
            </option>
        @endforeach
    </select>

      &nbsp;&nbsp;年度
      <select class="select month_select" name="year_select">
          <option value="">選択してください</option>
          @for($y = date('Y'); $y >= 2025; $y--)
              <option value="{{ $y }}年" {{ ($selectedYear ?? date('Y').'年') == $y.'年' ? 'selected' : '' }}>
                  {{ $y }}年
              </option>
          @endfor
      </select>

      &nbsp;&nbsp;月度
      <select class="select month_select" name="month_select">
          <option value="">選択してください</option>
          @for($m = 1; $m <= 12; $m++)
              <option value="{{ $m }}月" {{ ($selectedMonth ?? date('n').'月') == $m.'月' ? 'selected' : '' }}>
                  {{ $m }}月
              </option>
          @endfor
      </select>
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

<div class="react" data-component="GlobalNav"></div>

@if(!$posts->isEmpty())
<form name="form" action="#" method="post" id="form" autocomplete="on" enctype="multipart/form-data">
<table class="tb tb0">
    <tbody>
        <tr>
            <th>運行日・乗務者</th>
            <th>利用者</th>
            <th>発時刻</th>
            <th>着時刻</th>
            <th>行き/帰り</th>
            <th>行先</th>
            <th>任意行先</th>
            <th>乗合</th>
            <th>区分</th>
            <th>備考欄</th>
            <th>距離</th>
            <th>料金</th>
        </tr>
    @forelse($posts as $post)
        <tr>
            <td class="bg_color4">

            <span>
                {{ \Carbon\Carbon::parse($post->dates)->format('n月j日') }}
                　( {{ $post->member }} )
            </span>

            </td>
            <td><span>{{ $post->user }}</span></td>
            <td><span>{{ $post->departureTime }}</span></td>
            <td><span>{{ $post->arrivalTime }}</span></td>
            <td><span>{{ $post->goingBack }}</span></td>
            <td><span>{{ $post->destination }}</span></td>

            <td><span>{{ $post->remarks ?? '-' }}</span></td>

            <td><span>{{ $post->shareRide == 1 ? '乗合' : '-' }}</span></td>

            <td><span>{{ $post->classification ?? '-' }}</span></td>
            <td><span>{{ $post->any ?? '-' }}</span></td>

            <td class="check-error"><span>{{ $post->distance }}</span></td>
            <td class="check-error"><span>{{ $post->price }}</span></td>

            <!--0 Nullでエラー表示確認デバッグ用 
            <td class="check-error"><span></span></td>
            <td class="check-error"><span>0</span></td>
            -->
        </tr>
    @empty

        <tr>
            <td colspan="12">データがありません</td>
        </tr>

    @endforelse
    </tbody>
</table>
</form>
<div class="hit">
  [合計：{{ $posts->count() }}件]
</div>


@else

<div class="page404">
  <img src="./image/404.webp" alt="404"><br>
  該当する検索結果がありません
</div>

@endif

  <div class="prevPage no-print">
    @if(!$posts->isEmpty())
        <!-- 検索結果表示後にだけ出す -->
        <!-- @csrf  ←GETでダウンロードするだけのフォームなのでいらん -->
        <form action="{{ route('archive.downloadCsv') }}" method="get">
            <input type="hidden" name="user_name_select" value="{{ $selectedUser }}">
            <input type="hidden" name="member_name_select" value="{{ $selectedMember }}">
            <input type="hidden" name="year_select" value="{{ $selectedYear }}">
            <input type="hidden" name="month_select" value="{{ $selectedMonth }}">
            <button type="submit" name="submit" value="CSVダウンロード" >CSVダウンロード</button>
        </form>
    @endif
    <a href="./dashboard">戻る</a>
  </div>
</div>
<div id="overflow"><div class="conf"><img src="./image/404.webp"><p></p><button class="closeBtn">閉じる</button></div></div>

<x-recaptcha />

</body>
</html>