<?php //88点 ?>
<!doctype html>
<html>
<head>
<meta charset="utf-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0, minimum-scale=1.0">
<title>運航日報・印刷</title>

<script type="text/javascript" charset="UTF-8"></script>
<script src="https://code.jquery.com/jquery-3.6.0.min.js" integrity="sha256-/xUj+3OJU5yExlq6GSYGSHk7tPXikynS7ogEvDej/m4=" crossorigin="anonymous"></script>

{{-- ---------------------------------------------------------------------------------------- --}}

<script src="{{ asset('js/sessionMonitor.js') }}?v={{ time() }}" charset="utf-8"></script>

{{-- ---------------------------------------------------------------------------------------- --}}

<link rel="stylesheet" href="{{ asset('css/style.css') }}?v={{ filemtime(public_path('css/style.css')) }}">
<link rel="stylesheet" href="{{ asset('css/print.css') }}?v={{ filemtime(public_path('css/print.css')) }}" media="print">
</head>
<body class="print-page">
<div class="pc">
    <div class="user_information no-print">

        @php
        $post = $posts->first();
        @endphp

        <ul>
          <li><dl><dt>乗務者</dt><dd><span>{{ session('user_name') }}</span></dd></dl></li>
          <li><dl><dt>日付</dt><dd><span>{{ $displayDate }}</span></dd></dl></li>
          <li><dl><dt>車種</dt><dd><span>{{ $post->car }}</span></dd></dl></li>
          <li><dl><dt>始業距離</dt><dd><span>{{ $post->start_distance }}</span></dd></dl></li>
        </ul>
        
        <!--Laravelはセキュリティ（CSRF対策）のためにログアウトを POSTで行う-->
        <form method="POST" action="{{ route('logout') }}">
            @csrf
            <input type="hidden" name="recaptcha_token" class="recaptcha_token">




            <button type="submit">ログアウト</button>
        </form>
        <a href="{{ route('dashboard') }}">TOPへ</a>
    </div>

  @php
  $chunks = $posts->chunk(14);
  @endphp

  @foreach($chunks as $chunkIndex => $chunk)

  <div class="print_content print-area print-area1">

      {{-- ヘッダー --}}
      <h1>乗務記録（運行日報）</h1>

      <div class="head">
          <div class="l">
              {{ $post->dates }}
          </div>
          <div class="c">
              {{ $post->car }}
          </div>
          <div class="r">
              <dl>
                  <dt><img src="{{ asset('image/logomk.gif') }}"></dt>
                  <dd>
                      <ul>
                          <li>特定非営利活動法人</li>
                          <li>スマイルハート</li>
                      </ul>
                  </dd>
              </dl>
          </div>
      </div>

      {{-- 上部情報 --}}
      <table class="print_tb">
          <tbody>
              <tr>
                  <td>＼</td>
                  <td class="position">
                      <span class="left">始業距離</span>
                      <span class="alignRight">{{ $start_distance }}</span>
                      <span class="right">km</span>
                  </td>
                  <td>
                      <span class="left">開始時刻</span>
                      <span class="alignRight">{{ $start_time }}</span>
                  </td>
                  <td class="position">
                      <span class="left">終業距離</span>
                      <span class="alignRight">{{ $end_distance }}</span>
                      <span class="right">km</span>
                  </td>
                  <td>
                      <span class="left">終了時刻</span>
                      <span class="alignRight">{{ $end_time }}</span>
                  </td>
                  <td colspan="2">
                      <span class="left">運転者名</span>
                      <span class="alignRight2">{{ $driver_name }}</span>
                  </td>
                  <td><span class="left">運行管理者</span></td>
                  <td>
                      <span class="left">合計距離</span>
                      <span class="val1">{{ $total_distance }}</span>
                      <span class="right">km</span>
                  </td>
                  <td>
                      <span class="left">合計料金</span>
                      <span class="val2">{{ $total_price }}</span>
                      <span class="right">円</span>
                  </td>
                  <td>
                      <span class="left">合計乗車</span>
                      <span class="val3">{{ $total_rides }}</span>
                      <span class="right">人</span>
                  </td>
              </tr>

              {{-- カラム --}}
              <tr class="tr_2">
                  <td>回数</td>
                  <td>利用者</td>
                  <td>始発時刻</td>
                  <td>行先</td>
                  <td>到着時間</td>
                  <td>行き/帰り</td>
                  <td>乗合</td>
                  <td>支援</td>
                  <td>走行距離</td>
                  <td>収受料金</td>
                  <td>乗車人数</td>
              </tr>

              {{-- データ --}}
              @foreach($chunk as $post)
              <tr>
                  <td>{{ $loop->iteration + ($chunkIndex * 14) }}</td>
                  <td>{{ $post->user }}</td>
                  <td>{{ $post->departureTime }}</td>
                  <td>{{ $post->destination }}</td>
                  <td>{{ $post->arrivalTime }}</td>
                  <td>{{ $post->goingBack }}</td>
                  <td>{{ $post->shareRide ? 'あり' : '-' }}</td>
                  <td>{{ $post->classification }}</td>
                  <td>
                      {{ $post->distance }}<span class="right">km</span>
                  </td>
                  <td>
                      {{ $post->price }}<span class="right">円</span>
                  </td>
                  <td>
                      1<span class="right">人</span>
                  </td>
              </tr>
              @endforeach

          </tbody>
      </table>

      {{-- フッター --}}
    <div class="bottom">
      <div class="item">
        <dl>
          <dt><b>備考</b></dt>
          <dd><span>＊1印の点検は、走行距離・運行時の状態等から判断した適切な時期に行うことで足りる</span></dd>
        </dl>
      </div>
      <div class="item">
        <dl>
          <dt><span>ブレーキ</span></dt>
          <dd>
            <ul>
              <li>踏みしろ・効き具合<span>☑</span></li>
              <li>駐車ブレーキ引きしろ<span>☑</span></li>
              <li>ブレーキオイル液量<span>☑</span></li>
              <li>ブレーキ音<span>☑</span></li>
            </ul>
          </dd>
        </dl>
      </div>
      <div class="item">
        <dl>
          <dt><span>タイヤ</span></dt>
          <dd>
            <ul>
              <li>空気圧<span>☑</span></li>
              <li>亀裂及び損傷<span>☑</span></li>
              <li>異常摩擦<span>☑</span></li>
              <li>溝の深さ<span>☑</span></li>
              <li>＊1ディスクホイール状態<span>☑</span></li>
            </ul>
          </dd>
        </dl>
      </div>
      <div class="item">
        <dl>
          <dt><span>原動機</span></dt>
          <dd>
            <ul>
              <li>＊1冷却水の量<span>☑</span></li>
              <li>＊1ファンベルト張り具合・損傷<span>☑</span></li>
              <li>＊1エンジンオイルの量・汚れ<span>☑</span></li>
              <li>＊1かかり具合・異音<span>☑</span></li>
              <li>＊1低速・加速の状態<span>☑</span></li>
            </ul>
          </dd>
        </dl>
      </div>
      <div class="item">
        <dl>
          <dt><span>その他</span></dt>
          <dd>
            <ul>
              <li>＊1バッテリー液量<span>☑</span></li>
              <li>灯火装置・方向指示器<span>☑</span></li>
              <li>＊ウォッシャーワイパー<span>☑</span></li>
              <li>チャート紙装着<span>☑</span></li>
              <li>異常信号用具<span>☑</span></li>
            </ul>
          </dd>
        </dl>
      </div>
      <div class="item">
        <dl>
          <dd>
            <ul>
              <li>停止表示板<span>☑</span></li>
              <li>車検証・保険証整備記録薄携帯<span>☑</span></li>
              <li>工具・スペアタイヤの定位置固定<span>☑</span></li>
            </ul>
          </dd>
        </dl>
      </div>
    </div>
  </div>

  {{-- 改ページ / 最後じゃないときだけ / 印刷の区切り --}}
  @if(!$loop->last)
  <div style="page-break-after: always;"></div>
  @endif

  @endforeach

  <div class="prevPage no-print">
        <a onclick="window.print(); return false;" class="no-print">印刷</a>&nbsp;&nbsp;&nbsp;
        <a href="{{ route('dashboard') }}" class="no-print">戻る</a>
  </div>
  <div id="overflow">
    <div class="conf">
      <img src="{{ asset('image/404.webp') }}" alt="404"><p></p><button class="closeBtn">閉じる</button>
    </div>
  </div>
</div>
<div class="sp">
  <div class="page404">
      <img src="{{ asset('image/404.webp') }}" alt="404"><br>
      NO Responsive
      <div class="prevPage sp"><a href="{{ route('dashboard') }}">戻る</a></div>
  </div>
</div>

<x-recaptcha />

</body>
</html>