<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\SoftDeletes;

class DailyRecordProduct extends Model
{
    use HasFactory;
    use SoftDeletes;

    protected $fillable = [
        'product_id',
        'daily_record_id',
        'quantity',
        'revenue',
    ];

    protected $casts = [
        'quantity' => 'integer',
        'revenue' => 'decimal:2',
    ];

    public function dailyRecord()
    {
        return $this->belongsTo(DailyRecord::class);
    }

    public function product()
    {
        return $this->belongsTo(Product::class);
    }

    public function calculateRevenue()
    {
        if ($this->product) {
            $this->revenue = $this->quantity * $this->product->selling_price;
            $this->save();

            // Update the parent daily record's total
            $this->dailyRecord->calculateTotalRevenue();
        }
    }
} 