<?php

namespace App\Http\Controllers;

use Inertia\Inertia;
use Inertia\Response;
use App\Models\DailyRecord;
use App\Models\Product;
use Illuminate\Support\Facades\Request;
use Illuminate\Support\Facades\Redirect;
use Illuminate\Validation\ValidationException;
use Maatwebsite\Excel\Facades\Excel;
use App\Imports\DailyRecordsImport;
use Carbon\Carbon;

class DailyRecordController extends Controller
{
    public function index(): Response
    {
        return Inertia::render(
            'DailyRecords/Index',
            [
                'filters' => Request::all('search', 'trashed', 'record_date'),
                'dailyRecords' => DailyRecord::orderBy('record_date', 'desc')
                    ->filter(Request::only('search', 'trashed', 'record_date'))
                    ->paginate(10)
                    ->withQueryString()
                    ->through(fn ($dailyRecord) => [
                        'id' => $dailyRecord->id,
                        'record_date' => $dailyRecord->record_date,
                        'total_revenue' => $dailyRecord->total_revenue,
                        'number_of_products' => $dailyRecord->number_of_products,
                        'deleted_at' => $dailyRecord->deleted_at,
                    ]),
            ]
        );
    }

    public function create(): Response
    {
        return Inertia::render('DailyRecords/Create', [
            'products' => Product::all()->map->only('id', 'name', 'code', 'selling_price'),
        ]);
    }

    public function store()
    {
        $validated = Request::validate([
            'record_date' => ['required', 'date'],
            'products' => ['required', 'array', 'min:1'],
            'products.*.product_id' => ['required', 'exists:products,id'],
            'products.*.quantity' => ['required', 'numeric', 'min:0'],
        ]);
        
        $dailyRecord = DailyRecord::create([
            'record_date' => $validated['record_date'],
            'number_of_products' => count($validated['products']),
            'total_revenue' => 0, // Will be calculated after adding products
        ]);

        foreach ($validated['products'] as $productData) {
            $product = Product::find($productData['product_id']);
            $dailyRecord->dailyRecordProducts()->create([
                'product_id' => $productData['product_id'],
                'quantity' => $productData['quantity'],
                'revenue' => $productData['quantity'] * $product->selling_price,
            ]);
        }

        $dailyRecord->calculateTotalRevenue();

        return Redirect::route('daily-records')->with('success', 'Daily record created.');
    }

    public function edit(DailyRecord $dailyRecord): Response
    {
        // Check if record is more than a week old
        $oneWeekAgo = now()->subWeek();
        $recordDate = Carbon::parse($dailyRecord->record_date);
        
        if ($recordDate->lt($oneWeekAgo)) {
            return Redirect::back()->with('error', 'Records older than one week cannot be edited.');
        }

        return Inertia::render('DailyRecords/Edit', [
            'dailyRecord' => [
                'id' => $dailyRecord->id,
                'record_date' => $dailyRecord->record_date,
                'products' => $dailyRecord->dailyRecordProducts->map(fn ($drp) => [
                    'id' => $drp->id,
                    'product_id' => $drp->product_id,
                    'quantity' => $drp->quantity,
                    'revenue' => $drp->revenue,
                ]),
                'deleted_at' => $dailyRecord->deleted_at,
                'is_editable' => $recordDate->gte($oneWeekAgo),
            ],
            'products' => Product::all()->map->only('id', 'name', 'code', 'selling_price'),
        ]);
    }

    public function update(DailyRecord $dailyRecord)
    {
        // Check if record is more than a week old
        $oneWeekAgo = now()->subWeek();
        $recordDate = Carbon::parse($dailyRecord->record_date);
        
        if ($recordDate->lt($oneWeekAgo)) {
            return Redirect::back()->with('error', 'Records older than one week cannot be edited.');
        }

        $validated = Request::validate([
            'record_date' => ['required', 'date'],
            'products' => ['required', 'array', 'min:1'],
            'products.*.product_id' => ['required', 'exists:products,id'],
            'products.*.quantity' => ['required', 'numeric', 'min:0'],
        ]);

        $dailyRecord->update([
            'record_date' => $validated['record_date'],
            'number_of_products' => count($validated['products']),
        ]);

        // Delete existing product records
        $dailyRecord->dailyRecordProducts()->delete();

        // Create new product records
        foreach ($validated['products'] as $productData) {
            $product = Product::find($productData['product_id']);
            $dailyRecord->dailyRecordProducts()->create([
                'product_id' => $productData['product_id'],
                'quantity' => $productData['quantity'],
                'revenue' => $productData['quantity'] * $product->selling_price,
            ]);
        }

        $dailyRecord->calculateTotalRevenue();

        return Redirect::back()->with('success', 'Daily record updated.');
    }

    public function destroy(DailyRecord $dailyRecord)
    {
        $dailyRecord->delete();

        return Redirect::back()->with('success', 'Daily record deleted.');
    }

    public function restore(DailyRecord $dailyRecord)
    {
        $dailyRecord->restore();

        return Redirect::back()->with('success', 'Daily record restored.');
    }

    public function import()
    {
        Request::validate([
            'file' => ['required', 'file', 'mimes:csv,xlsx,xls'],
            'record_date' => ['nullable', 'date'],
        ]);

        try {
            $file = Request::file('file');
            $recordDate = Request::input('record_date') ? Carbon::parse(Request::input('record_date')) : now();
            
            $import = new DailyRecordsImport($recordDate);
            Excel::import($import, $file);
            
            $errors = $import->getErrors();
            
            if ($errors->isNotEmpty()) {
                return Redirect::back()
                    ->with('warning', 'File imported with some errors. Some records could not be processed.')
                    ->with('import_errors', $errors);
            }
            
            return Redirect::back()->with('success', 'File imported successfully.');
            
        } catch (\Exception $e) {
            throw ValidationException::withMessages([
                'file' => 'There was an error processing the file: ' . $e->getMessage(),
            ]);
        }
    }
}
