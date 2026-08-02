<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <title>Struk Transaksi - Price Wise</title>

    <!-- ===== STYLE UMUM: Reset dan tampilan dasar halaman ===== -->
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
            font-family: Arial, Helvetica, sans-serif;
        }

        body {
            background: #f3f4f6;
            padding: 40px;
        }

        .print-button {

            margin-top: 40px;
            display: flex;
            justify-content: center;
            gap: 15px;
            flex-wrap: wrap;

        }

        .print-button a,
        .print-button button {

            display: inline-block;
            text-decoration: none;
            color: white;
            padding: 14px 28px;
            border-radius: 8px;
            font-size: 15px;
            font-weight: bold;
            border: none;
            cursor: pointer;
        }

        .print-button a:hover,
        .print-button button:hover {

            opacity: .9;

        }

        @media print {

            .print-button {

                display: none;

            }

            body {

                background: white;

                padding: 0;

            }

            .invoice {

                box-shadow: none;

            }

        }

        .invoice {

            max-width: 900px;
            margin: auto;
            background: white;
            border-radius: 12px;
            overflow: hidden;
            box-shadow: 0 5px 20px rgba(0, 0, 0, .08);

        }

        .header {

            background: #2563eb;
            color: white;
            padding: 30px;

        }

        .header h1 {

            font-size: 30px;

        }

        .header p {

            margin-top: 8px;
            font-size: 15px;

        }

        .content {

            padding: 30px;

        }
    </style>

    <!-- ===== STYLE KHUSUS PDF: Ukuran font & layout dipadatkan agar muat di satu halaman A4 ===== -->
    @if(isset($for_pdf) && $for_pdf)
        <style>
            /* Pengaturan ukuran halaman A4 mode potrait dengan margin minimal */
            @page {
                size: A4 portrait;
                margin: 10mm;
            }

            body {
                background: white;
                padding: 6px;
                font-size: 11px;
                line-height: 1.1;
            }

            .invoice {
                max-width: 780px;
                margin: auto;
                border-radius: 4px;
                box-shadow: none;
            }

            .header {
                padding: 8px 10px;
            }

            .header h1 {
                font-size: 16px;
            }

            .header p {
                font-size: 10px;
            }

            .content {
                padding: 8px 10px;
            }

            table th,
            table td {
                font-size: 10px;
                padding: 6px 8px;
            }

            .print-button {
                display: none !important;
            }

            img {
                width: 48px;
                height: auto;
            }

            hr {
                margin: 6px 0 !important;
            }

            h2 {
                font-size: 14px;
                margin-bottom: 8px;
            }

            h3 {
                font-size: 12px;
                margin-bottom: 6px;
            }

            .total-amount {
                font-size: 18px;
            }

            /* Mencegah konten terpotong di tengah halaman saat dicetak */
            .invoice,
            .content,
            table,
            tbody,
            tr,
            td {
                page-break-inside: avoid;
            }
        </style>
    @endif

</head>

