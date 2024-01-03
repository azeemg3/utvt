<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class LeadReminder extends Model
{
    use HasFactory;
    protected $guarded=[''];
    protected $casts = [
        'created_at' => "datetime:d-m-Y h:i:s",
    ];
}
