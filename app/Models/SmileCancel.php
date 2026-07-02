<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

use App\Models\Customer;

class SmileCancel extends Model
{
    protected $table = 'smile_cancel';

    protected $fillable = [
        'user',
        'destination',
        'cancel_date',
        'datetimes',
        'client_name',
        'receptionist',
        'input_date',
        'reflection_date',
        'attention',
        'remarks_txt',
        'place',
    ];

    // 日付をCarbon化
    protected $casts = [
        'cancel_date' => 'datetime',
        'input_date'  => 'datetime',
    ];

    // 顧客とのリレーション
    public function customer()
    {
        return $this->belongsTo(Customer::class, 'user', 'name');
    }
}