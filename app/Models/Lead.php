<?php

namespace App\Models;

use App\Models\Lms\SourceQuery;
use App\Models\Lms\SService;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Auth;

class Lead extends Model
{
    use HasFactory;
    protected $guarded = [''];

    public function leadSpo()
    {
        return $this->belongsTo(User::class, 'spo', 'id');
    }

    public function createdBy()
    {
        return $this->belongsTo(User::class, 'created_by');
    }
    public function country(){
        return $this->belongsTo(Country::class,'CID','id');
    }
    public function city(){
        return $this->belongsTo(City::class,'CTID','id');
    }
    public function source(){
        return $this->belongsTo(SourceQuery::class,'source_id','id');
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
    protected $casts = [
        'created_at' => "datetime:d-m-Y h:i:s",
    ];
    /**Lead Recent action status and time */
    public static function recent_action($leadId,$status){
        $res=LeadActivity::with(['user'])->where(['LID'=>$leadId,'action_status'=>$status])->orderBy('id','DESC')->first();
        return $res;
    }
}
