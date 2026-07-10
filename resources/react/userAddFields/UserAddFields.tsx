import { useEffect } from 'react';
import './UserAddFields.css';

export default function UserAddFields() {

    useEffect(() => {

        const form = document.querySelector<HTMLFormElement>('#new_addition_form');

        if (!form) return;

        const handleSubmit = (e: SubmitEvent) => {

            const fullName = document.querySelector<HTMLInputElement>('.full_name');
            const user = document.querySelector<HTMLInputElement>('.user');
            const pass = document.querySelector<HTMLInputElement>('.pass');

            const ERROR_MESSAGE =
                '社員名・ユーザー名・パスワードを正しく入力してください';

            if (
                !fullName?.value ||
                !user?.value ||
                !pass?.value
            ) {
                alert(ERROR_MESSAGE);
                e.preventDefault();
            }
        };

        form.addEventListener('submit', handleSubmit);

        return () => {
            form.removeEventListener('submit', handleSubmit);
        };

    }, []);

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