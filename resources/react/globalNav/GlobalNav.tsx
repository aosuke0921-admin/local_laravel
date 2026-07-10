import './GlobalNav.css';

export default function GlobalNav() {
  return (
    <nav aria-label="グローバルナビゲーション" className="gnav">
      <ul>
        <li><a href="/post">登録</a></li>
        <li><a href="/preview">更新</a></li>
        <li><a href="/delete">削除</a></li>
        <li><a href="/archive">検索</a></li>
        <li><a href="/month-archive">月報</a></li>
        <li><a href="/user_registration">利用者・登録</a></li>
        <li><a href="/destination_registration">行き先・登録</a></li>
        <li><a href="/user_destination_registration">利用者・行き先・登録</a></li>
        <li><a href="/boarding_reservation">乗降予約</a></li>
        <li><a href="/reservation_search">乗降予約一覧</a></li>
        <li><a href="/pop_select">キャンセル受付</a></li>
        <li><a href="/reservation_search?mode=support">キャンセル受付一覧</a></li>
      </ul>
    </nav>
  );
}