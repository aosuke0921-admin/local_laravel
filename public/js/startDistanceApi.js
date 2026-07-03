// All javaScript
//----------------------------------------------------------------------------------------
function fetchStartDistance(date, car) {
    return fetch(`/get-start-distance?dates=${date}&car=${car}`)
        .then(res => {
            if (!res.ok) throw new Error('Network Error');
            return res.json();
        });
}