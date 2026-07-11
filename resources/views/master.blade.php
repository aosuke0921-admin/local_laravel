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

<style>
  .welcome{
    display:block;
    height:0;
    position:absolute;
    top:13px;
    left:150px;
  }
</style>
</head>
<body class="master-page">

  <div class="react" data-component="LogoTitle"></div>

    <p class="welcome">ようこそ、特定非営利活動法人スマイルハート {{ $members->user_login }} さん</p>

    @php
      $y = date('Y');
      $m = date('m');
    @endphp
 
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

<div class="react" data-component="GlobalNav"></div>

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

  <div id="overflow"><div class="conf"><img src="{{ asset('image/404.webp') }}" alt=""><p></p><button class="closeBtn">閉じる</button></div></div>
  
</body>
</html>