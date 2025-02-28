<?php

namespace App\Models;

use App\Models\Product;
use App\Models\DailyRecordProduct;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Notifications\Notifiable;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class DailyRecord extends Model
{
    use HasFactory;
    use Notifiable;
    use SoftDeletes;

    /**
     * The attributes that are guarded.
     *
     * @var array<int, string>
     */
    protected $guarded = ['id', 'created_at', 'updated_at', 'deleted_at'];

    protected $casts = [
        'record_date' => 'date',
        'total_revenue' => 'decimal:2',
        'number_of_products' => 'integer',
    ];

    public function scopeFilter($query, array $filters)
    {
        $query->when($filters['search'] ?? null, function ($query, $search) {
            $query->where('record_date', 'like', '%'.$search.'%')
                ->orWhereHas('dailyRecordProducts.product', function ($query) use ($search) {
                    $query->where('name', 'like', '%'.$search.'%')
                        ->orWhere('description', 'like', '%'.$search.'%')
                        ->orWhere('code', 'like', '%'.$search.'%');
                });
        });

        $query->when($filters['record_date'] ?? null, function ($query, $date) {
            $query->whereDate('record_date', $date);
        });

        $query->when($filters['trashed'] ?? null, function ($query, $trashed) {
            if ($trashed === 'with') {
                $query->withTrashed();
            } elseif ($trashed === 'only') {
                $query->onlyTrashed();
            }
        });
    }

    public function resolveRouteBinding($value, $field = null)
    {
        return $this->where($field ?? 'id', $value)->withTrashed()->firstOrFail();
    }

    public function dailyRecordProducts()
    {
        return $this->hasMany(DailyRecordProduct::class);
    }

    public function products()
    {
        return $this->belongsToMany(Product::class, 'daily_record_products')
            ->withPivot(['quantity', 'revenue'])
            ->withTimestamps();
    }

    public function calculateTotalRevenue()
    {
        $this->total_revenue = $this->dailyRecordProducts->sum('revenue');
        $this->number_of_products = $this->dailyRecordProducts->count();
        $this->save();
    }
}
