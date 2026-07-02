    <div class="date_wrap_parent">
        <div class="date_wrap">

            <button type="submit" name="move" value="-1m" class="arrow"><img src="{{ asset('image/arrow_left_double.png') }}" alt=""></button>
            <button type="submit" name="move" value="-1d" class="arrow"><img src="{{ asset('image/arrow_left.png') }}" alt=""></button>

            <div class="inner">

                <input type="hidden"
                    class="date_input"
                    name="dates"
                    value="{{ $date }}">

                    <span id="year">{{ $year }}</span>年
                    <span id="month">{{ $month }}</span>月
                    <span id="day">{{ $day }}</span>日
                    <img src="{{ asset('image/btn_calender.png') }}" class="ymd date_icon">

            </div>

            <button type="submit" name="move" value="1d" class="arrow"><img src="{{ asset('image/arrow_right.png') }}" alt=""></button>
            <button type="submit" name="move" value="1m" class="arrow"><img src="{{ asset('image/arrow_right_double.png') }}" alt=""></button>

        </div>
    </div>
    <div class="cl_wp">
        <div class="cl_toggle">
            <div id="calendar"></div>
        </div>
    </div>