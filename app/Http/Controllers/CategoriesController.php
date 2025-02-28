<?php

namespace App\Http\Controllers;

use Inertia\Inertia;
use Inertia\Response;
use App\Models\Category;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\Request;
use Illuminate\Support\Facades\Redirect;

class CategoriesController extends Controller
{
    public function index(): Response
    {
        return Inertia::render('Categories/Index', [
            'filters' => Request::all('search', 'trashed'),
            'categories' => Category::orderBy('name', 'desc')
                ->filter(Request::only('search', 'trashed'))
                ->paginate(10)
                ->withQueryString()
                ->through(function ($category) {
                    return [
                        'id' => $category->id,
                        'name' => $category->name,
                        'description' => $category->description,
                        'deleted_at' => $category->deleted_at,
                    ];
                }),
        ]);
    }

    public function create(): Response
    {
        return Inertia::render('Categories/Create');
    }

    public function store(): RedirectResponse
    {
        Category::create(
            Request::validate([
                'name' => ['required', 'max:50'],
                'description' => ['nullable', 'max:255'],
            ])
        );

        return Redirect::route('categories')->with('success', 'Category created.');
    }

    public function edit(Category $category): Response
    {
        return Inertia::render('Categories/Edit', [
            'category' => [
                'id' => $category->id,
                'name' => $category->name,
                'description' => $category->description,
                'deleted_at' => $category->deleted_at,
            ],
        ]);
    }

    public function update(Category $category): RedirectResponse
    {
        $category->update(
            Request::validate([
                'name' => ['required', 'max:50'],
                'description' => ['nullable', 'max:255'],
            ])
        );

        return Redirect::route('categories')->with('success', 'Category updated.');
    }

    public function destroy(Category $category): RedirectResponse
    {
        $category->delete();

        return Redirect::route('categories')->with('success', 'Category deleted.');
    }

    public function restore(Category $category): RedirectResponse
    {
        $category->restore();

        return Redirect::route('categories')->with('success', 'Category restored.');
    }
}
