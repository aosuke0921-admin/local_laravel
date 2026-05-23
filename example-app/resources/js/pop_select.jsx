// React本体を読み込む
// JSXを書くために必要
import React from 'react';


// Reactをブラウザへ描画する機能
// createRoot() を使うため必要
import ReactDOM from 'react-dom/client';


// 自作した Reactコンポーネント を読み込む
// ./ は「同じ階層」を意味する
import PopSelect from './PopSelect';


// HTML側の id="pop_select" を取得
// Bladeの <div id="pop_select"></div>
const popSelect = document.getElementById('pop_select');


// もしHTML側に存在していたら実行
// 存在しないページでエラーになるのを防ぐ
if (popSelect) {

    // Reactを描画開始する場所を作る
    // popSelect の中へ React を入れる
    ReactDOM.createRoot(popSelect).render(

        // PopSelectコンポーネントを表示
        // HTMLタグっぽいけど React部品
        <PopSelect />

    );

}