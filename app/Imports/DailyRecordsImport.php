<?php

namespace App\Imports;

use Carbon\Carbon;
use App\Models\Product;
use App\Models\DailyRecord;
use Illuminate\Support\Collection;
use Maatwebsite\Excel\Concerns\ToCollection;
use Maatwebsite\Excel\Concerns\WithHeadingRow;
use Maatwebsite\Excel\Concerns\WithValidation;

class DailyRecordsImport implements ToCollection, WithHeadingRow, WithValidation
{
    private $products;
    private $errors;
    private $recordDate;

    public function __construct($recordDate = null)
    {
        $this->recordDate = $recordDate ?? now();
        $this->products = Product::pluck('id', 'code');
        $this->errors = new Collection();
    }

    public function collection(Collection $rows)
    {
        // Group products by date if date column exists
        $productsByDate = $rows->groupBy(function ($row) {
            return isset($row['date']) ? Carbon::parse($row['date'])->format('Y-m-d') : $this->recordDate->format('Y-m-d');
        });

        foreach ($productsByDate as $date => $products) {
            // Create a daily record for this date
            $dailyRecord = DailyRecord::create([
                'record_date' => $date,
                'number_of_products' => $products->count(),
                'total_revenue' => 0, // Will be calculated after adding products
            ]);

            foreach ($products as $row) {
                $product = $this->products->get($row['product_code']);
                
                if (!$product) {
                    $this->errors->push([
                        'row' => $row,
                        'error' => "Product with code '{$row['product_code']}' not found"
                    ]);
                    continue;
                }

                $productModel = Product::find($product);
                $quantity = (int) $row['quantity'];
                
                // Create daily record product
                $dailyRecord->dailyRecordProducts()->create([
                    'product_id' => $product,
                    'quantity' => $quantity,
                    'revenue' => $quantity * $productModel->selling_price,
                ]);
            }

            // Calculate total revenue for this daily record
            $dailyRecord->calculateTotalRevenue();
        }
    }

    public function rules(): array
    {
        return [
            'product_code' => ['required', 'string'],
            'quantity' => ['required', 'numeric', 'min:0'],
            'date' => ['nullable', 'date'],
        ];
    }

    public function getErrors(): Collection
    {
        return $this->errors;
    }
}