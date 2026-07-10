import ReactDOM from 'react-dom/client';

import PopSelect from './pop_select/PopSelect';
import Reminder from './reminder/Reminder';
import PushNotification from './push_notification/PushNotification';

import PageTop from './page_top/PageTop';

const components: Record<string, React.ComponentType> = {
    PopSelect,
    Reminder,
    PushNotification,
    PageTop,
};

const containers = document.querySelectorAll<HTMLElement>('.react');

containers.forEach((container) => {

    // デバッグ（必要なら残す）
    console.log("FOUND:", container.dataset.component);

    const component = container.dataset.component;

    if (!component) return;

    const Component = components[component];

    if (!Component) return;

    const root = ReactDOM.createRoot(container);
    root.render(<Component />);
});