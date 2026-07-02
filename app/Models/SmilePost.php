<?php //90点

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class SmilePost extends Model
{
    use HasFactory;

    // 作成したいカラムをすべて列挙
    protected $fillable = [
        'car',
        'start_distance',
        'end_distance',
        'member',
        'dates',
        'user',
        'departureTime',
        'arrivalTime',
        'goingBack',
        'destination',
        'any',
        'shareRide',
        'classification',
        'remarks',
        'distance',
        'price',
        'datetimes',
    ];

    // タイムスタンプカラムがない場合
    //public $timestamps = false;
}