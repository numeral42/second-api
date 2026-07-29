<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Tag;
use App\Http\Requests\CreateTagRequest;
use App\Http\Requests\EditTagRequest;
class TagController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        return view('admin.tags.index');
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('admin.tags.create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(CreateTagRequest $request)
    {
        $tag = Tag::create($request->validated());
        session()->flash('swal', [
            'icon' => 'success',
            'title' => 'Etiqueta creada exitosamente.',
            'text' => 'La etiqueta ha sido creada correctamente.',
        ]);

        return redirect()->route('admin.tags.edit', $tag);
    }

    /**
     * Display the specified resource.
     */
    public function show(Tag $tag)
    {
        return view('admin.tags.show', compact('tag'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Tag $tag)
    {
        return view('admin.tags.edit', compact('tag'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(EditTagRequest $request, Tag $tag)
    {
        $tag->update($request->validated());
                session()->flash('swal', [
            'icon' => 'success',
            'title' => 'Etiqueta actualizada exitosamente.',
            'text' => 'La etiqueta ha sido actualizada correctamente.',
        ]);

        return redirect()->route('admin.tags.edit', $tag);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Tag $tag)
    {

        $tag->delete();
    }
}
