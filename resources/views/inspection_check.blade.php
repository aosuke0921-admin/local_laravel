<?php //96点 ?>
<!doctype html>
<html>
<head>
<meta charset="utf-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0, minimum-scale=1.0">
<title>始業開始点検</title>
<script src="https://code.jquery.com/jquery-3.6.1.min.js"></script>
<script src="{{ asset('js/system.js') }}?id={{ time() }}" charset="utf-8"></script>
<link href="{{ asset('css/style.css') }}?id={{ time() }}" rel="stylesheet" type="text/css">
</head>
<body class="inspection_check">
    <div class="inspection">
      <div class="inner">
        <form action="{{ url('/inspection_check') }}?error=no_check" method="POST">
        @csrf
          <table>
            <tr>
              <td>
                  <div class="check_list">
                    <div class="check_item">
                     <div class="item">
                        <dl>
                          <dt>運転者 / 点検日 / 時刻 / 天候 / 車種</dt>
                          <dd>
                            <ul>
                              <li>
                                <b>{{ $user_login }} / {{ $displayDate }} / {{ now()->format('G時i分s秒') }} / {{ $weather }} / {{ $selectedCar }}</b>
                              </li>
                            </ul>
                          </dd>
                        </dl>
                        <dl>
                          <dt><span>ブレーキ</span></dt>
                          <dd>
                            <ul>
                              <li><label for="c1">踏みしろ・効き具合</label><input type="checkbox" id="c1" name="checks[]" value="c1"></li>
                              <li><label for="c2">駐車ブレーキ引きしろ</label><input type="checkbox" id="c2" name="checks[]" value="c2"></li>
                              <li><label for="c3">ブレーキオイル液量</label><input type="checkbox" id="c3" name="checks[]" value="c3"></li>
                              <li><label for="c4">ブレーキ音</label><input type="checkbox" id="c4" name="checks[]" value="c4"></li>
                            </ul>
                          </dd>
                        </dl>
                      </div>
                      <div class="item">
                        <dl>
                          <dt><span>タイヤ</span></dt>
                          <dd>
                            <ul>
                                <li><label for="c5">空気圧</label><input type="checkbox" id="c5" name="checks[]" value="c5"></li>
                                <li><label for="c6">亀裂及び損傷</label><input type="checkbox" id="c6" name="checks[]" value="c6"></li>
                                <li><label for="c7">異常摩擦</label><input type="checkbox" id="c7" name="checks[]" value="c7"></li>
                                <li><label for="c8">溝の深さ</label><input type="checkbox" id="c8" name="checks[]" value="c8"></li>

                                @if($isFirstCheckThisMonth ?? false)

                                  <li><label for="c9"><span>*</span>1ディスクホイール状態</label><input type="checkbox" id="c9" name="checks[]" value="c9"></li>

                                @endif

                            </ul>
                          </dd>
                        </dl>
                      </div>
                 
                    @if($isFirstCheckThisMonth ?? false)
                        <div class="item">
                          <dl>
                            <dt><span>原動機</span></dt>
                            <dd>
                              <ul>
                                <li><label for="c10"><span>*</span>1冷却水の量</label><input type="checkbox" id="c10" name="checks[]" value="c10"></li>
                                <li><label for="c11"><span>*</span>1ファンベルト張り具合・損傷</label><input type="checkbox" id="c11" name="checks[]" value="c11"></li>
                                <li><label for="c12"><span>*</span>1エンジンオイルの量・汚れ</label><input type="checkbox" id="c12" name="checks[]" value="c12"></li>
                                <li><label for="c13"><span>*</span>1かかり具合・異音</label><input type="checkbox" id="c13" name="checks[]" value="c13"></li>
                                <li><label for="c14"><span>*</span>1低速・加速の状態</label><input type="checkbox" id="c14" name="checks[]" value="c14"></li>
                              </ul>
                            </dd>
                          </dl>
                        </div>
                    @endif
     
                      <div class="item">
                        <dl>
                          <dt><span>その他</span></dt>
                          <dd>
                            <ul>
                                <!-- 右の配列に左の値がなければtrue -->
                                @if($isFirstCheckThisMonth ?? false)
                                <!-- 今月まだ誰も点検していない車種 -->
                                    <li>
                                        <label><span>*</span>バッテリー液量</label>
                                        <input type="checkbox" id="c15" name="checks[]" value="c15">
                                    </li>
                                @endif

                                <li><label for="c16">灯火装置・方向指示器</label><input type="checkbox" id="c16" name="checks[]" value="c16"></li>
                            
                                @if($isFirstCheckThisMonth ?? false)

                                    <li><label for="c17"><span>*</span>ウォッシャーワイパー</label><input type="checkbox" id="c17" name="checks[]" value="c17"></li>

                                @endif
                           
                              <li><label for="c18">異常信号用具</label><input type="checkbox" id="c18" name="checks[]" value="c18"></li>
                            </ul>
                          </dd>
                        </dl>
                      </div>
                      <div class="item">
                        <dl>
                          <dd>
                            <ul>
                              <li><label for="c19">停止表示板</label><input type="checkbox" id="c19" name="checks[]" value="c19"></li>
                              <li><label for="c20">車検証・保険証整備記録薄携帯</label><input type="checkbox" id="c20" name="checks[]" value="c20"></li>
                              <li><label for="c21">工具・スペアタイヤの定位置固定</label><input type="checkbox" id="c21" name="checks[]" value="c21"></li>
                            </ul>
                          </dd>
                        </dl>
                      </div>
                      <div class="item">
                        <dl>
                          <dt><span>点呼方法</span></dt>
                          <dd>
                            <input type="radio" id="label_tel" name="roll_call" value="電話" checked>
                            <label for="label_tel">電話</label>&nbsp;&nbsp;
                            <input type="radio" id="label_face_to_face" name="roll_call" value="対面">
                            <label for="label_face_to_face">対面</label>
                          </dd>
                        </dl>
                      </div>
                    </div>
                  </div>
              </td>
            </tr>
          </table>
          <div class="button">

            <div class="flex-item"><a href="{{ route('dashboard', [
                'dates' => session('dates'),
                'car' => session('car'),
                'start_distance' => session('start_distance')
            ]) }}">戻る</a></div>

            <?php /*<div class="flex-item"><input type="button" value="戻る" onclick="location.href='{{ url('/dashboard') }}';"></div>*/ ?>
          
            <div class="flex-item"><input type="submit" name="submitText1" value="次へ"></div>
          </div>
        </form>
      </div>
    </div>
<div id="overflow"><div class="conf"><img src="{{ asset('image/404.webp') }}" alt=""><p></p><button class="closeBtn">閉じる</button></div></div>

<x-recaptcha />

</body>
</html>