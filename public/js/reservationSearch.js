document.addEventListener('DOMContentLoaded', () => {

    const month = localStorage.getItem('month_select');

    if (month) {
        document.querySelector('.moth_select').value = month;
    }

    document.querySelector('.moth_select')?.addEventListener('change', e => {
        localStorage.setItem('month_select', e.target.value);
    });

    const year = localStorage.getItem('year_select');

    if (year) {
        document.querySelector('.year_select').value = year;
    }

    document.querySelector('.year_select')?.addEventListener('change', e => {
        localStorage.setItem('year_select', e.target.value);
    });
});