<body>

    <!-- ===== CONTAINER UTAMA STRUK ===== -->
    <div class="invoice">

        <!-- Header struk: Nama platform dan keterangan dokumen -->
        <div class="header">

            <h1>PRICE WISE</h1>

            <p>
                Bukti Transaksi Rekening Bersama (Rekber)
            </p>

        </div>

        <!-- ===== ISI KONTEN STRUK ===== -->
        <div class="content">

            <!-- Judul halaman -->
            <h2 style="margin-bottom:25px;">
                Invoice Transaksi
            </h2>

            <!-- Tabel info transaksi: Nomor invoice, ID, tanggal, dan status -->
            <table style="width:100%; border-collapse:collapse;">

                <tr>
                    <td width="25%"><strong>Nomor Invoice</strong></td>
                    <td>
                        INV-PW-{{ date('Ymd') }}-{{ str_pad($order->id, 5, '0', STR_PAD_LEFT) }}
                    </td>
                </tr>

                <tr>
                    <td><strong>ID Transaksi</strong></td>
                    <td>
                        #PW-{{ str_pad($order->id, 5, '0', STR_PAD_LEFT) }}
                    </td>
                </tr>

                <tr>
                    <td><strong>Tanggal</strong></td>
                    <td>
                        {{ $order->created_at->format('d F Y H:i') }}
                    </td>
                </tr>

                <tr>
                    <td><strong>Status</strong></td>
                    <td style="color:green;">
                        {{ strtoupper($order->status) }}
                    </td>
                </tr>

            </table>

            <hr style="margin:30px 0;">

            <!-- Tabel data pembeli: nama, email, alamat, ekspedisi, metode pembayaran -->
            <h3 style="margin-bottom:15px;">
                Data Pembeli
            </h3>

            <table style="width:100%;">

                <tr>
                    <td width="25%"><strong>Nama</strong></td>
                    <td>{{ $order->nama }}</td>
                </tr>

                <tr>
                    <td><strong>Email</strong></td>
                    <td>{{ $order->email }}</td>
                </tr>

                <tr>
                    <td><strong>Alamat</strong></td>
                    <td>{{ $order->alamat }}</td>
                </tr>

                <tr>
                    <td><strong>Ekspedisi</strong></td>
                    <td>{{ $order->ekspedisi }}</td>
                </tr>

                <tr>
                    <td><strong>Pembayaran</strong></td>
                    <td>{{ $order->metode_pembayaran }}</td>
                </tr>

            </table>
            <hr style="margin:30px 0;">

            <!-- Tabel detail produk yang dibeli: foto, nama, penjual, qty, harga, subtotal -->
            <h3 style="margin-bottom:15px;">
                Detail Produk
            </h3>

            <table style="width:100%; border-collapse:collapse; border:1px solid #ddd;">

                <thead style="background:#f3f4f6;">

                    <tr>

                        <th style="padding:12px;">Foto</th>

                        <th>Produk</th>

                        <th>Penjual</th>

                        <th>Qty</th>

                        <th>Harga</th>

                        <th>Subtotal</th>

                    </tr>

                </thead>

                <tbody>

                    @foreach($order->orderDetails as $detail)

                        <tr>

                            <td style="padding:12px; text-align:center;">

                                @if($detail->product->foto)

                                    @php
                                        // Gunakan path lokal untuk render PDF, dan URL asset untuk tampilan browser
                                        $imgSrc = (isset($for_pdf) && $for_pdf)
                                            ? public_path('storage/products/' . $detail->product->foto)
                                            : asset('storage/products/' . $detail->product->foto);
                                    @endphp

                                    <img src="{{ $imgSrc }}" width="70" style="border-radius:8px;">

                                @endif

                            </td>

                            <td>

                                {{ $detail->product->nama_produk }}

                            </td>

                            <td>

                                {{ $detail->product->user->name }}

                            </td>

                            <td align="center">

                                {{ $detail->jumlah }}

                            </td>

                            <td>

                                Rp {{ number_format($detail->harga_saat_beli, 0, ',', '.') }}

                            </td>

                            <td>

                                Rp {{ number_format($detail->jumlah * $detail->harga_saat_beli, 0, ',', '.') }}

                            </td>

                        </tr>

                    @endforeach

                </tbody>

            </table>

            <!-- Total harga keseluruhan transaksi -->
            <div class="total-amount" style="text-align:right;font-weight:bold;">
                TOTAL :
                Rp {{ number_format($order->total_harga, 0, ',', '.') }}
            </div>
            <hr>

            <!-- Keterangan status transaksi dan informasi rekber -->
            <div style="display:flex;justify-content:space-between;align-items:center;">

                <div>

                    <h3>Status Transaksi</h3>

                    <p style="margin-top:10px;color:green;font-size:18px;font-weight:bold;">

                        ✔ TRANSAKSI SELESAI

                    </p>

                    <p style="margin-top:10px;color:#555;line-height:1.6;max-width:500px;">

                        Dana pembayaran telah berhasil diteruskan kepada penjual melalui
                        sistem Rekening Bersama (Rekber) Price Wise.
                        Barang telah diterima oleh pembeli dan transaksi dinyatakan selesai.

                    </p>

                </div>

            </div>
            <div style="margin-top:30px;display:flex;justify-content:space-between;">

                <div>

                    <strong>Price Wise Marketplace</strong>

                    @unless(isset($for_pdf) && $for_pdf)
                        <br><br>
                    @endunless

                    Dokumen ini dibuat secara otomatis oleh sistem.

                </div>

                <div style="text-align:center;">

                    @unless(isset($for_pdf) && $for_pdf)
                        <br>
                    @endunless

                </div>

            </div>

        </div>

    </div>
    <!-- Tombol aksi: Kembali, Download PDF, dan Cetak (disembunyikan saat mode PDF) -->
    @unless(isset($for_pdf) && $for_pdf)
        <div class="print-button">

            <!-- Tombol Kembali ke riwayat -->
            <a href="{{ route('orders.history') }}"
                style="text-decoration:none;background:#6b7280;color:white;padding:12px 25px;border-radius:8px;font-weight:bold;">

                ← Kembali

            </a>

            <!-- Tombol Download PDF -->
            <a href="{{ route('orders.download', $order->id) }}"
                style="text-decoration:none;background:#16a34a;color:white;padding:12px 25px;border-radius:8px;font-weight:bold;">

                📥 Download PDF

            </a>

            <!-- Tombol Cetak -->
            <button onclick="window.print()"
                style="background:#2563eb;color:white;padding:12px 25px;border:none;border-radius:8px;font-weight:bold;cursor:pointer;">

                🖨 Cetak

            </button>

        </div>
    @endunless
</body>

</html>