<?php

namespace App\Models\Lms;

use App\Models\User;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Auth;

class SourceQuery extends Model
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

        static::creating(function ($SourceQuery) {
            if (auth()->check()) {
                $SourceQuery->created_by =Auth::user()->id;
            }
        });
    }

    public static function dropdown($id=0){
        $res=self::all();
        $list='';
        foreach ($res as $item){
            $list.='<option value="'.$item->id.'">'.$item->name.'</option>';
        }
        return $list;
    }
}
