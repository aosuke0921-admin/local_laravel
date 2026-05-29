import React from 'react';
import ReactDOM from 'react-dom/client';
import PopSelect from '../PopSelect/PopSelect';

function App() {
    return (
        <div>
            <PopSelect />
        </div>
    );
}

ReactDOM.createRoot(document.getElementById('app')).render(
    <App />
);