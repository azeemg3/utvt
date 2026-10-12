<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Auth;

class Invoice extends Model
{
    use HasFactory;

    protected $guarded = [];

    protected $casts = [
        'invoice_date' => 'date',
        'due_date' => 'date',
        'subtotal' => 'decimal:2',
        'tax_percent' => 'decimal:2',
        'tax_amount' => 'decimal:2',
        'discount' => 'decimal:2',
        'total' => 'decimal:2',
    ];

    protected static function boot()
    {
        parent::boot();

        static::creating(function ($model) {
            if (auth()->check()) {
                $model->created_by = Auth::id();
            }
            if (empty($model->invoice_number)) {
                $model->invoice_number = self::generateInvoiceNumber();
            }
        });

        static::updating(function ($model) {
            if (auth()->check()) {
                $model->updated_by = Auth::id();
            }
        });
    }

    public function items()
    {
        return $this->hasMany(InvoiceItem::class);
    }

    public function createdBy()
    {
        return $this->belongsTo(User::class, 'created_by');
    }

    public static function generateInvoiceNumber()
    {
        $prefix = 'RC ';
        $last = self::where('invoice_number', 'like', $prefix . '%')
            ->orderByDesc('id')
            ->value('invoice_number');

        $seq = 1;
        if ($last && preg_match('/(\d+)$/', $last, $m)) {
            $seq = ((int) $m[1]) + 1;
        }

        return $prefix . str_pad($seq, 4, '0', STR_PAD_LEFT);
    }

    public function serialDisplay()
    {
        // Prefer stored RC number; fallback from id
        if (preg_match('/RC\s*(\d+)/i', (string) $this->invoice_number, $m)) {
            return 'RC ' . str_pad($m[1], 4, '0', STR_PAD_LEFT);
        }

        return 'RC ' . str_pad((string) $this->id, 4, '0', STR_PAD_LEFT);
    }

    public function statusBadge()
    {
        $map = [
            'draft' => 'secondary',
            'unpaid' => 'warning',
            'paid' => 'success',
            'cancelled' => 'danger',
        ];
        $class = $map[$this->status] ?? 'secondary';
        return '<span class="badge badge-' . $class . '">' . ucfirst($this->status) . '</span>';
    }
}
