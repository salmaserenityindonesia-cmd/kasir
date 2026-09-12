# Dokumentasi Teknis OSPOS (Open Source Point of Sale)

Dokumen ini berisi analisis teknis mendalam mengenai arsitektur, fitur, relasi database, hingga alur logika khusus pada modul penjualan (Sales) dari repositori OSPOS di environment saat ini.

---

## 1. High-Level Overview
OSPOS (Open Source Point of Sale) adalah aplikasi sistem kasir (POS) berbasis web yang dirancang untuk mengelola penjualan, inventori, pelanggan, penyuplai, dan laporan toko. Aplikasi ini dikembangkan di atas kerangka kerja (framework) **CodeIgniter 4**, berlandaskan arsitektur **MVC (Model-View-Controller)** yang memisahkan logika antarmuka, kontrol arus data, dan manipulasi database.

---

## 2. Feature List (Daftar Modul)
Berikut adalah daftar modul utama (merepresentasikan Controller) yang terdapat pada aplikasi beserta fungsinya:

*   **Items (`Items.php` / `Item.php`)**: Mengelola master data barang, harga beli/jual, pajak, kategori, dan lokasi stok.
*   **Item Kits (`Item_kits.php`)**: Memungkinkan penggabungan beberapa item tunggal menjadi satu paket penjualan (bundling).
*   **Sales (`Sales.php` / `Sale.php`)**: Modul inti (Point of Sale) untuk memproses transaksi dengan pelanggan, menghitung diskon & pajak, mencatat pembayaran, hingga menerbitkan struk/invoice.
*   **Customers (`Customers.php`)**: Mengelola data pelanggan toko, melacak riwayat diskon, poin _reward_, dan hutang pelanggan.
*   **Expenses (`Expenses.php`)**: Modul pencatatan pengeluaran operasional toko.
*   **Receivings (`Receivings.php`)**: Mengelola proses retur atau penerimaan stok masuk (Purchase Orders) dari _Suppliers_.
*   **Suppliers (`Suppliers.php`)**: Penyimpanan master data pemasok/vendor barang toko.
*   **Employees (`Employees.php`)**: Modul manajemen akses akun pengguna (kasir/admin), _role_, dan _permissions_ (hak akses modul).
*   **Reports (`Reports.php`)**: Pembuatan laporan ringkasan/detail (penjualan, stok, dll) baik dalam format tabel maupun grafik.
*   **Config / Office**: Konfigurasi tingkat aplikasi yang menyangkut profil perusahaan, pencetak struk (receipt), format format tanggal/waktu, bahasa, dll.

---

## 3. Application Architecture (Arsitektur MVC)
Aplikasi ini diimplementasikan menggunakan arsitektur **MVC CodeIgniter 4**:
*   **Controller (`app/Controllers/`)**: Bertanggung jawab menerima _request_ HTTP dari pengguna. Controller (misal: `Sales.php`) akan memanggil fungsi dari Library atau Model terkait, serta menerima inputan via form lalu mem-parsing instruksinya. 
*   **Model (`app/Models/`)**: Menangani sekumpulan perintah kueri ke database. File seperti `Sale.php` mewarisi `CodeIgniter\Model` dan melakukan abstraksi untuk `INSERT`, `UPDATE`, `SELECT`, dll. Pada operasi kompleks, Model menggunakan fitur Transaction (`transStart()` & `transComplete()`) untuk menjaga integritas data.
*   **View (`app/Views/`)**: Lapisan presentasi (UI) yang umumnya diperkaya oleh kombinasi Bootstrap, jQuery, dan interaksi AJAX untuk pengalaman layaknya _Single Page Application_ di modul kasir.
*   **Libraries (`app/Libraries/`)**: OSPOS banyak menyematkan aturan dan kalkulasi bisnisnya di sebuah "Library" perantara (contoh: `Sale_lib.php`). Library inilah yang mengatur logika _Cart_ (keranjang) secara sementara via *PHP Session* (menyimpan susunan item sebelum kasir meng-klik "Selesaikan Penjualan").

---

## 4. Database Relations
Berikut merupakan tabel-tabel krusial dalam sistem database dan relasi antar mereka (menggunakan prefix _ospos__):

*   **`ospos_items`**: Menyimpan referensi entitas barang pokok.
*   **`ospos_customers`**: Menyimpan identitas pelanggan toko. Terhubung dengan entitas umum dari tabel `ospos_people`.
*   **`ospos_sales`**:  Tabel kerangka utama setiap proses *checkout* penjualan/invoice.
    *   Berelasi dengan `ospos_customers` (via *customer_id*).
    *   Berelasi dengan `ospos_employees` (via *employee_id*, kasir yang bertugas).
*   **`ospos_sales_items`**: Menyimpan daftar rinci (keranjang) per barang pada satu transaksi.
    *   Berelasi _Many-to-One_ ke `ospos_sales` (via *sale_id*).
    *   Berelasi _Many-to-One_ ke `ospos_items` (via *item_id*).
