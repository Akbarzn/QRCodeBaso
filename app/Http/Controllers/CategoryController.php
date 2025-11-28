<?php

namespace App\Http\Controllers;

use App\Models\Category;
use Illuminate\Http\Request;

class CategoryController extends Controller
{
    public function __construct()
    {
        // hanya boleh diakses oleh user yang login
    }

    /** List semua kategori */
    public function index()
    {
        $categories = Category::latest()->get();
        return view('categories.index', compact('categories'));
    }

    /** Form create kategori */
    public function create()
    {
        return view('categories.create');
    }

    /** Simpan kategori baru */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'nama_kategori' => ['required', 'string', 'max:100']
        ]);

        Category::create($validated);

        return redirect()
            ->route('categories.index')
            ->with('success', 'Kategori berhasil dibuat ✅');
    }

    /** Form edit kategori */
    public function edit(Category $category)
    {
        return view('categories.edit', compact('category'));
    }

    /** Update kategori */
    public function update(Request $request, Category $category)
    {
        $validated = $request->validate([
            'nama_kategori' => ['required', 'string', 'max:100']
        ]);

        $category->update($validated);

        return redirect()
            ->route('categories.index')
            ->with('success', 'Kategori berhasil diupdate ✨');
    }

    /** Soft delete kategori */
    public function destroy(Category $category)
    {
        $category->delete();

        return redirect()
            ->route('categories.index')
            ->with('success', 'Kategori berhasil dihapus 🗑️');
    }

    /** Restore kategori yang ke-soft delete */
    public function restore($id)
    {
        $category = Category::onlyTrashed()->findOrFail($id);
        $category->restore();

        return redirect()
            ->route('categories.index')
            ->with('success', 'Kategori berhasil direstore ♻️');
    }
}