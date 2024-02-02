<?php

namespace App\Models\Lms;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Auth;

class SService extends Model
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

    public static function dropdown($ids=''){
        $ids=json_decode($ids,true);
        $res=self::all();
        $list='';
        foreach ($res as $item){
            if(is_array($ids) && count($ids)>0){
                $list.='<option '.(in_array($item->id,$ids)?'selected':'').' value="'.$item->id.'">'.$item->name.'</option>';
            }else{
                $list.='<option  value="'.$item->id.'">'.$item->name.'</option>';
            }
        }
        return $list;
    }
}
