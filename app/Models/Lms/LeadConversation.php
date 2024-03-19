<?php

namespace App\Models\Lms;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Auth;
use App\Models\User;
use Carbon\Carbon;

class LeadConversation extends Model
{
    use HasFactory;
    protected $guarded=[''];

    public function user(){
        return $this->belongsTo(User::class,'created_by','id');
    }
    protected $casts = [
        'created_at' => "datetime:d-m-Y h:i:s",
    ];
    public function createdBy()
    {
        return $this->belongsTo(User::class, 'created_by');
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
    public function getCreatedAtAttribute($value)
    {
        return Carbon::createFromTimestamp(strtotime($value))
            ->timezone('Asia/Karachi')
            ->toDateTimeString()
        ;
    }
}
