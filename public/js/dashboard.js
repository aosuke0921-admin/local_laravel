// jQuery + javaScript
//----------------------------------------------------------------------------------------
// URLのクエリ文字列を取得
const queryString = window.location.search;
// URLSearchParamsオブジェクトを作成してクエリ文字列を解析
const params = new URLSearchParams(queryString);

$(function(){

    // 数値（小数OK）しか入力できないようにする
    $('#start_distance').on('input', function() {
        // 入力された値から「数字(0-9)とドット(.)以外」をすべて削除する / 例: "abc123.4d" → "123.4"
        this.value = this.value.replace(/[^0-9.]/g, '');

        // ドット(.)を基準に分割（小数点チェックのため）
        const parts = this.value.split('.');

        // ドットが2つ以上ある場合（例: 12.3.4）
        if(parts.length > 2) {
            // 1つ目のドットだけ残して、それ以降はすべて結合して1つの小数にする / 例: ["12","3","4"] → "12.34"
            this.value = parts[0] + '.' + parts.slice(1).join('');
        }
    });
    //----------------------------------------------------------------------------------------
        let paramStartDistance = params.get('start_distance');

        // urlにパラメータがついていればパラメータ優先
        if (paramStartDistance !== null) {

            $('#start_distance').val(paramStartDistance);

        } else {

            // changeイベント登録
            //$('#ymd, #car').on('change', updateStartDistance);

            // 初回実行
            updateStartDistance();

        }
    //----------------------------------------------------------------------------------------
    // #carの変更時だけ発火
    $('#car, #ymd').on('change', updateStartDistance);

    // URLにパラメータがついていればパラメータ優先
    if (paramStartDistance !== null) {

        $('#start_distance').val(paramStartDistance);

    } else {

        // DBから取得
        updateStartDistance();

    }
    //----------------------------------------------------------------------------------------
    // 「2026年4月4日」のような日付文字列を「2026-04-04」のDB用フォーマットに変換する関数
    function formatDateToDB(dateStr) {

        // 正規表現で「年・月・日」の数字を抽出 / (\d+) → 数字を1つ以上取得 / 例: "2026年4月4日" → ["2026年4月4日", "2026", "4", "4"]
        const match = dateStr.match(/(\d+)年(\d+)月(\d+)日/);

        // マッチしなかった場合（形式が違う場合）は空文字を返す
        if (!match) return '';

        // 年を取得（配列の2番目）
        const year  = match[1];

        // 月を取得し、2桁にする（例: 4 → 04）
        const month = String(match[2]).padStart(2, '0');

        // 日を取得し、2桁にする（例: 4 → 04）
        const day   = String(match[3]).padStart(2, '0');

        // 「YYYY-MM-DD」の形式に組み立てて返す / 例: "2026-04-04"
        return `${year}-${month}-${day}`;
    }
    //----------------------------------------------------------------------------------------
    // 始業距離をDBから取得して、入力欄に自動反映する関数
    async function updateStartDistance() {

        const rawDate = document.querySelector('#ymd')?.value;
        const date = formatDateToDB(rawDate);
        const car = document.querySelector('#car')?.value;

        if (!date || !car) {
            document.querySelector('#start_distance').value = 0;
            return;
        }

        try {
            const res = await fetchStartDistance(date, car);
            document.querySelector('#start_distance').value = res.start_distance ?? 0;
        } catch (err) {
            console.error(err);
            document.querySelector('#start_distance').value = 0;
        }
    }
    //----------------------------------------------------------------------------------------
    // SP画面　＋ボタンで展開している数を保存してしてたのを削除(リロード後復元で使用)
    sessionStorage.removeItem('open_rows');
    //----------------------------------------------------------------------------------------
});