import './UserAddFields.css';

export default function UserAddFields() {
    return (
        <>
            <label>
                社員名
                <input
                    type="text"
                    name="full_name"
                    className="input full_name"
                />
            </label>

            <label>
                ユーザー
                <input
                    type="text"
                    name="user_name"
                    className="input user"
                />
            </label>

            <label>
                パスワード
                <input
                    type="password"
                    name="user_pass"
                    className="input pass"
                />
            </label>
        </>
    );
}