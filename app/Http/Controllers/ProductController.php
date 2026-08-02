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
        $user = Auth::user();
        // Hanya mengambil produk yang diinput oleh user/seller yang sedang login saat ini
        $products = Product::where('user_id', $user->id)->get();
        // Mengambil riwayat penarikan dana
        $withdrawals = \App\Models\Withdrawal::where('user_id', $user->id)->latest()->get();
        return view('seller.products.index', compact('products', 'withdrawals', 'user'));
    }

    // 2. CREATE: Menampilkan form untuk menambahkan produk baru
    public function create()
    {
        if (Auth::user()->role === 'seller' && Auth::user()->seller_status !== 'approved') {
            return redirect()->route('dashboard')->with('error', 'Akun seller Anda masih belum disetujui Admin. Anda belum dapat menambahkan produk.');
        }

        $categories = \App\Models\Category::all(); // Pastikan ambil data dari model
        return view('seller.products.create', compact('categories'));
    }

    // 3. STORE: Logika untuk memproses dan menyimpan data produk baru ke database
    public function store(Request $request)
    {
        if (Auth::user()->role === 'seller' && Auth::user()->seller_status !== 'approved') {
            return redirect()->route('dashboard')->with('error', 'Akun seller Anda masih belum disetujui Admin. Anda belum dapat menambahkan produk.');
        }
        // Validasi inputan form
        $request->validate([
            'category_id' => 'required|exists:categories,id',
            'nama_produk' => 'required|string|max:255',
            'deskripsi' => 'required|string',
            'harga' => 'required|numeric|min:0',
            'stok' => 'required|numeric|min:0',
            'foto' => 'nullable|array|max:10',
            'foto.*' => 'image|mimes:jpeg,png,jpg,webp|max:2048', // Maksimal 2MB per foto
            'bank_name' => 'nullable|string|max:255',
            'no_rekening' => 'nullable|string|max:255',
            'atas_nama' => 'nullable|string|max:255',
        ]);

        $foto_paths = [];
        // Logika jika seller mengunggah batch foto produk
        if ($request->hasFile('foto')) {
            foreach ($request->file('foto') as $file) {
                // Membuat nama file unik berdasarkan waktu agar tidak bentrok
                $nama_file_foto = time() . '_' . uniqid() . '_' . $file->getClientOriginalName();
                // Pindahkan file ke folder public/storage/products
                $file->move(public_path('storage/products'), $nama_file_foto);
                $foto_paths[] = $nama_file_foto;
            }
        }

        // Simpan data ke database lewat Model Product
        Product::create([
            'user_id' => Auth::id(), // Mengunci ID Seller yang sedang login
            'category_id' => $request->category_id,
            'nama_produk' => $request->nama_produk,
            'deskripsi' => $request->deskripsi,
            'harga' => $request->harga,
            'stok' => $request->stok,
            'foto' => !empty($foto_paths) ? $foto_paths : null,
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
            'foto' => 'nullable|array|max:10',
            'foto.*' => 'image|mimes:jpeg,png,jpg,webp|max:2048',
            'bank_name' => 'nullable|string|max:255',
            'no_rekening' => 'nullable|string|max:255',
            'atas_nama' => 'nullable|string|max:255',
        ]);

        $foto_paths = $product->fotos;

        if ($request->hasFile('foto')) {
            // Hapus file-file lama terlebih dahulu
            foreach ($product->fotos as $old_foto) {
                if ($old_foto && file_exists(public_path('storage/products/' . $old_foto))) {
                    unlink(public_path('storage/products/' . $old_foto));
                }
            }

            $foto_paths = [];

            // Simpan file-file baru
            foreach ($request->file('foto') as $file) {
                $name = time() . '_' . uniqid() . '_' . $file->getClientOriginalName();
                $file->move(public_path('storage/products'), $name);
                $foto_paths[] = $name;
            }
        }

        $product->update([
            'category_id' => $request->category_id,
            'nama_produk' => $request->nama_produk,
            'deskripsi' => $request->deskripsi,
            'harga' => $request->harga,
            'stok' => $request->stok,
            'foto' => !empty($foto_paths) ? $foto_paths : null,
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

        foreach ($product->fotos as $foto) {
            if ($foto && file_exists(public_path('storage/products/' . $foto))) {
                unlink(public_path('storage/products/' . $foto));
            }
        }

        $product->delete();

        return redirect()->route('products.index')->with('success', 'Produk berhasil dihapus!');
    }
}