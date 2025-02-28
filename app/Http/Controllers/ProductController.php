<?php

namespace App\Http\Controllers;

use Inertia\Inertia;
use Inertia\Response;
use App\Models\Branch;
use App\Models\Product;
use App\Models\Category;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\Request;
use Illuminate\Support\Facades\Redirect;

class ProductController extends Controller
{
    public function index(): Response
    {
        return Inertia::render('Products/Index', [
            'filters' => Request::all('search', 'trashed'),
            'products' => Product::orderBy('name', 'desc')
                ->filter(Request::only('search', 'trashed'))
                ->paginate(10)
                ->withQueryString()
                ->through(function ($product) {
                    return [
                        'id' => $product->id,
                        'name' => $product->name,
                        'code' => $product->code,
                        'description' => $product->description,
                        'cost_price' => $product->cost_price,
                        'selling_price' => $product->selling_price,
                        'quantity' => $product->quantity,
                        'branch' => $product->branch ? $product->branch->only('id', 'name') : null,
                        'category' => $product->category ? $product->category->only('id', 'name') : null,
                        'deleted_at' => $product->deleted_at,
                    ];
                }),
        ]);
    }

    public function create(): Response
    {
        return Inertia::render('Products/Create', [
            'branches' => Branch::orderBy('name', 'asc')->get()
            ->map
            ->only('id', 'name'),
            'categories' => Category::orderBy('name', 'asc')->get()
            ->map
            ->only('id', 'name'),
        ]);
    }

    public function store(): RedirectResponse
    {
        Product::create(
            Request::validate([
                'name' => ['required', 'max:50'],
                'code' => ['required', 'max:50', 'unique:products'],
                'description' => ['nullable'],
                'cost_price' => ['required', 'numeric'],
                'selling_price' => ['required', 'numeric'],
                'quantity' => ['required', 'integer'],
                'branch_id' => ['nullable', 'exists:branches,id'],
                'category_id' => ['nullable', 'exists:categories,id'],
            ])
        );

        return Redirect::route('products')->with('success', 'Product created.');
    }

    public function edit(Product $product): Response
    {
        return Inertia::render('Products/Edit', [
            'product' => [
                'id' => $product->id,
                'name' => $product->name,
                'code' => $product->code,
                'description' => $product->description,
                'cost_price' => $product->cost_price,
                'selling_price' => $product->selling_price,
                'quantity' => $product->quantity,
                'branch_id' => $product->branch_id,
                'category_id' => $product->category_id,
                'deleted_at' => $product->deleted_at,
            ],
            'branches' => Branch::orderBy('name', 'asc')->get()
            ->map
            ->only('id', 'name'),
            'categories' => Category::orderBy('name', 'asc')->get()
            ->map
            ->only('id', 'name'),
        ]);
    }

    public function update(Product $product): RedirectResponse
    {
        $product->update(
            Request::validate([
                'name' => ['required', 'max:50'],
                'code' => ['required', 'max:50', 'unique:products,code,'.$product->id],
                'description' => ['nullable'],
                'cost_price' => ['required', 'numeric'],
                'selling_price' => ['required', 'numeric'],
                'quantity' => ['required', 'integer'],
                'branch_id' => ['nullable', 'exists:branches,id'],
                'category_id' => ['nullable', 'exists:categories,id'],
            ])
        );

        return Redirect::route('products')->with('success', 'Product updated.');
    }

    public function destroy(Product $product): RedirectResponse
    {
        $product->delete();

        return Redirect::route('products')->with('success', 'Product deleted.');
    }

    public function restore(Product $product): RedirectResponse
    {
        $product->restore();

        return Redirect::route('products')->with('success', 'Product restored.');
    }

    public function addQuantity(Product $product): RedirectResponse
    {
        Request::validate([
            'quantity' => ['required', 'integer', 'min:1'],
        ]);

        $product->quantity += Request::input('quantity');
        $product->save();

        return Redirect::back()->with('success', 'Quantity added successfully.');
    }
}
