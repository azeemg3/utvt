<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class BusinessSmtp extends Model
{
    use HasFactory;
    protected $guarded = [''];

    public function createdBy()
    {
        return $this->belongsTo(User::class, 'created_by');
    }

    protected static function boot()
    {
        parent::boot();

        static::creating(function ($user) {
            if (auth()->check()) {
                $user->created_by =Auth::user()->id;
            }
        });
    }
}
