// All javaScript
//----------------------------------------------------------------------------------------
class Calendar {
  // ===============================
  // 初期化処理
  // ===============================
  constructor() {

    // カレンダー本体のDOM取得
    this.calendar = document.querySelector('#calendar');

    // 今日の日付取得
    const now = new Date();

    // 現在の年と月を初期値にセット
    /*this.year = now.getFullYear();
    this.month = now.getMonth() + 1;*/

    const path = window.location.pathname;


    // ダッシュボード
    if (path === '/dashboard') {

      this.year = now.getFullYear();
      this.month = now.getMonth() + 1;
      this.day = now.getDate();

    }
    // その他のページ
    else {

      const value = document.querySelector('[name="dates"]').value;

      const [year, month, day] = value
        .split('-')
        .map(Number);

      this.year = year;
      this.month = month;
      this.day = day;
    }

    // ヘッダー生成（年・月UI部分）
    this.createHeader();

    // 初期化処理
    this.init();
  }

  // ===============================
  // 初期化まとめ
  // ===============================
  init() {

    // 各種イベント登録
    this.bindEvents();

    // カレンダー描画
    this.render();
  }

  // ===============================
  // ヘッダー生成（jQuery before の代替）
  // ===============================
  createHeader() {

    // ヘッダーDOM作成
    const header = document.createElement('div');
    header.className = 'wp';

    // 年・月のUIをHTMLで生成
    header.innerHTML = `
      <div class="yprev">◀</div>
      <div class="yy">${this.year}</div>
      <div class="ynext">▶</div>

      <div class="prev">◀</div>
      <div class="mm">${this.month}</div>
      <div class="next">▶</div>
    `;

    // #calendar の前にヘッダーを挿入
    this.calendar.before(header);
  }

  // ===============================
  // イベント登録処理
  // ===============================
  bindEvents() {

    // 年戻るボタン
    const yprev = document.querySelector('.yprev');
    if (yprev) {
      yprev.addEventListener('click', () => {
        this.year--; // 年を1減らす
        this.render(); // 再描画
      });
    }

    // 年進むボタン
    const ynext = document.querySelector('.ynext');
    if (ynext) {
      ynext.addEventListener('click', () => {
        this.year++; // 年を1増やす
        this.render();
      });
    }

    // 月戻るボタン
    const prev = document.querySelector('.prev');
    if (prev) {
      prev.addEventListener('click', () => {
        this.month--; // 月を減らす

        // 0月になったら前年12月へ
        if (this.month === 0) {
          this.month = 12;
          this.year--;
        }

        this.render();
      });
    }

    // 月進むボタン
    const next = document.querySelector('.next');
    if (next) {
      next.addEventListener('click', () => {
        this.month++; // 月を増やす

        // 13月になったら翌年1月へ
        if (this.month === 13) {
          this.month = 1;
          this.year++;
        }

        this.render();
      });
    }

    // カレンダー表示トグル（入力クリックで表示）
    const ymd = document.querySelector('.ymd');
    if (ymd) {
      ymd.addEventListener('click', () => {
        document.querySelector('.cl_toggle').style.display = 'block';
      });

      //---------------------------------------------------------------------------
      // ★ここに追加
      ymd.addEventListener('change', (e) => {

        const date = e.target.value.replace(
          /(\d+)年(\d+)月(\d+)日/,
          (_, y, m, d) =>
            `${y}-${m.padStart(2,'0')}-${d.padStart(2,'0')}`
        );

        this.selectedDate = date;

        //this(this.selectedDate);

        this.render();
      });
      //---------------------------------------------------------------------------
    }

    // ===============================
    // 日付クリック（イベント委譲）
    // ===============================
    this.calendar.addEventListener('click', (e) => {

      this.render();

      // td以外は無視
      if (e.target.tagName !== 'TD') return;

      const day = e.target.textContent;

      // 空 or 数字以外は無視
      if (!day || isNaN(day)) return;

      // カレンダーを閉じる関数
      const closeCalendar = () => {
        document.querySelector('.cl_toggle').style.display = 'none';
      };

      // ===============================
      // 更新ページ
      // ===============================
      const dateInput = document.querySelector('.date_input');

      if (dateInput) {

        document.querySelector('#year').textContent = this.year;
        document.querySelector('#month').textContent = this.month;
        document.querySelector('#day').textContent = day;

        dateInput.value =
          `${this.year}-${String(this.month).padStart(2, '0')}-${String(day).padStart(2, '0')}`;

        closeCalendar();

        dateInput.form.submit();
        return;
      }

      // ===============================
      // ダッシュボード
      // ===============================
      const ymd = document.querySelector('.ymd');

      if (ymd) {
        ymd.value = `${this.year}年${this.month}月${day}日`;
        ymd.dispatchEvent(new Event('change'));
      }

      closeCalendar();
    });
  }
  