*   **`ospos_sales_payments`**: Relasi rinci jika dalam 1 transaksi pembeli melakukan metode split payment (tunai & kartu kredit/giftcard). Terhubung dengan `ospos_sales` (via *sale_id*).
*   **`ospos_item_quantities` & `ospos_inventory`**: Tempat di mana rekapitulasi jumlah stok dan log histori perubahan (audit log) atas item berada. Terhubung via *item_id*.

---

## 5. Core Logic Flow (Alur Modul Penjualan / Sales)
Modul *Sales* merupakan fungsionalitas paling krusial. Alur proses kasir hingga penyimpanan di database berjalan sebagai berikut:

1.  **Add Item to Cart**:
    *   Ketika kasir men-scan barcode atau menginput nama barang, permintaan HTTP POST dilakukan ke `Sales::postAdd()`.
    *   Controller mendelegasikan perintah ini ke Library `Sale_lib->add_item()`.
    *   Library memverifikasi ketersediaan stok, mem-parsing harga beserta pajak/diskon, lalu menyimpan perubahannya di memori *Session* keranjang (`$_SESSION['cart']`). Ini berguna agar jika transaksi tertunda, datanya tidak membebani/mengotori *database*.
2.  **Add Payment**:
    *   Kasir menginputkan nominal bayar (`Sales::postAddPayment()`). Library `Sale_lib` akan memvalidasi apakah tagihan (dikurangi _Gift Card/Rewards_) sudah terpenuhi oleh uang tunai/_credit_ lalu menyimpannya di _Session_.
3.  **Complete Sale (Checkout)**:
    *   Proses diselesaikan melalui perintah ke `Sales::postComplete()`. 
    *   Bila verifikasi sukses, *Controller* akan mengirimkan rangkuman data di sesi tersebut ke **Model** untuk disuntikkan secara persisten lewat `Sale::save_value()`.
4.  **Database Persistence & Transaction (Model `Sale.php`)**:
    *   Model memulai transaksi *database* atomik menggunakan `$this->db->transStart()`.
    *   **INSERT `ospos_sales`**: Menyimpan waktu order, *employee*, dan *customer*. Mengembalikan nilai *primary key* `sale_id`.
    *   **INSERT `ospos_sales_payments`**: Melakukan iterasi semua tipe pembayaran untuk ID `sale_id` di atas.
    *   **INSERT `ospos_sales_items`**: Menyimpan per baris daftar barang (harga akhir, diskon, id item).
    *   **UPDATE `ospos_item_quantities`**: Mendeterminasi ulang (mengurangi) stok gudang.
    *   **INSERT `ospos_inventory`**: Memasukkan satu baris log pengeluaran (-X jumlah barang) dengan referensi `POS {sale_id}`.
    *   Transaksi *database* selesai via `$this->db->transComplete()`.
    *   Terakhir, struk ditenagai dan di-*render* ke klien.

---

## 6. Customization Guide (Panduan Kustomisasi)
Jika tim pengembang ke depannya perlu melakukan modifikasi sistem secara custom, area yang harus diulik adalah sebagai berikut:

*   **Pembaruan Tampilan atau Format Kasir (View)**: 
    *   Pusat direktori UI berada di `app/Views/`. 
    *   *(Untuk mengubah struk kasir/receipt)*: Arahkan rombakan pada `app/Views/sales/receipt.php` atau `app/Views/sales/receipt_default.php`.
    *   *(Untuk panel antarmuka transaksi)*: Ubah di `app/Views/sales/register.php`.
*   **Penambahan Flow Logika/Validasi Baru (Library/Controller)**:
    *   Untuk menambah validasi kasir di sisi server (misal: validasi diskon maksimal atau batasan penjualan per hari), modifikasi `app/Libraries/Sale_lib.php`.
    *   Untuk menambah endpoint _Controller_ guna menerima data AJAX dari frontend, tambahkan metode _method_ baru di file-file `app/Controllers/*.php`.
*   **Perubahan Query & Data Stok (Model)**:
    *   Modifikasi kueri, _report_ agregat, serta integrasi struktur tabel baru hanya dilakukan di _Model_. Contoh: untuk menghubungkan data baru ketika checkout selesai, edit metode besar `save_value()` yang bersemayam dalam `app/Models/Sale.php`.
*   **Penyesuaian Konfigurasi Utama & Database**:
    *   URL, _Database Connection_ (apabila bertukar dari SQLite/MySQL), serta Mode _Development_ diletakkan di dalam file `.env` sistem CodeIgniter 4 dan `app/Config/Database.php`.
*   **Penerjemahan Bahasa (L10N)**:
    *   Label teks bisa dimodifikasi pada `app/Language/<kode_bahasa>/`. Misalnya ganti _wording_ kasir via `app/Language/id/Sales.php`.
