import './PopSelect.css';

export default function PopSelect() {

    const moveSupport = (): void => {

        window.location.href =
            '/boarding_reservation?mode=support&id=1';

    };

    const moveBoarding = (): void => {

        window.location.href =
            '/boarding_reservation?mode=support&id=2';

    };

    /*---------------------------------------------------------------*/

    return (

        <div className="pop_msg">

            <div className="inner">

                <div className="btn1" onClick={moveSupport}>
                    <p>支援</p>
                </div>

                <div className="btn2" onClick={moveBoarding}>
                    <p>乗降</p>
                </div>

                <div className="both">

                    <img
                        src="/image/wanko_hatena.png"
                        alt=""
                    />

                </div>

            </div>

        </div>

    );

}