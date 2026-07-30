<?php

namespace App\Http\Controllers;

use App\Models\Category;
use Illuminate\Http\Request;

class CategoryController extends Controller
{
    // Menampilkan daftar semua kategori beserta jumlah produk di masing-masing kategori
    public function index()
    {
        $categories = Category::withCount('products')->get();
        return view('categories.index', compact('categories'));
    }

    // Menampilkan form untuk membuat kategori baru
    public function create()
    {
        return view('categories.create');
    }

    // Memproses dan menyimpan kategori baru ke database
    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255|unique:categories,name',
        ]);

        Category::create([
            'name' => $request->name,
        ]);

        return redirect()->route('categories.index')->with('success', 'Kategori berhasil ditambahkan!');
    }

    // Menampilkan detail satu kategori (tidak digunakan / belum diimplementasi)
    public function show(Category $category)
    {
        //
    }

    // Menampilkan form edit untuk kategori yang dipilih
    public function edit(Category $category)
    {
        return view('categories.edit', compact('category'));
    }

    // Memproses dan menyimpan perubahan data kategori ke database
    public function update(Request $request, Category $category)
    {
        $request->validate([
            'name' => 'required|string|max:255|unique:categories,name,' . $category->id,
        ]);

        $category->update([
            'name' => $request->name,
        ]);

        return redirect()->route('categories.index')->with('success', 'Kategori berhasil diperbarui!');
    }

    // Menghapus kategori dari database (hanya jika tidak ada produk yang menggunakannya)
    public function destroy(Category $category)
    {
        // Periksa apakah kategori masih digunakan oleh produk
        if ($category->products()->count() > 0) {
            return redirect()->route('categories.index')->with('error', 'Kategori tidak bisa dihapus karena sedang digunakan oleh produk!');
        }

        $category->delete();

        return redirect()->route('categories.index')->with('success', 'Kategori berhasil dihapus!');
    }
}
