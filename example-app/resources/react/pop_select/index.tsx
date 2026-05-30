import ReactDOM from 'react-dom/client';
import PopSelect from '../PopSelect/PopSelect';

const App = (): React.JSX.Element => {
    return <PopSelect />;
};

const container = document.getElementById('app');

if (container) {
    ReactDOM.createRoot(container).render(
        <App />
    );
}