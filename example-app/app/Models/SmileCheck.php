<?php //92点

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class SmileCheck extends Model
{
    use HasFactory;

    protected $table = 'smile_check';

    protected $fillable = [
        'user_id', 'car', 'roll_call', 'weather', 'dates', 'datetimes'
    ];

    public $timestamps = false; //安全に INSERT　おまじない

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}