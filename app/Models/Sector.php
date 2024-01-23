<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Sector extends Model
{
    use HasFactory;
    protected $guarded=[];
    public static function dropdown($ids=[]){
        $list='';
        $data=self::all();
        foreach($data as $item){
            $list.='<option '.(in_array($item->sector,json_decode($ids,true))?'selected':'').' value="'.$item->sector.'">'.$item->sector.'</option>';
        }
        return $list;
    }
}
