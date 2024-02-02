<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Sector extends Model
{
    use HasFactory;
    protected $guarded=[];
    public static function dropdown($ids=''){
        $list='';
        $ids=json_decode($ids,true);
        $data=self::all();
        foreach($data as $item){
            if(is_array($ids) && count($ids)>0){
                $list.='<option '.(in_array($item->sector,$ids)?'selected':'').' value="'.$item->sector.'">'.$item->sector.'</option>';
            }else{
                $list.='<option  value="'.$item->sector.'">'.$item->sector.'</option>';
            }
        }
        return $list;
    }
}
