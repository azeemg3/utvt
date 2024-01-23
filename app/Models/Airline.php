<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Auth;

class Airline extends Model
{
    use HasFactory;
    protected $guarded=[];
    protected static function boot()
    {
        parent::boot();

        static::creating(function ($SourceQuery) {
            if (auth()->check()) {
                $SourceQuery->created_by =Auth::user()->id;
            }
        });
    }

    public static function dropdown($ids=[]){
        $res=self::all();
        $list='';
        foreach ($res as $item){
            $list.='<option '.(in_array($item->id,json_decode($ids,true))?'selected':'').' value="'.$item->id.'">'.$item->name.'</option>';
        }
        return $list;
    }
}
