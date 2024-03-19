<?php

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class LeadReminder extends Model
{
    use HasFactory;
    protected $guarded=[''];
    protected $casts = [
        'created_at' => "datetime:d-m-Y h:i:s",
    ];
    public function getCreatedAtAttribute($value)
    {
        return Carbon::createFromTimestamp(strtotime($value))
            ->timezone('Asia/Karachi')
            ->toDateTimeString()
        ;
    }
}
