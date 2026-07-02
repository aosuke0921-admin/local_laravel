// All javaScript
//----------------------------------------------------------------------------------------
(function () {

  function DeleteCheckPlugin() {

    //const deleteBtns = document.querySelectorAll('.delte_btn');
    const deleteBtn = document.querySelector('.delete_btn');
    const cloneBtn = document.querySelector('.clone_btn');
    const checkAllBox = document.querySelector('.checkall_box');
    const deleteChecks = document.querySelectorAll('.delete_check');

    // 初期状態
    if (deleteBtn) {
      deleteBtn.style.display = 'none';
    }

    // ------------------------
    // 個別チェックボックス
    // ------------------------
    deleteChecks.forEach(box => {
      box.addEventListener('change', function () {

        const checkedCount = document.querySelectorAll('.delete_check:checked').length;

        if (deleteBtn && cloneBtn && checkAllBox) {
          if (checkedCount > 0) {
            deleteBtn.style.display = 'block';
            cloneBtn.style.display = 'none';
            checkAllBox.checked = false;
          } else {
            deleteBtn.style.display = 'none';
            cloneBtn.style.display = 'block';
            checkAllBox.checked = false;
          }
        }
      });
    });

    // ------------------------
    // 全選択チェックボックス
    // ------------------------
    if (checkAllBox) {
      checkAllBox.addEventListener('click', function () {

        if (this.checked) {
          deleteChecks.forEach(box => {
            box.checked = true;
          });

          if (deleteBtn && cloneBtn) {
            deleteBtn.style.display = 'block';
            cloneBtn.style.display = 'none';
          }

        } else {
          deleteChecks.forEach(box => {
            box.checked = false;
          });

          if (deleteBtn && cloneBtn) {
            deleteBtn.style.display = 'none';
            cloneBtn.style.display = 'block';
          }
        }
      });
    }
  }

  // 実行
  document.addEventListener('DOMContentLoaded', DeleteCheckPlugin);

})();