import './InitialTabs.css';

export default function InitialTabs() {
    return (
        <div className="tab-add">
            <ul className="tabs">
                <li className="initial_tab active" data-initial="あ">あ</li>
                <li className="initial_tab" data-initial="か">か</li>
                <li className="initial_tab" data-initial="さ">さ</li>
                <li className="initial_tab" data-initial="た">た</li>
                <li className="initial_tab" data-initial="な">な</li>
                <li className="initial_tab" data-initial="は">は</li>
                <li className="initial_tab" data-initial="ま">ま</li>
                <li className="initial_tab" data-initial="や">や</li>
                <li className="initial_tab" data-initial="ら">ら</li>
                <li className="initial_tab" data-initial="わ">わ</li>
            </ul>
        </div>
    );
}