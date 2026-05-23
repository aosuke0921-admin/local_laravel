<div class="open_window">

            @php
            $groupClassMap = [
                'あ'=>'a','い'=>'a','う'=>'a','え'=>'a','お'=>'a',
                'か'=>'ka','き'=>'ka','く'=>'ka','け'=>'ka','こ'=>'ka',
                'さ'=>'sa','し'=>'sa','す'=>'sa','せ'=>'sa','そ'=>'sa',
                'た'=>'ta','ち'=>'ta','つ'=>'ta','て'=>'ta','と'=>'ta',
                'な'=>'na','に'=>'na','ぬ'=>'na','ね'=>'na','の'=>'na',
                'は'=>'ha','ひ'=>'ha',
                'ふ'=>'ha','へ'=>'ha','ほ'=>'ha',
                'ま'=>'ma','み'=>'ma','む'=>'ma','め'=>'ma','も'=>'ma',
                'や'=>'ya','ゆ'=>'ya','よ'=>'ya',
                'ら'=>'ra','り'=>'ra','る'=>'ra','れ'=>'ra','ろ'=>'ra',
                'わ'=>'other','を'=>'other','ん'=>'other',
            ];

            // 五十音の固定順（ここが重要）
            $allKeys = [
                'あ','い','う','え','お',
                'か','き','く','け','こ',
                'さ','し','す','せ','そ',
                'た','ち','つ','て','と',
                'な','に','ぬ','ね','の',
                'は','ひ','ふ','へ','ほ',
                'ま','み','む','め','も',
                'や','ゆ','よ',
                'ら','り','る','れ','ろ',
                'わ','を',
            ];

            // グルーピング済みデータ
            $grouped = $groupedUsers->map(function ($list, $initial) {
                return $list;
            });
            @endphp

@foreach($allKeys as $key)

                @php
                    $cls = $groupClassMap[$key] ?? 'other';
                    $list = $grouped[$key] ?? collect();
                @endphp

                <ul class="open_1 {{ $cls }}">
                    <li class="cap">{{ $key }}</li>

                    @forelse($list as $item)
                        <li data-type="user" data-user="{{ $item->name }}">
                            {{ $item->name }}
                        </li>
                    @empty
                        {{-- 空でも枠だけ出す（必要なければ削除OK） --}}
                        <li class="empty">—</li>
                    @endforelse

                </ul>

 @endforeach

@if (request()->is('user_destination_registration'))


            {{-- 行き先 --}}
            @foreach($allKeys as $key)

                @php
                    $cls = $groupClassMap[$key] ?? 'other';
                    $list = $groupedDestinations[$key] ?? collect();
                @endphp

                <ul class="open_2 {{ $cls }}">
                    <li class="cap">{{ $key }}</li>

                    @forelse($list as $item)
                        <li data-type="destination">
                            {{ $item->destination }}
                        </li>
                    @empty
                        <li class="empty">—</li>
                    @endforelse
                </ul>

            @endforeach
@endif


    <div class="close_btn">✕</div>

</div>