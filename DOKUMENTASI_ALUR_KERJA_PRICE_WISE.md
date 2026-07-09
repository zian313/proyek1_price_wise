# 📱 DOKUMENTASI ALUR KERJA SISTEM PRICE-WISE

**Tanggal**: 8 Juli 2026  
**Platform**: Marketplace Jual-Beli Barang Bekas  
**Versi**: 1.0

---

## 📑 DAFTAR ISI

1. [Pengenalan Sistem](#pengenalan-sistem)
2. [Struktur Folder & Database](#struktur-folder--database)
3. [Alur Kerja Lengkap](#alur-kerja-lengkap)
4. [Fase-Fase Transaksi](#fase-fase-transaksi)
5. [Alur Buyer](#alur-buyer)
6. [Alur Seller](#alur-seller)
7. [Alur Admin](#alur-admin)
8. [Status Order](#status-order)
9. [Security & Validation](#security--validation)
10. [File-File Penting](#file-file-penting)

---

## 🎯 Pengenalan Sistem

### **Apa itu Price-Wise?**

Price-Wise adalah aplikasi marketplace berbasis web untuk jual-beli barang bekas dengan sistem verifikasi pembayaran oleh admin. Sistem ini melibatkan 3 peran utama:

- **BUYER** (Pembeli): Mencari dan membeli barang
- **SELLER** (Penjual): Menjual barang bekas mereka
- **ADMIN** (Moderator): Memverifikasi pembayaran dan menjaga integritas transaksi

### **Teknologi**

- **Framework**: Laravel 11
- **Database**: MySQL
- **Frontend**: Blade Templates + Tailwind CSS
- **Build Tool**: Vite + esbuild

---

## 🗂️ Struktur Folder & Database

### **Struktur Folder Utama**

```
price-wise/
├── app/
│   ├── Http/
│   │   ├── Controllers/
│   │   │   ├── ProductController.php      (Seller: CRUD Produk)
│   │   │   ├── TransactionController.php  (Buyer: Checkout, Payment, History)
│   │   │   ├── AdminController.php        (Admin: Verifikasi)
│   │   │   └── AdminDashboardController.php (Dashboard Admin)
│   │   ├── Middleware/
│   │   └── Requests/
│   ├── Models/
│   │   ├── User.php            (role: buyer/seller/admin)
│   │   ├── Product.php         (Produk dari Seller)
│   │   ├── Order.php           (Order dari Buyer)
│   │   ├── OrderDetail.php     (Rincian Produk dalam Order)
│   │   └── Category.php        (Kategori Produk)
│   └── Providers/
├── routes/
│   ├── web.php                 (Route Utama)
│   └── auth.php                (Auth Routes)
├── database/
│   ├── migrations/
│   │   ├── create_users_table.php
│   │   ├── create_categories_table.php
│   │   ├── create_products_table.php
│   │   ├── create_orders_table.php
│   │   └── create_order_details_table.php
│   └── seeders/
├── resources/
│   ├── views/
│   │   ├── dashboard.blade.php
│   │   ├── buyer/
│   │   ├── seller/
│   │   └── admin/
│   ├── css/
│   └── js/
└── public/
    └── storage/
        ├── products/           (Foto Produk)
        └── bukti_transfer/     (Bukti Transfer Pembayaran)
```

### **Struktur Database**

#### **1. Tabel: users**
```sql
id, name, email, password, role (buyer/seller/admin), 
bank_name, no_rekening, atas_nama, created_at
```

#### **2. Tabel: categories**
```sql
id, nama_kategori, deskripsi, created_at
```

#### **3. Tabel: products**
```sql
id, user_id (FK→users), category_id (FK→categories),
nama_produk, deskripsi, harga, stok, foto,
bank_name, no_rekening, atas_nama, created_at, updated_at
```

#### **4. Tabel: orders**
```sql
id, user_id (FK→users, Buyer), total_harga, status,
bukti_transfer, nama, email, alamat, ekspedisi,
metode_pembayaran, created_at, updated_at
```

Status: menunggu_pembayaran | menunggu_verifikasi | lunas | dibatalkan | selesai

#### **5. Tabel: order_details**
```sql
id, order_id (FK→orders), product_id (FK→products),
jumlah, harga_saat_beli, created_at, updated_at
```

---

## 🔄 Alur Kerja Lengkap

```
┌─────────────────────────────────────────────────────────────┐
│                     REGISTRASI & LOGIN                       │
│                   User Pilih Role: Buyer/Seller/Admin       │
└────────────────┬────────────────────────────────────────────┘
                 ↓
    ┌────────────────────────┬──────────────────┐
    ↓                        ↓                  ↓
┌─────────┐          ┌──────────────┐    ┌─────────┐
│ BUYER   │          │ SELLER       │    │ ADMIN   │
│         │          │              │    │         │
└────┬────┘          └──────┬───────┘    └────┬────┘
     │                      │                  │
     │                      ↓                  │
     │            UPLOAD PRODUK                │
     │            (form + foto)                │
     │                      │                  │
     │                      ↓                  │
     │            SIMPAN KE DATABASE           │
     │            (products tabel)             │
     │                      │                  │
     ↓                      ↓                  ↓
BROWSE & FILTER    LIHAT PESANAN      VERIFIKASI
PRODUK             MASUK              PEMBAYARAN
     │                      │                  │
     ↓                      ↓                  ↓
KLIK BELI          TUNGGU ADMIN      CHECK BUKTI
     │                      │          APPROVE/REJECT
     ↓                      ↓                  │
CHECKOUT FORM             ↑                   │
(nama, alamat,     (kurangi stok)             │
 ekspedisi)               ↑                    │
     │                      │                  ↓
     ↓                      ↓            UPDATE STATUS
SIMPAN ORDER       KIRIM BARANG      (lunas/dibatalkan)
(orders tabel)             │                  │
     │                      │                  │
     ↓                      │                  │
UPLOAD BUKTI       TUNGGU PEMBELI      ← → KOMUNIKASI
TRANSFER           TERIMA BARANG       DENGAN BUYER
     │                      │
     ↓                      │
UPDATE STATUS              │
(menunggu_verifikasi)      │
     │                      │
     └──────────┬───────────┘
                ↓
         BUYER CONFIRM
         TERIMA BARANG
                ↓
         STATUS: SELESAI ✅
```

---

## 📋 Fase-Fase Transaksi

### **FASE 1: REGISTRASI & LOGIN**

| Langkah | Aksi | Output |
|---------|------|--------|
| 1 | User kunjungi halaman register | Form input email, password |
| 2 | User pilih role (buyer/seller/admin) | Tersimpan di `users.role` |
| 3 | User login | Session aktif, redirect ke dashboard |
| 4 | Middleware auth check | Hanya user yang login bisa akses |

---

### **FASE 2: SELLER UPLOAD PRODUK**

**Route**: `GET /products/create` → `POST /products`  
**Controller**: `ProductController`

#### **Step 2.1: Buka Form Upload**
```
GET /products/create
├── ProductController::create()
├── Ambil semua kategori dari DB
└── Return: seller.products.create (form)

Form Input:
├── Nama Produk
├── Kategori (dropdown)
├── Harga
├── Stok
├── Deskripsi
├── Foto Produk (image upload, max 2MB)
├── Bank Name (opsional)
├── No Rekening (opsional)
└── Atas Nama (opsional)
```

#### **Step 2.2: Proses Submit**
```
POST /products
├── ProductController::store()
├── Validasi semua input
├── Jika ada file foto:
│  ├── Generate nama unik: {timestamp}_{filename}
│  └── Simpan ke: public/storage/products/
├── DB Transaction:
│  ├── INSERT ke tabel: products
│  │  ├── user_id = ID Seller (Auth::id())
│  │  ├── category_id
│  │  ├── nama_produk, harga, stok, deskripsi
│  │  ├── foto (nama file)
│  │  └── bank_name, no_rekening, atas_nama
│  └── Jika error → Rollback otomatis
└── Return: Redirect ke /products dengan success message
```

#### **Step 2.3: Produk Tersedia di Marketplace**
```
Dashboard Buyer:
├── GET /dashboard
├── Query: SELECT * FROM products WHERE stok > 0
├── Ambil relasi: category, user (seller)
└── Tampilkan dalam grid: foto, nama, harga, seller
```

---

### **FASE 3: BUYER BERBELANJA**

**Route**: `GET /checkout/{id}` → `POST /checkout/store/{id}` → `GET /orders/{id}/payment` → `POST /orders/{id}/pay`  
**Controller**: `TransactionController`

#### **Step 3.1: Browse & Lihat Detail Produk**
```
GET /dashboard
├── Buyer lihat daftar produk
├── Filter by kategori
└── Klik produk yang ingin dibeli
```

#### **Step 3.2: Halaman Checkout**
```
GET /checkout/{product_id}
├── TransactionController::checkout()
├── Query produk by ID
├── Validasi: stok > 0
└── Return: buyer.checkout (form)

Form Checkout:
├── Nama Pembeli
├── Email
├── Alamat Pengiriman
├── Pilih Ekspedisi (dropdown)
├── Metode Pembayaran
├── Display: Foto produk, harga, total
└── Tombol: LANJUT KE PEMBAYARAN
```

#### **Step 3.3: Proses Checkout**
```
POST /checkout/store/{product_id}
├── TransactionController::storeTransaction()
├── Validasi semua input form
├── DB Transaction (PENTING):
│  ├── INSERT ke tabel: orders
│  │  ├── user_id = ID Buyer (Auth::id())
│  │  ├── total_harga = harga produk
│  │  ├── status = "menunggu_pembayaran"
│  │  ├── nama, email, alamat, ekspedisi, metode_pembayaran
│  │  └── bukti_transfer = NULL (belum upload)
│  ├── INSERT ke tabel: order_details
│  │  ├── order_id = ID order yang baru dibuat
│  │  ├── product_id
│  │  ├── jumlah = 1
│  │  └── harga_saat_beli = harga produk saat checkout
│  └── Jika error → Rollback otomatis
└── Return: Redirect ke halaman pembayaran
```

#### **Step 3.4: Halaman Pembayaran**
```
GET /orders/{order_id}/payment
├── TransactionController::payment()
├── Validasi: order milik buyer yang login
├── Validasi: status = "menunggu_pembayaran"
├── Query order dengan relasi orderDetails & product
└── Return: buyer.payment (form upload bukti)

Halaman Pembayaran Display:
├── Rincian Order (produk, harga, total)
├── Data Seller (nama, bank, no rekening, atas nama)
├── Instruksi: Transfer ke rekening seller sebesar {total}
├── Form Upload Bukti Transfer:
│  ├── File input (image: jpg, png, webp)
│  ├── Max size: 2MB
│  └── Tombol: UPLOAD BUKTI
└── Status saat ini: MENUNGGU PEMBAYARAN
```

#### **Step 3.5: Proses Upload Bukti Transfer**
```
POST /orders/{order_id}/pay
├── TransactionController::pay()
├── Validasi: order milik buyer yang login
├── Validasi file:
│  ├── Harus ada file
│  ├── Type: image (jpeg, png, jpg, webp)
│  └── Size: max 2048KB (2MB)
├── Upload ke: public/storage/bukti_transfer/
│  └── Nama file: bukti_{timestamp}_{filename}
├── UPDATE tabel orders:
│  ├── status = "menunggu_verifikasi"
│  └── bukti_transfer = nama file yang diupload
└── Return: Redirect ke /orders/history dengan success msg

⚠️ PENTING:
├── Stok BELUM dikurangi pada tahap ini
├── Hanya berkurang setelah admin approve
└── Buyer status: MENUNGGU VERIFIKASI ADMIN
```

#### **Step 3.6: Lihat Riwayat Pembelian**
```
GET /orders/history
├── TransactionController::history()
├── Query: SELECT * FROM orders WHERE user_id = Auth::id()
├── Ambil relasi: orderDetails, product
├── Order by: latest first
└── Tampilkan dalam tabel:
    ├── Tanggal pemesanan
    ├── Nama produk
    ├── Total harga
    ├── Status order (dengan badge)
    └── Tombol: Lihat Detail
```

---

### **FASE 4: SELLER LIHAT PESANAN MASUK**

**Route**: `GET /seller/orders`  
**Controller**: `TransactionController::sellerOrders()`

```
GET /seller/orders
├── Query: SELECT * FROM order_details
├── Filter: WHERE product.user_id = Auth::id() (seller)
├── Ambil relasi: product, order, user (buyer)
├── Order by: latest first
└── Tampilkan dalam tabel:
    ├── Nama produk yang dipesan
    ├── Jumlah
    ├── Harga satuan
    ├── Nama pembeli
    ├── Alamat pengiriman
    ├── Ekspedisi yang dipilih
    ├── Status order
    └── Tombol: Lihat Detail

⚠️ SELLER HANYA MENUNGGU
├── Tidak bisa approve/reject (itu tugas admin)
├── Tunggu sampai status = "lunas"
└── Baru bisa kirim barang
```

---

### **FASE 5: ADMIN VERIFIKASI PEMBAYARAN** ⭐ **KUNCI SISTEM**

**Route**: `GET /admin/dashboard` → `GET /admin/order/{id}` → `POST /admin/order/{id}/verify`  
**Controller**: `AdminController`, `AdminDashboardController`  
**Middleware**: `auth` + `admin` (hanya akses untuk user dengan role='admin')

#### **Step 5.1: Dashboard Admin**
```
GET /admin/dashboard
├── AdminDashboardController::index()
├── Query: SELECT * FROM orders (SEMUA order)
├── Tampilkan dalam tabel:
│  ├── ID Order
│  ├── Nama Buyer
│  ├── Produk yang dibeli
│  ├── Total Harga
│  ├── Status Order
│  ├── Tanggal Pemesanan
│  └── Tombol: LIHAT DETAIL
└── Status yang perlu verifikasi: "menunggu_verifikasi"
```

#### **Step 5.2: Lihat Detail Order**
```
GET /admin/order/{id}
├── AdminController::show()
├── Query order by ID dengan relasi:
│  ├── user (buyer)
│  ├── orderDetails
│  └── orderDetails.product (seller info)
├── Tampilkan:
│  ├── Detail Order:
│  │  ├── Order ID
│  │  ├── Tanggal pemesanan
│  │  ├── Total harga
│  │  └── Status saat ini
│  ├── Data Pembeli (Buyer):
│  │  ├── Nama
│  │  ├── Email
│  │  ├── Alamat pengiriman
│  │  ├── Ekspedisi
│  │  └── Metode pembayaran
│  ├── Data Produk:
│  │  ├── Nama produk
│  │  ├── Harga
│  │  ├── Jumlah
│  │  └── Total
│  ├── Data Penjual (Seller):
│  │  ├── Nama seller
│  │  ├── Bank name
│  │  ├── No rekening
│  │  └── Atas nama
│  ├── Bukti Transfer:
│  │  ├── Display image dari public/storage/bukti_transfer/
│  │  └── Tautan download
│  └── Tombol Aksi:
│     ├── APPROVE (ubah status → lunas)
│     └── REJECT (ubah status → dibatalkan)
└── Admin CEK BUKTI:
   ├── Bandingkan nominal dengan total harga
   ├── Cek nama rekening dengan atas_nama produk
   └── Validasi bukti authentic (tidak palsu)
```

#### **Step 5.3: Admin Approve**
```
POST /admin/order/{id}/verify
├── AdminController::verify()
├── Validasi: Request body { status: "lunas" atau "dibatalkan" }
├── Cek: Order ditemukan
├── DB Transaction (SANGAT PENTING - ATOMIC):
│  │
│  ├─ IF status = "lunas":
│  │  ├─ UPDATE orders: status = "lunas"
│  │  ├─ LOOP semua order_details dalam order ini:
│  │  │  └─ UNTUK SETIAP PRODUK:
│  │  │     ├─ Cek: stok produk >= jumlah yang dipesan
│  │  │     ├─ UPDATE products:
│  │  │     │  └─ stok = stok - order_detail.jumlah
│  │  │     └─ Jika stok tidak cukup:
│  │  │        └─ THROW Exception
│  │  │           (Semua transaksi rollback)
│  │  │
│  │  └─ SUCCESS: Transaksi berubah menjadi LUNAS ✅
│  │     ├─ Stok sudah dikurangi
│  │     ├─ Seller bisa kirim barang
│  │     └─ Buyer bisa lihat di riwayat
│  │
│  ├─ ELSE IF status = "dibatalkan":
│  │  ├─ UPDATE orders: status = "dibatalkan"
│  │  ├─ Stok TIDAK dikurangi
│  │  ├─ Transaksi batal
│  │  └─ Buyer bisa mencoba order ulang
│  │
│  └─ Jika terjadi error di tengah proses:
│     └─ ROLLBACK OTOMATIS (semua perubahan dibatalkan)
│
└── Return: Success/Error message
```

**⚠️ PENJELASAN DETAIL APPROVAL:**

```
Kenapa Stok Dikurangi saat Admin Approve?
├─ Untuk mencegah overselling (penjualan lebih dari stok)
├─ Saat checkout, stok masih utuh (buyer hanya reserve)
├─ Hanya saat approval, stok benar-benar berkurang
└─ Ini memastikan integritas data

Contoh Skenario:
├─ Produk A: stok = 5
├─ Buyer 1 order: 3 unit → stok masih 5 (menunggu verifikasi)
├─ Buyer 2 order: 2 unit → stok masih 5 (menunggu verifikasi)
├─ Admin approve Buyer 1 → stok = 5 - 3 = 2 ✅
├─ Admin approve Buyer 2 → stok = 2 - 2 = 0 ✅
└─ Buyer 3 tidak bisa order (stok habis) ✅

Jika tidak ada Admin Verifikasi:
├─ Buyer 1 & 2 sama-sama approve sendiri
├─ Stok = 5 - 3 - 2 = 0 (normal)
└─ Tapi pembayaran tidak diverifikasi (rawan fraud)
```

---

### **FASE 6: SETELAH APPROVED (Status = "LUNAS")**

**Route**: `GET /orders/history` + `POST /orders/{id}/confirm-receipt`  
**Controller**: `TransactionController`

#### **Step 6.1: Buyer Lihat Status Lunas**
```
GET /orders/history
├── Status berubah: MENUNGGU VERIFIKASI → LUNAS ✅
├── Tampilkan dalam riwayat:
│  ├── Nama produk
│  ├── Total harga
│  ├── Status: LUNAS
│  └── Tombol: KONFIRMASI PENERIMAAN (hanya jika status=lunas)
└── Notification/Alert: "Pembayaran diverifikasi, barang siap dikirim"
```

#### **Step 6.2: Seller Kirim Barang**
```
Seller Action (di luar sistem):
├─ Lihat di /seller/orders (status sudah berubah ke lunas)
├─ Ambil barang dari gudang
├─ Siapkan paket
├─ Hubungi kurir (JNE/Tiki/Pos)
├─ Input no resi pengiriman
├─ Catat untuk referensi
└─ Kirim ke alamat pembeli
```

#### **Step 6.3: Barang Sampai ke Pembeli**
```
Buyer Terima Barang:
├─ Barang sampai via kurir
├─ Terima dari kurir
├─ Cek kondisi dan kesesuaian dengan deskripsi
├─ Jika sesuai → Lanjut ke step 6.4
└─ Jika tidak sesuai → Hubungi seller (chat/WA)
```

#### **Step 6.4: Buyer Konfirmasi Penerimaan**
```
POST /orders/{order_id}/confirm-receipt
├── TransactionController::confirmReceipt()
├── Validasi:
│  ├─ Order ditemukan
│  ├─ Order milik buyer yang login
│  └─ Status order = "lunas" (hanya boleh confirm jika lunas)
├── DB Transaction:
│  ├─ UPDATE orders: status = "selesai"
│  └─ Jika error → Rollback
└── Return: Success message + Redirect ke /orders/history

Status Akhir: SELESAI ✅

Transaksi Lengkap:
├─ Pembeli dapatkan barang
├─ Penjual dapatkan uang (dalam sistem)
├─ Admin verifikasi berhasil
└─ Marketplace dapat komisi (optional)
```

---

## 👤 Alur Buyer (Pembeli)

### **User Journey Buyer**

```
1. REGISTRASI
   └─ Daftar akun
   └─ Pilih role: BUYER
   └─ Set email & password

2. LOGIN
   └─ Masuk ke dashboard

3. BROWSE PRODUK
   └─ GET /dashboard
   └─ Lihat semua produk
   └─ Filter by kategori
   └─ Lihat harga & foto

4. PILIH PRODUK
   └─ Klik produk yang menarik
   └─ Baca deskripsi & detail

5. CHECKOUT
   └─ GET /checkout/{product_id}
   └─ Isi form: nama, email, alamat, ekspedisi, metode pembayaran
   └─ POST /checkout/store/{product_id}
   └─ Order tersimpan

6. PEMBAYARAN
   └─ GET /orders/{order_id}/payment
   └─ Lihat instruksi transfer bank
   └─ Lihat rekening seller
   └─ Transfer uang via mobile banking
   └─ Screenshot/foto bukti transfer

7. UPLOAD BUKTI
   └─ POST /orders/{order_id}/pay
   └─ Upload bukti transfer
   └─ Status: MENUNGGU VERIFIKASI

8. TUNGGU ADMIN
   └─ GET /orders/history
   └─ Lihat status order
   └─ Status: MENUNGGU VERIFIKASI
   └─ Tunggu admin cek bukti

9. BARANG SIAP DIKIRIM
   └─ Status berubah: LUNAS ✅
   └─ Seller siap kirim barang
   └─ Tunggu barang tiba

10. TERIMA BARANG
    └─ Barang sampai via kurir
    └─ Cek kondisi & kesesuaian
    └─ Jika OK, konfirmasi

11. KONFIRMASI PENERIMAAN
    └─ POST /orders/{order_id}/confirm-receipt
    └─ Klik tombol "Konfirmasi Penerimaan"
    └─ Status: SELESAI ✅
    └─ Transaksi lengkap!

12. RIWAYAT PEMBELIAN
    └─ GET /orders/history
    └─ Lihat semua order
    └─ Download bukti/invoice (opsional)
```

### **Fitur Buyer**

| Fitur | Route | Method | Deskripsi |
|-------|-------|--------|-----------|
| Browse Produk | `/dashboard` | GET | Lihat semua produk |
| Checkout | `/checkout/{id}` | GET/POST | Buat order baru |
| Pembayaran | `/orders/{id}/payment` | GET | Lihat instruksi transfer |
| Upload Bukti | `/orders/{id}/pay` | POST | Upload bukti transfer |
| Riwayat | `/orders/history` | GET | Lihat semua order |
| Konfirmasi | `/orders/{id}/confirm-receipt` | POST | Konfirmasi terima barang |
| Profile | `/profile` | GET/PATCH/DELETE | Edit profil |

---

## 👨‍💼 Alur Seller (Penjual)

### **User Journey Seller**

```
1. REGISTRASI
   └─ Daftar akun
   └─ Pilih role: SELLER
   └─ Set email & password

2. LOGIN
   └─ Masuk ke dashboard
   └─ Dashboard seller (tidak sama dengan buyer)

3. SETUP PRODUK
   └─ GET /products/create
   └─ Isi form: kategori, nama, harga, stok, foto, dsb
   └─ Opsional: Setup bank (bank_name, no_rekening, atas_nama)

4. UPLOAD PRODUK
   └─ POST /products
   └─ Upload foto produk (max 2MB)
   └─ Foto simpan ke public/storage/products/
   └─ Data produk simpan ke tabel products

5. KELOLA PRODUK
   └─ GET /products
   └─ Lihat daftar produk milik sendiri
   └─ Edit produk: /products/{id}/edit
   └─ Hapus produk: DELETE /products/{id}

6. LIHAT PESANAN MASUK
   └─ GET /seller/orders
   └─ Lihat order_details yang produknya milik sendiri
   └─ Lihat: nama pembeli, alamat, ekspedisi, jumlah, harga

7. TUNGGU ADMIN VERIFIKASI
   └─ Status order: MENUNGGU VERIFIKASI
   └─ Admin yang cek bukti transfer
   └─ Seller tidak perlu aksi apapun

8. PERSIAPAN PENGIRIMAN
   └─ Status berubah: LUNAS ✅
   └─ Ambil barang dari gudang
   └─ Siapkan paket rapi
   └─ Catat detail packing

9. PENGIRIMAN
   └─ Hubungi kurir (sesuai ekspedisi yang dipilih buyer)
   └─ Input data penerima dari order
   └─ Terima no resi dari kurir
   └─ Catat untuk referensi

10. TUNGGU PEMBELI TERIMA
    └─ Barang dalam perjalanan
    └─ Pembeli akan konfirmasi penerimaan
    └─ Transaksi selesai

11. TRANSAKSI SELESAI
    └─ Status order: SELESAI ✅
    └─ Seller dapat komisi/nilai jual
    └─ Lihat di riwayat penjualan (opsional)
```

### **Fitur Seller**

| Fitur | Route | Method | Deskripsi |
|-------|-------|--------|-----------|
| Buat Produk | `/products/create` | GET | Form input produk |
| Simpan Produk | `/products` | POST | Simpan produk baru |
| Lihat Produk | `/products` | GET | Daftar produk milik |
| Edit Produk | `/products/{id}/edit` | GET | Form edit produk |
| Update Produk | `/products/{id}` | PATCH | Update data produk |
| Hapus Produk | `/products/{id}` | DELETE | Hapus produk |
| Pesanan Masuk | `/seller/orders` | GET | Lihat order yang masuk |
| Profile | `/profile` | GET/PATCH/DELETE | Edit profil |

---

## 👨‍⚖️ Alur Admin (Moderator/Verifikator)

### **User Journey Admin**

```
1. REGISTRASI (Dibuat manual oleh Developer)
   └─ Buat user dengan role='admin'
   └─ Set email & password
   └─ Akses admin panel

2. LOGIN
   └─ Masuk ke dashboard admin

3. DASHBOARD ADMIN
   └─ GET /admin/dashboard
   └─ Lihat SEMUA order dari semua buyer/seller
   └─ Tampilan tabel dengan status ORDER

4. FILTER ORDER YANG PERLU VERIFIKASI
   └─ Cari order dengan status: MENUNGGU_VERIFIKASI
   └─ Klik "LIHAT DETAIL"

5. VERIFIKASI PEMBAYARAN
   └─ GET /admin/order/{id}
   └─ Tampilkan:
      ├─ Detail lengkap order
      ├─ Data pembeli (nama, alamat, email)
      ├─ Data penjual (nama, bank, rekening)
      ├─ Produk yang dipesan
      ├─ Total harga yang seharusnya dibayar
      └─ Foto bukti transfer dari pembeli

6. CEK BUKTI TRANSFER
   └─ Admin buka file bukti_transfer (display image)
   └─ Bandingkan:
      ├─ Nominal dalam bukti = total harga? ✓
      ├─ Nama rekening = atas_nama seller? ✓
      ├─ Bukti terlihat authentic (bukan palsu)? ✓
      └─ Tanggal transfer masuk akal? ✓

7. APPROVE ATAU REJECT
   └─ Jika VALID:
      ├─ POST /admin/order/{id}/verify
      ├─ Request: { status: "lunas" }
      ├─ DB Transaction:
      │  ├─ UPDATE orders: status="lunas"
      │  ├─ LOOP order_details: Kurangi stok produk
      │  └─ Jika error: Rollback semua
      └─ Status order: LUNAS ✅
      
   └─ Jika TIDAK VALID (palsu, nominal tidak cocok, dsb):
      ├─ POST /admin/order/{id}/verify
      ├─ Request: { status: "dibatalkan" }
      ├─ UPDATE orders: status="dibatalkan"
      ├─ Stok tidak dikurangi
      ├─ Transaksi batal
      └─ Buyer bisa order ulang

8. MONITORING TRANSAKSI
   └─ GET /admin/dashboard
   └─ Refresh halaman
   └─ Monitor semua transaksi real-time
   └─ Lihat statistik:
      ├─ Total order masuk
      ├─ Order menunggu verifikasi
      ├─ Order yang sudah lunas
      └─ Order yang dibatalkan

9. REPORT & ANALYTICS (Opsional)
   └─ Lihat laporan penjualan
   └─ Lihat seller top
   └─ Lihat total transaksi
   └─ Download report ke Excel/PDF
```

### **Fitur Admin**

| Fitur | Route | Method | Deskripsi |
|-------|-------|--------|-----------|
| Dashboard | `/admin/dashboard` | GET | Lihat semua order |
| Lihat Detail | `/admin/order/{id}` | GET | Detail order dengan bukti |
| Approve/Reject | `/admin/order/{id}/verify` | POST | Verifikasi pembayaran |

### **Tanggung Jawab Admin**

```
✓ Verifikasi bukti pembayaran
✓ Bandingkan nominal uang
✓ Cek keaslian bukti
✓ Approve order (ubah status → lunas)
✓ Reject order jika tidak valid
✓ Trigger pengurangan stok saat approve
✓ Monitoring transaksi real-time
✓ Handle dispute/komplain (opsional)
✓ Generate laporan penjualan (opsional)

✗ TIDAK boleh:
  ├─ Memodifikasi harga order
  ├─ Membuat order sendiri
  ├─ Upload produk
  ├─ Mengubah data buyer/seller tanpa alasan
  └─ Approve tanpa verifikasi bukti
```

---

## 📊 Status Order

### **Status Lifecycle**

```
1. MENUNGGU_PEMBAYARAN
   └─ Order baru dibuat dari checkout
   └─ Buyer belum upload bukti transfer
   └─ Aksi: Buyer upload bukti transfer
   
2. MENUNGGU_VERIFIKASI
   └─ Buyer sudah upload bukti transfer
   └─ Admin belum cek & approve
   └─ Aksi: Admin verifikasi & approve/reject
   
3. LUNAS (jika approved)
   └─ Admin verifikasi pembayaran valid
   └─ Stok produk sudah dikurangi
   └─ Seller siap kirim barang
   └─ Aksi: Seller kirim barang, Buyer terima & konfirmasi
   
   ATAU

3. DIBATALKAN (jika rejected)
   └─ Admin verifikasi pembayaran tidak valid
   └─ Stok tidak dikurangi
   └─ Transaksi batal
   └─ Aksi: Buyer bisa order ulang
   
4. SELESAI
   └─ Buyer sudah konfirmasi penerimaan barang
   └─ Transaksi lengkap
   └─ Status final (tidak bisa berubah lagi)
```

### **Diagram Status**

```
[MENUNGGU_PEMBAYARAN]
         ↓
      (upload bukti)
         ↓
[MENUNGGU_VERIFIKASI]
    ↙             ↘
(approve)      (reject)
  ↓                ↓
[LUNAS]      [DIBATALKAN]
  ↓             (transaksi batal)
(kirim & terima)
  ↓
(konfirmasi penerimaan)
  ↓
[SELESAI] ← FINAL STATUS
```

---

## 🔐 Security & Validation

### **Buyer Security**

```
✓ Middleware: auth
  └─ Hanya user yang login bisa checkout

✓ Validasi Order Ownership
  └─ Cek: order.user_id == Auth::id()
  └─ Mencegah buyer lihat/edit order orang lain

✓ Validasi Status Order
  └─ Cek status sebelum payment/confirm
  └─ Mencegah aksi di status yang salah

✓ File Upload Validation
  └─ Type: image (jpg, png, jpeg, webp)
  └─ Size: max 2MB
  └─ Mencegah upload file berbahaya

✓ Input Validation
  └─ Semua input dari form divalidasi
  └─ Required fields: nama, email, alamat, ekspedisi, metode

✓ DB Transaction
  └─ Jika gagal, semua perubahan rollback
  └─ Mencegah data inconsistency
```

### **Seller Security**

```
✓ Middleware: auth
  └─ Hanya seller yang login bisa upload produk

✓ Validasi Product Ownership
  └─ Cek: product.user_id == Auth::id()
  └─ Seller hanya bisa edit/delete produk milik sendiri

✓ File Upload Validation (Photo)
  └─ Type: image (jpg, png, jpeg, webp)
  └─ Size: max 2MB

✓ Input Validation
  └─ category_id harus valid (exists di DB)
  └─ harga & stok: numeric & min 0
  └─ nama_produk & deskripsi: required

✓ Stok Protection
  └─ Stok baru berkurang saat admin approve
  └─ Mencegah overselling (jual lebih dari stok)

✓ View Restriction
  └─ /seller/orders: hanya lihat order produk milik sendiri
```

### **Admin Security**

```
✓ Middleware: auth + admin
  └─ Hanya user dengan role='admin' bisa akses

✓ Verifikasi Bukti Transfer
  └─ Admin HARUS cek bukti sebelum approve
  └─ Bandingkan nominal & nama rekening
  └─ Mencegah fraud

✓ DB Transaction pada Approval
  └─ UPDATE order & stok dalam satu transaksi
  └─ Jika error, semua rollback
  └─ Mencegah data inconsistency

✓ Audit Log (Optional)
  └─ Catat setiap aksi admin
  └─ Untuk tracking & accountability
```

### **General Security**

```
✓ Password Hashing
  └─ Password di-hash sebelum simpan ke DB

✓ Session Management
  └─ Session timeout
  └─ Logout menghapus session

✓ CSRF Protection
  └─ Laravel automatic CSRF token
  └─ POST/PATCH/DELETE request harus include token

✓ SQL Injection Prevention
  └─ Laravel Eloquent ORM (parameterized queries)
  └─ Protection built-in

✓ XSS Prevention
  └─ Laravel Blade template escaping
  └─ {{ }} auto-escape HTML entities
```

---

## 📁 File-File Penting

### **Controllers**

#### **ProductController.php**
```
Fungsi:
├─ index()    → GET /products (tampilkan produk milik seller)
├─ create()   → GET /products/create (form input produk)
├─ store()    → POST /products (simpan produk ke DB)
├─ edit()     → GET /products/{id}/edit (form edit produk)
├─ update()   → PATCH /products/{id} (update produk)
└─ destroy()  → DELETE /products/{id} (hapus produk)

File Upload Handling:
├─ Validasi: image, jpg/png/webp, max 2MB
├─ Generate nama: {timestamp}_{filename}
└─ Simpan ke: public/storage/products/
```

#### **TransactionController.php**
```
Fungsi Buyer:
├─ checkout()             → GET /checkout/{id}
├─ storeTransaction()     → POST /checkout/store/{id}
├─ payment()              → GET /orders/{id}/payment
├─ pay()                  → POST /orders/{id}/pay
├─ history()              → GET /orders/history
└─ confirmReceipt()       → POST /orders/{id}/confirm-receipt

Fungsi Seller:
└─ sellerOrders()         → GET /seller/orders

Key Logic:
├─ DB Transaction untuk atomicity
├─ Validasi status order sebelum aksi
├─ File upload dengan nama unik
├─ Order ownership validation
```

#### **AdminController.php**
```
Fungsi:
├─ show()     → GET /admin/order/{id} (detail order dengan bukti)
└─ verify()   → POST /admin/order/{id}/verify (approve/reject)

Key Logic:
├─ DB Transaction untuk atomicity
├─ UPDATE orders status
├─ Kurangi stok saat approve
├─ Validasi status & ownership
└─ Error handling & rollback
```

#### **AdminDashboardController.php**
```
Fungsi:
└─ index()    → GET /admin/dashboard (tampilkan semua order)

Display:
├─ Tabel semua order
├─ Info pembeli, produk, harga
├─ Status order
└─ Tombol aksi
```

### **Models**

#### **User.php**
```
Relasi:
├─ orders() ─→ hasMany(Order)
├─ products() ─→ hasMany(Product) [seller]
└─ orderDetails() ─→ hasMany(OrderDetail) [optional]

Attribute:
├─ id, name, email, password
├─ role: buyer/seller/admin
├─ bank_name, no_rekening, atas_nama
└─ email_verified_at, created_at, updated_at
```

#### **Product.php**
```
Relasi:
├─ user() ─→ belongsTo(User) [seller]
├─ category() ─→ belongsTo(Category)
├─ orderDetails() ─→ hasMany(OrderDetail)
└─ orders() ─→ hasManyThrough(Order, OrderDetail)

Attribute:
├─ id, user_id, category_id
├─ nama_produk, deskripsi, harga, stok
├─ foto, bank_name, no_rekening, atas_nama
└─ created_at, updated_at
```

#### **Order.php**
```
Relasi:
├─ user() ─→ belongsTo(User) [buyer]
└─ orderDetails() ─→ hasMany(OrderDetail)

Attribute:
├─ id, user_id
├─ total_harga, status
├─ bukti_transfer (nullable)
├─ nama, email, alamat
├─ ekspedisi, metode_pembayaran
└─ created_at, updated_at
```

#### **OrderDetail.php**
```
Relasi:
├─ order() ─→ belongsTo(Order)
└─ product() ─→ belongsTo(Product)

Attribute:
├─ id, order_id, product_id
├─ jumlah, harga_saat_beli
└─ created_at, updated_at
```

### **Routes**

#### **routes/web.php**
```
Public Routes:
└─ GET / (landing page)

Auth Routes:
├─ GET /profile
├─ PATCH /profile
└─ DELETE /profile

Buyer Routes:
├─ GET /dashboard (semua user)
├─ GET /checkout/{product_id}
├─ POST /checkout/store/{product_id}
├─ GET /orders/{order_id}/payment
├─ POST /orders/{order_id}/pay
├─ GET /orders/history
└─ POST /orders/{order_id}/confirm-receipt

Seller Routes:
├─ Resource /products (CRUD)
└─ GET /seller/orders

Admin Routes:
├─ GET /admin/dashboard
├─ GET /admin/order/{id}
└─ POST /admin/order/{id}/verify

Auth Routes:
└─ require __DIR__.'/auth.php' (login, register, dsb)
```

### **Database Migrations**

```
create_users_table.php
├─ id, name, email, password, role
├─ bank_name, no_rekening, atas_nama
└─ email_verified_at, created_at, updated_at

create_categories_table.php
├─ id, nama_kategori, deskripsi
└─ created_at, updated_at

create_products_table.php
├─ id, user_id (FK), category_id (FK)
├─ nama_produk, deskripsi, harga, stok, foto
├─ bank_name, no_rekening, atas_nama
└─ created_at, updated_at

create_orders_table.php
├─ id, user_id (FK), total_harga, status
├─ bukti_transfer, nama, email, alamat
├─ ekspedisi, metode_pembayaran
└─ created_at, updated_at

create_order_details_table.php
├─ id, order_id (FK), product_id (FK)
├─ jumlah, harga_saat_beli
└─ created_at, updated_at
```

---

## 📊 Database Relationships

```
Users (1) ─────────────── (M) Products
  ↓                              ↓
  ├─ role=buyer        ┌────────┴───────┐
  ├─ role=seller       ↓                ↓
  └─ role=admin    OrderDetails    OrderDetails
                       ↓
                      (M) ──────────── (M)
                       ↑                ↑
                      Orders          Categories
                       ↑
                      (1)
                       ↑
                      Users
                    (role=buyer)

Relasi Detail:
1. User (Seller) → banyak Products (1:M)
2. Category → banyak Products (1:M)
3. Product → banyak OrderDetails (1:M)
4. Order → banyak OrderDetails (1:M)
5. User (Buyer) → banyak Orders (1:M)
6. OrderDetail → Product (M:1)
7. OrderDetail → Order (M:1)
```

---

## 🎯 Summary & Checklist

### **Pre-Launch Checklist**

```
✓ Database Setup
  ├─ All migrations run successfully
  ├─ Relationships configured correctly
  └─ Test data seeded (optional)

✓ Authentication
  ├─ User registration with role selection
  ├─ Login & logout working
  ├─ Password hashing secure
  └─ Session management active

✓ Seller Features
  ├─ Product upload with foto
  ├─ Product edit & delete
  ├─ Product listing
  ├─ Bank details configuration
  └─ Order incoming view

✓ Buyer Features
  ├─ Product browsing & filtering
  ├─ Checkout form
  ├─ Payment instruction page
  ├─ Bukti transfer upload
  ├─ Order history view
  └─ Confirm receipt action

✓ Admin Features
  ├─ Admin dashboard
  ├─ Order detail view with bukti
  ├─ Approve/Reject functionality
  ├─ Stok reduction on approval
  └─ Admin middleware protection

✓ File Handling
  ├─ Product photo upload working
  ├─ Bukti transfer upload working
  ├─ File storage paths correct
  ├─ File validation active
  └─ File cleanup on delete (optional)

✓ Security
  ├─ CSRF protection active
  ├─ SQL injection prevention
  ├─ XSS protection
  ├─ Password hashing
  ├─ Session security
  └─ Ownership validation

✓ Error Handling
  ├─ DB transactions for atomicity
  ├─ Proper error messages
  ├─ Validation messages
  └─ Exception handling

✓ Testing
  ├─ Buyer checkout flow
  ├─ Seller order view
  ├─ Admin verification flow
  ├─ Stok reduction
  ├─ File uploads
  └─ Edge cases (overselling, invalid status)
```

---

## 📞 Support & Troubleshooting

### **Common Issues**

#### **Issue: Stok tidak berkurang setelah admin approve**
```
Solution:
├─ Cek AdminController::verify() logic
├─ Cek apakah DB transaction berjalan
├─ Cek apakah ada exception yang di-throw
└─ Debug dengan dd($order->orderDetails)
```

#### **Issue: Buyer tidak bisa see tombol "Konfirmasi Penerimaan"**
```
Solution:
├─ Cek status order (harus 'lunas')
├─ Refresh halaman atau clear cache
├─ Cek view blade template logic
└─ Debug: SELECT * FROM orders WHERE user_id=X
```

#### **Issue: Bukti transfer tidak ter-upload**
```
Solution:
├─ Cek file size (max 2MB)
├─ Cek file type (jpg, png, webp)
├─ Cek folder permission: public/storage/bukti_transfer/
├─ Cek disk space di server
└─ Debug: dd($request->file('bukti_transfer'))
```

#### **Issue: Admin tidak bisa akses /admin/dashboard**
```
Solution:
├─ Cek user role (harus 'admin')
├─ Cek middleware di routes/web.php
├─ Cek auth user: Auth::user()->role
└─ Debug: dd(Auth::user())
```

---

## 🎓 Kesimpulan

**Price-Wise** adalah sistem marketplace yang aman dengan:
- ✅ Seller bisa upload produk
- ✅ Buyer bisa browse & checkout
- ✅ Admin memverifikasi pembayaran
- ✅ Stok otomatis berkurang saat approval
- ✅ Transaksi atomic (semua-atau-tidak-sama-sekali)
- ✅ Security built-in (auth, CSRF, SQL injection prevention)

**Alur singkat:**
1. Seller upload produk
2. Buyer checkout & upload bukti transfer
3. Admin verifikasi & approve (stok berkurang)
4. Seller kirim barang
5. Buyer terima & konfirmasi
6. Transaksi selesai ✅

---

**Dokumentasi ini dibuat oleh: GitHub Copilot**  
**Tanggal: 8 Juli 2026**  
**Versi: 1.0**

---

*Untuk pertanyaan lebih lanjut atau update dokumentasi, silakan hubungi tim development.*
