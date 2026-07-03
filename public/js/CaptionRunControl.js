// All javaScript
//----------------------------------------------------------------------------------------
window.addEventListener('load', function () {

    const deleteRuns = document.querySelectorAll('span.delete_run');

//----------------------------------------------------------------------------------------------------
    function updateRunNumbers() {

        document.querySelectorAll('caption.input_area_c').forEach((caption, index) => {

            const title = caption.querySelector('.run_title');

            if (title) {
                title.textContent = '運行' + (index + 1);
            }
        });
    }
//----------------------------------------------------------------------------------------------------
    document.querySelectorAll('span.toggle_run').forEach(el => {

        el.addEventListener('click', function () {

            const caption = this.closest('caption.input_area_c');
            const tbody = caption?.nextElementSibling;
            
            if (!tbody) return;

            // ★実際の表示状態で判定（初期ズレ対策）
            const isHidden = window.getComputedStyle(tbody).display === 'none';

            if (isHidden) {
                // 開く
                this.classList.add('active');
                this.textContent = 'ー';
                tbody.style.display = 'table-row-group';
            } else {
                // 閉じる
                this.classList.remove('active');
                this.textContent = '＋';
                tbody.style.display = 'none';
            }
        });
    });
//----------------------------------------------------------------------------------------------------
    // .delete_runが存在するページだけtrue
    if (deleteRuns.length) {

        deleteRuns.forEach(el => {

            el.addEventListener('click', function (e) {

                // 親へのイベント伝播を止める
                e.stopPropagation();

                // 削除確認
                if (!confirm('この運行を消しますか？')) {
                    return;
                }

                // caption（親要素）
                const caption = this.parentElement;

                // tbody（次の要素）
                const tbody = caption?.nextElementSibling;

                if (tbody) tbody.remove();
                caption.remove();

                // 再採番
                updateRunNumbers();

            });
        });
    }
//----------------------------------------------------------------------------------------------------
});