import './PageTop.css';

export default function PageTop() {
    const handleClick = () => {
        window.scrollTo({
            top: 0,
            behavior: 'smooth',
        });
    };

    return (
        <div className="page_top" onClick={handleClick}>
            <img src="/image/pagetop.png" alt="PAGE TOP" className="pagetop_btn" />
            <span>PAGE TOP</span>
        </div>
    );
}