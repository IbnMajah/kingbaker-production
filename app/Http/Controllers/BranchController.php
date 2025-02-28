<?php

namespace App\Http\Controllers;

use Inertia\Inertia;
use Inertia\Response;
use App\Models\Branch;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\Request;
use Illuminate\Support\Facades\Redirect;

class BranchController extends Controller
{
    public function index(): Response
    {
        return Inertia::render('Branch/Index', [
            'filters' => Request::all('search', 'trashed'),
            'branches' => Branch::orderBy('name', 'desc')
                ->filter(Request::only('search', 'trashed'))
                ->paginate(10)
                ->withQueryString()
                ->through(function ($branch) {
                    return [
                        'id' => $branch->id,
                        'name' => $branch->name,
                        'code' => $branch->code,
                        'phone' => $branch->phone,
                        'address' => $branch->address,
                        'deleted_at' => $branch->deleted_at,
                    ];
                }),
        ]);
    }

    public function create(): Response
    {
        return Inertia::render('Branch/Create');
    }

    public function store(): RedirectResponse
    {
        Branch::create(
            Request::validate([
                'name' => ['required', 'max:50'],
                'code' => ['required', 'max:50'],
                'phone' => ['required', 'max:50'],
                'address' => ['required', 'max:50'],
            ])
        );

        return Redirect::route('branches')->with('success', 'Branch created.');
    }

    public function edit(Branch $branch): Response
    {
        return Inertia::render('Branch/Edit', [
            'branch' => [
                'id' => $branch->id,
                'name' => $branch->name,
                'code' => $branch->code,
                'phone' => $branch->phone,
                'address' => $branch->address,
                'deleted_at' => $branch->deleted_at,
            ],
        ]);
    }

    public function update(Branch $branch): RedirectResponse
    {
        $branch->update(
            Request::validate([
                'name' => ['required', 'max:50'],
                'code' => ['required', 'max:50'],
                'phone' => ['required', 'max:50'],
                'address' => ['required', 'max:50'],
            ])
        );

        return Redirect::route('branches')->with('success', 'Branch updated.');
    }

    public function destroy(Branch $branch): RedirectResponse
    {
        $branch->delete();

        return Redirect::route('branches')->with('success', 'Branch deleted.');
    }

    public function restore(Branch $branch): RedirectResponse
    {
        $branch->restore();

        return Redirect::route('branches')->with('success', 'Branch restored.');
    }
}
