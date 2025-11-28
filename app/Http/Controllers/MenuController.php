<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Models\Menu;
use App\Models\Category;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;


class MenuController extends Controller
{

    public function index()
    {
        $menus = Menu::with('category')->latest()->get();
        return view('menus.index', compact('menus'));
    }

    public function create()
    {
        $categories = Category::all();
        return view('menus.create', compact('categories'));
    }

   /** Simpan menu baru + upload gambar */
public function store(Request $request)
{
    $validated = $request->validate([
        'nama_menu' => 'required|string|max:120',
        'category_id' => 'required|exists:categories,id',
        'harga' => 'required|numeric|min:0',
        'stok' => 'required|integer|min:0',
        'status' => 'required|boolean',
        'image' => 'required|image|mimes:jpg,png,jpeg|max:2048', // 💀 WAJIB image upload
    ]);

    // Upload image pakai Storage
    $path = $request->file('image')->store('menu-images', 'public');

    $validated['image'] = '/storage/' . $path;

    Menu::create($validated);

    return redirect()->route('menus.index')->with('success', 'Menu berhasil dibuat ✅');
}

/** Update menu + ganti gambar jika ada */
public function update(Request $request, Menu $menu)
{
    $validated = $request->validate([
        'nama_menu' => 'required|string|max:120',
        'category_id' => 'required|exists:categories,id',
        'harga' => 'required|numeric|min:0',
        'stok' => 'required|integer|min:0',
        'status' => 'required|boolean',
        'image' => 'nullable|image|mimes:jpg,png,jpeg|max:2048', // ✨ optional
    ]);

    if ($request->hasFile('image')) {
        // delete gambar lama
        if ($menu->image) {
            $old = str_replace('/storage/', '', $menu->image);
            Storage::disk('public')->delete($old);
        }

        // upload baru
        $path = $request->file('image')->store('menu-images', 'public');
        $validated['image'] = '/storage/' . $path;
    }

    $menu->update($validated);

    return redirect()->route('menus.index')->with('success', 'Menu berhasil diupdate ✨');
}

    public function edit(Menu $menu)
    {
        $categories = Category::all();
        return view('.menus.edit', compact('menu', 'categories'));
    }


    public function destroy(Menu $menu)
    {
        $menu->delete();
        return redirect()
            ->route('menus.index')
            ->with('success', 'Menu berhasil dihapus 🗑️');
    }

    public function show(Menu $menu)
    {
        return redirect()->route('menus.index'); // sementara skip
    }
}
