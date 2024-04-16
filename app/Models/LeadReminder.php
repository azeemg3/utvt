<?php

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Auth;

class LeadReminder extends Model
{
    use HasFactory;
    protected $guarded=[''];
    public function lead(){
        return $this->belongsTo(Lead::class,'leadId','id');
    }
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
    public function getReminderDateAttribute($value)
    {
        return Carbon::parse($value)->format('d-m-Y');
    }
    protected static function boot()
    {
        parent::boot();

        static::creating(function ($SourceQuery) {
            if (auth()->check()) {
                $SourceQuery->created_by = Auth::user()->id;
            }
        });
    }
}
