<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Auth;

class Product extends Model
{
    use HasFactory;

    protected $guarded = [];

    protected static function boot()
    {
        parent::boot();

        static::creating(function ($model) {
            if (auth()->check()) {
                $model->created_by = Auth::id();
            }
        });

        static::updating(function ($model) {
            if (auth()->check()) {
                $model->updated_by = Auth::id();
            }
        });
    }

    public function createdBy()
    {
        return $this->belongsTo(User::class, 'created_by');
    }

    public static function dropdown($selected = null)
    {
        $list = '';
        foreach (self::where('status', 1)->orderBy('name')->get() as $item) {
            $isSelected = ($selected == $item->id) ? 'selected' : '';
            $list .= '<option ' . $isSelected . ' value="' . $item->id . '" data-price="' . $item->price . '" data-name="' . e($item->name) . '">' . e($item->name) . ' (' . number_format($item->price, 2) . ')</option>';
        }
        return $list;
    }

    public static function activeList()
    {
        return self::where('status', 1)->orderBy('name')->get(['id', 'name', 'price', 'sku']);
    }
}
