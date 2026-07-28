<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\CreateCategoryRequest;
use App\Http\Requests\EditCategoryRequest;
use App\Models\Category;
use Illuminate\Http\RedirectResponse;
use Illuminate\View\View;

class CategoryController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(): View
    {
        return view('admin.categories.index');
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create(): View
    {
        return view('admin.categories.create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(CreateCategoryRequest $request): RedirectResponse
    {
        $category = Category::create($request->validated());

        return redirect()
            ->route('admin.categories.edit', $category)
            ->with('swal', [
                'icon' => 'success',
                'title' => 'Categoría creada exitosamente.',
                'text' => 'La categoría ha sido creada correctamente.',
            ]);
    }

    /**
     * Display the specified resource.
     */
    public function show(Category $category): View
    {
        return view('admin.categories.show', compact('category'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Category $category): View
    {
        return view('admin.categories.edit', compact('category'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(
        EditCategoryRequest $request,
        Category $category
    ): RedirectResponse {
        $category->update($request->validated());

        session()->flash('swal', [
            'icon' => 'success',
            'title' => 'Categoría actualizada exitosamente.',
            'text' => 'La categoría ha sido actualizada correctamente.',
        ]);

        return redirect()->route('admin.categories.edit', $category);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Category $category, bool $redirect = true)
    {
        $category->delete();

        /* if (! $redirect) {
        return;
    }

    return redirect()
        ->route('admin.categories.index')
        ->with('swal', [
            'icon' => 'success',
            'title' => 'Categoría eliminada.',
            'text' => 'La categoría ha sido eliminada correctamente.',
        ]); */
    }
}
