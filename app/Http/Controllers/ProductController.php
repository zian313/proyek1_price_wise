<?php

namespace App\Http\Controllers;

use App\Models\Product;
use App\Models\Category;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class ProductController extends Controller
{
    // 1. READ: Menampilkan semua produk milik Seller yang sedang login
    public function index()
    {
        // Hanya mengambil produk yang diinput oleh user/seller yang sedang login saat ini
        $products = Product::where('user_id', Auth::id())->get();
        return view('seller.products.index', compact('products'));
    }

    public function create()
{
    $categories = \App\Models\Category::all(); // Pastikan ambil data dari model
    return view('seller.products.create', compact('categories'));
}

    // 3. STORE: Logika untuk memproses dan menyimpan data produk baru ke database
    public function store(Request $request)
    {
        // Validasi inputan form
        $request->validate([
            'category_id' => 'required|exists:categories,id',
            'nama_produk' => 'required|string|max:255',
            'deskripsi' => 'required|string',
            'harga' => 'required|numeric|min:0',
            'stok' => 'required|numeric|min:0',
            'foto' => 'nullable|image|mimes:jpeg,png,jpg,webp|max:2048', // Maksimal 2MB
            'bank_name' => 'nullable|string|max:255',
            'no_rekening' => 'nullable|string|max:255',
            'atas_nama' => 'nullable|string|max:255',
        ]);

        $nama_file_foto = null;
        // Logika jika seller mengunggah foto produk
        if ($request->hasFile('foto')) {
            $file = $request->file('foto');
            // Membuat nama file unik berdasarkan waktu agar tidak bentrok (misal: 171871234_sepatu.jpg)
            $nama_file_foto = time() . '_' . $file->getClientOriginalName();
            // Pindahkan file ke folder public/storage/products
            $file->move(public_path('storage/products'), $nama_file_foto);
        }

        // Simpan data ke database lewat Model Product
        Product::create([
            'user_id' => Auth::id(), // Mengunci ID Seller yang sedang login
            'category_id' => $request->category_id,
            'nama_produk' => $request->nama_produk,
            'deskripsi' => $request->deskripsi,
            'harga' => $request->harga,
            'stok' => $request->stok,
            'foto' => $nama_file_foto,
            'bank_name' => $request->bank_name,
            'no_rekening' => $request->no_rekening,
            'atas_nama' => $request->atas_nama,
        ]);

        // Kembalikan ke halaman daftar produk dengan pesan sukses
        return redirect()->route('products.index')->with('success', 'Produk berhasil ditambahkan!');
    }

    // 4. EDIT: Menampilkan form edit dengan data produk lama
    public function edit(string $id)
    {
        $product = Product::where('id', $id)->where('user_id', \Auth::id())->firstOrFail();
        $categories = \App\Models\Category::all(); 
        
        return view('seller.products.edit', compact('product', 'categories'));
    }

    // 5. UPDATE: Memproses perubahan data produk di database
    public function update(Request $request, string $id)
    {
        $product = Product::where('id', $id)->where('user_id', \Auth::id())->firstOrFail();

        $request->validate([
            'category_id' => 'required|exists:categories,id',
            'nama_produk' => 'required|string|max:255',
            'deskripsi' => 'required|string',
            'harga' => 'required|numeric|min:0',
            'stok' => 'required|numeric|min:0',
            'foto' => 'nullable|image|mimes:jpeg,png,jpg,webp|max:2048',
            'bank_name' => 'nullable|string|max:255',
            'no_rekening' => 'nullable|string|max:255',
            'atas_nama' => 'nullable|string|max:255',
        ]);

        $nama_file_foto = $product->foto; 

        if ($request->hasFile('foto')) {
            if ($product->foto && file_exists(public_path('storage/products/' . $product->foto))) {
                unlink(public_path('storage/products/' . $product->foto));
            }

            $file = $request->file('foto');
            $nama_file_foto = time() . '_' . $file->getClientOriginalName();
            $file->move(public_path('storage/products'), $nama_file_foto);
        }

        $product->update([
            'category_id' => $request->category_id,
            'nama_produk' => $request->nama_produk,
            'deskripsi' => $request->deskripsi,
            'harga' => $request->harga,
            'stok' => $request->stok,
            'foto' => $nama_file_foto,
            'bank_name' => $request->bank_name,
            'no_rekening' => $request->no_rekening,
            'atas_nama' => $request->atas_nama,
        ]);

        return redirect()->route('products.index')->with('success', 'Produk berhasil diperbarui!');
    }

    // 6. DELETE: Menghapus produk dari database beserta fotonya
    public function destroy(string $id)
    {
        $product = Product::where('id', $id)->where('user_id', \Auth::id())->firstOrFail();

        if ($product->foto && file_exists(public_path('storage/products/' . $product->foto))) {
            unlink(public_path('storage/products/' . $product->foto));
        }

        $product->delete();

        return redirect()->route('products.index')->with('success', 'Produk berhasil dihapus!');
    }
}