<?php

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class LeadActivity extends Model
{
    use HasFactory;
    protected $guarded=[''];

    protected static function boot()
    {
        parent::boot();

        static::creating(function (LeadActivity $activity) {
            if (empty($activity->action_time)) {
                $activity->action_time = now();
            }
        });
    }

    public function user(){
        return $this->belongsTo(User::class,'action_by','id');
    }
    public function getCreatedAtAttribute($value)
    {
        return Carbon::createFromTimestamp(strtotime($value))
            ->timezone('Asia/Karachi')
            ->toDateTimeString()
        ;
    }
}
