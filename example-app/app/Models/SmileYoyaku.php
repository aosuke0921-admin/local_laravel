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
    ];

    protected $casts = [
        'reservation_datetime' => 'datetime',
        'input_date' => 'datetime',
        'created_at' => 'datetime',
        'updated_at' => 'datetime',
    ];

    public function customer()
    {
        //return $this->belongsTo(Customer::class, 'user', 'name');
        return $this->belongsTo(Customer::class, 'client_name', 'name');
    }
}