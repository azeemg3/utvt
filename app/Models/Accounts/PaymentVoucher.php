<?php

namespace App\Models\Accounts;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class PaymentVoucher extends Model
{
    use HasFactory;
    use SoftDeletes;
    protected $fillable=['trans_date', 'posting_date', 'payment_to', 'payment_from',
        'payment_type', 'amount', 'cheque', 'currency', 'conversion_rate', 'ref', 'remarks',
        'status', 'Created_by', 'Updated_by', 'BID', 'trans_code','attach_file'];

    public function setTransDateAttribute($value)
    {
        $this->attributes['trans_date'] =Carbon::parse($value)->format('Y-m-d');
    }
    public function setPostingDateAttribute($value)
    {
        $this->attributes['trans_date'] =Carbon::parse($value)->format('Y-m-d');
    }
    public function trans_acc(){
        return $this->belongsTo(TransactionAccount::class,'payment_to','id');
    }

}
