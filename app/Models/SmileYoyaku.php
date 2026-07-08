<?php // 100点

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

use App\Models\Customer;

class SmileYoyaku extends Model
{
    protected $table = 'smile_yoyaku';

    protected $fillable = [
        'user',
        'destination',
        'reservation_datetime',
        'client_name',
        'receptionist',
        'input_date',
        'attention',
        'remarks_txt',
        'place',
        'is_reflected',
        'reflected_at',
        'reflected_by',
        'edited_at', //←追加
    ];

    protected $casts = [
        'reservation_datetime' => 'datetime',
        'input_date' => 'datetime',
        'edited_at' => 'datetime', //←追加
        'reflected_at' => 'datetime',
        'created_at' => 'datetime',
        'updated_at' => 'datetime',
    ];

    public function customer()
    {
        //return $this->belongsTo(Customer::class, 'user', 'name');
        return $this->belongsTo(Customer::class, 'client_name', 'name');
    }
}