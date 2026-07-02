import { useEffect } from 'react';
import './Reminder.css';

export default function Reminder() {

  useEffect(() => {
    const imgs = document.querySelectorAll('.osirase img');
    const text = document.querySelector('.osirase b');

    // 画像のちょい揺れ
    const keyframes = [
      { transform: 'translateY(-5px)' },
      { transform: 'translateY(0px)' }
    ];

    const options = {
      duration: 150,
      iterations: 5
    };

    imgs.forEach(img => {
      img.animate(keyframes, options);
    });

    // 文字フェードイン（1回だけ）
    setTimeout(() => {
      if (!text) return;

      text.animate(
        [
          { opacity: 0},
          { opacity: 1}
        ],
        {
          duration: 1000,
          easing: 'ease',
          fill: 'forwards'
        }
      );

    }, 300);

  }, []);

  return (
    <div className="osirase">

      <b>
        お願いします
        <i>✨</i>
      </b>

      <img
        src="/image/wanko_haru8.png"
        alt=""
      />

      <span>
        業務終了時に更新ページから
        <br />
        終業距離を入力してください
      </span>

    </div>
  );
}