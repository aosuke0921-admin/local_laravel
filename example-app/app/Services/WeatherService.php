<?php //92点
namespace App\Services;

class WeatherService
{
    public function getTodayWeather()
    {
        $url = 'https://tenki.jp/forecast/3/16/4410/13101/';

        $ch = curl_init($url);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_USERAGENT, 'Mozilla/5.0');
        $html = curl_exec($ch);
        curl_close($ch);

        if (preg_match('/<p class="weather-telop">(.+?)<\/p>/', $html, $matches)) {
            return trim($matches[1]);
        }

        return '不明';
    }
}