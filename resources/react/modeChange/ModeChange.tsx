import './ModeChange.css';

export default function ModeChange() {
    return (
        <div className="mode_change">

            <label>
                <input
                    type="radio"
                    name="mode"
                    value="customers"
                />
                <span>利用者</span>
            </label>

            <label>
                <input
                    type="radio"
                    name="mode"
                    value="destinations"
                />
                <span>行き先</span>
            </label>

            <label>
                <input
                    type="radio"
                    name="mode"
                    value="user_destination_records"
                    defaultChecked
                />
                <span>利用者・行き先</span>
            </label>

        </div>
    );
}