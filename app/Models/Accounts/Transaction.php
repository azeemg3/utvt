<?php

namespace App\Models\Accounts;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Session;
use Illuminate\Http\Request;
use Carbon\Carbon;

class Transaction extends Model
{
    use HasFactory;
//    protected static function boot()
//    {
//        parent::boot();
//
//        Transaction::creating(function($model) {
//            $model->financial_year = session::get('financial_year');;
//        });
//    }
    protected $fillable=['amount', 'dr_cr', 'vt', 'trans_code', 'trans_acc_id',
        'trans_date', 'posting_date', 'rec_date', 'narration', 'status',
        'Created_By', 'Updated_By', 'BID', 'created_at', 'updated_at', 'payment_type','SID'];
    protected $attributes=['Created_By'=>0, 'Updated_By'=>0, 'BID'=>0];

    public function trans_acc(){
        return $this->belongsTo(TransactionAccount::class,'trans_acc_id','id');
    }
    public function setTransDateAttribute($value){
        $this->attributes['trans_date'] = date('Y-m-d', strtotime($value));
    }
    public function setPostingDateAttribute($value)
    {
        $this->attributes['posting_date'] =Carbon::parse($value)->format('Y-m-d');
    }
}
