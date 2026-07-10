<!doctype html>
<html>
<head>
<meta charset="utf-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0, minimum-scale=1.0">
<title>マスター保守</title>
<script type="text/javascript" charset="UTF-8"></script>
<script src="https://code.jquery.com/jquery-3.6.0.min.js" integrity="sha256-/xUj+3OJU5yExlq6GSYGSHk7tPXikynS7ogEvDej/m4=" crossorigin="anonymous"></script>
<script src="https://logute.com/js/plugin/DeleteCheck/DeleteCheck.js?v=1778639541" charset="utf-8"></script>
<link href="{{ asset('css/style.css') }}" rel="stylesheet" type="text/css">
<link href="{{ asset('css/master.css') }}" rel="stylesheet" type="text/css">
{{-- ---------------------------------------------------------------------------------------- --}}
<script src="{{ asset('js/sessionMonitor.js') }}?v={{ time() }}" charset="utf-8"></script>
<script src="{{ asset('js/system.js') }}?v={{ time() }}" charset="utf-8"></script>
<script src="{{ asset('js/page-feedback.js') }}?v={{ time() }}" charset="utf-8"></script>
<script src="{{ asset('js/master.js') }}?id={{ time() }}" charset="utf-8"></script>
{{-- ---------------------------------------------------------------------------------------- --}}

@viteReactRefresh

@vite('resources/react/index.tsx')

</head>
<body class="master-page">
  <div class="gnav">

    @php
      $y = date('Y');
      $m = date('m');
    @endphp

    <p>ようこそ、特定非営利活動法人スマイルハート さん</p>
  <ul>
    <li><a href="{{ route('dashboard') }}"><i>●</i>TOP</a></li>
    <li><a href="{{ route('archive.page') }}"><i>●</i>検索</a></li>
    <li><a href="{{ route('month_archive.page') }}"><i>●</i>月報</a></li>
    <li><a href="{{ route('user_registration.page') }}"><i>●</i>利用者・登録</a></li>
    <li><a href="{{ route('destination_registration.page') }}"><i>●</i>行き先・登録</a></li>
    <li><a href="{{ route('user_destination_registration.page') }}"><i>●</i>利用者・行き先・登録</a></li>
    <li><a href="{{ route('boarding_reservation.page') }}"><i>●</i>乗降予約</a></li>
    <li>
      <a href="{{ route('reservation_search.page', [
        'year_select' => $y . '年',
        'month_select' => $m . '月',
        'user_select' => '選択してください',
        'sien_select' => '選択してください'
      ]) }}">
        <i>●</i>乗降一覧検索
      </a>
    </li>
    <li><a href="{{ route('boarding_reservation.page', ['cancel' => true]) }}"><i>●</i>キャンセル登録</a></li>
    <li>
      <a href="{{ route('reservation_search.page', [
        'cancel' => true,
        'year_select' => $y . '年',
        'month_select' => $m . '月',
        'user_select' => '選択してください',
        'sien_select' => '選択してください'
      ]) }}">
        <i>●</i>キャンセル一覧検索
      </a>
    </li>
    <li>
      <form method="POST" action="{{ route('logout') }}">
          @csrf

          <button type="submit">
              ログアウト
          </button>
      </form>
    </li>
  </ul>
  </div>
  
  {{-- 社員登録 --}}
  <div class="user_admin">
    <div class="new_addition">

      <form id="new_addition_form"
      action="{{ route('master.store') }}"
      method="POST"
      autocomplete="on"
      novalidate>
      @csrf

        <div class="react UserAddFields" data-component="UserAddFields"></div>
        <input type="submit" class="submit" value="新規追加">

      </form>

    </div>

    <table>

      @include('master_parts.employee_th')

      @foreach($member as $val)

      {{-- 更新フォーム --}}
      <form method="POST" action="{{ route('member.update', $val->id) }}">
        @csrf
        @method('PUT')
        <tr>

            <td>{{ $val->id }}</td>

            <td>
              <input type="text" name="full_name" value="{{ $val->full_name }}">
            </td>

            <td>
              <input type="text" name="user_login" value="{{ $val->user_login }}">
            </td>

            <td>
              <input type="password" name="password" placeholder="変更時のみ入力">
            </td>

            <td class="td_last">
              <button type="submit">更新</button>
              </form>

              {{-- 削除フォーム --}}
              <form method="POST" action="{{ route('member.delete', $val->id) }}" style="display:inline;">
                @csrf
                @method('DELETE')

                <button type="submit" class="delete_btn">削除</button>
              </form>
            </td>

        </tr>
      @endforeach
    </table>

    <div class="react" data-component="ModeChange"></div>

    <div class="react sticky" data-component="InitialTabs"></div>

    <table>

      <thead class="table_head">

          @include('master_parts.user_destination_th')

      </thead>

      <tbody class="table_area">

        @include('master_parts.user_destination_rows', [
            'records' => $user_destination_records
        ])
        
      </tbody>

    </table>

  </div>

  <footer>
    <a href="{{ route('dashboard') }}">戻る</a>

    <div class="react" data-component="PageTop"></div>

  </footer>

</body>
</html>