  // ===============================
  // 再描画処理
  // ===============================
  render() {

    // カレンダー再生成（これが本体）
    this.calendar.innerHTML = this.createCalendar(this.year, this.month);

    // ヘッダー更新
    this.updateHeader();

    // ハイライト
    this.toDay();
  }

  // ===============================
  // ヘッダー更新処理
  // ===============================
  updateHeader() {

    const yy = document.querySelector('.yy');
    const mm = document.querySelector('.mm');

    if (yy) yy.textContent = this.year;
    if (mm) mm.textContent = this.month;
  }
  /*
  clearHighlight() {
    this.calendar.querySelectorAll('td').forEach(td => {
      td.style.backgroundColor = '';
    });
  }
  */
  // ===============================
  // カレンダー生成処理
  // ===============================
  createCalendar(year, month) {

    const weeks = ['日', '月', '火', '水', '木', '金', '土'];

    // 月初日
    const start = new Date(year, month - 1, 1);

    // 月末日
    const end = new Date(year, month, 0);

    const startDay = start.getDay(); // 曜日
    const lastDate = end.getDate();  // 日数

    // 行数計算
    const lines = this.calendarLine(startDay, lastDate);

    let html = "<table>";

    // 曜日行
    html += "<tr>";
    for (let w = 0; w < 7; w++) {
      html += `<td>${weeks[w]}</td>`;
    }
    html += "</tr>";

    let date = 1;

    // カレンダー本体生成
    for (let i = 0; i < lines; i++) {
      html += "<tr>";

      for (let j = 0; j < 7; j++) {

        // 前月空白
        if (i === 0 && j < startDay) {
          html += "<td></td>";
        }

        // 翌月空白
        else if (date > lastDate) {
          html += "<td></td>";
        }

        // 当月日付
        else {
          html += `<td>${date}</td>`;
          date++;
        }
      }

      html += "</tr>";
    }

    return html;
  }

  // ===============================
  // カレンダー行数計算
  // ===============================
  calendarLine(startDay, lastDate) {

    if (startDay + lastDate >= 36) return 6;
    if (startDay + lastDate <= 28) return 4;
    return 5;
  }

  // ===============================
  // 今日の日付ハイライト
  // ===============================
  toDay() {

    const path = window.location.pathname;

    if (path === '/dashboard') {

      const cells = document.querySelectorAll('#calendar table td');

      const targetDate =
        this.selectedDate ??
        `${this.year}-${String(this.month).padStart(2,'0')}-${String(this.day).padStart(2,'0')}`;

      cells.forEach(td => {

        const val = Number(td.textContent);
        if (!val) return;

        const cellDate = `${this.year}-${String(this.month).padStart(2,'0')}-${String(val).padStart(2,'0')}`;

        if (targetDate === cellDate) {
          td.style.backgroundColor = '#F2B46B';
        }
      });

    } else {

      const value = document.querySelector('[name="dates"]').value;

      const [y, m, d] = value
        .split('-')
        .map(Number);

      const cells = document.querySelectorAll('#calendar table td');

      cells.forEach(td => {

        const val = Number(td.textContent);

        if (
          this.year === y &&
          this.month === m &&
          val === d
        ) {
          td.style.backgroundColor = '#F2B46B';
        }
      });

    }
  }
}

// ===============================
// 実行（DOM読み込み後）
// ===============================
document.addEventListener('DOMContentLoaded', () => {
  new Calendar('#calendar');
});