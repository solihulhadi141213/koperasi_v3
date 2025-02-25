# Aplikasi Koperasi V 3.0.0
Aplikasi Koperasi V 3.0.0 adalah sebuah aplikasi open source yang dirancang untuk membantu pengelolaan koperasi secara digital. Aplikasi ini dibangun menggunakan teknologi modern seperti PHP, MySQL, jQuery, dan Bootstrap 4 untuk memberikan pengalaman pengguna yang responsif dan mudah digunakan. Aplikasi ini cocok digunakan oleh koperasi simpan pinjam, koperasi serba usaha, atau koperasi konsumen yang membutuhkan sistem pengelolaan terintegrasi.
![Logo](./assets/img/screenshot/dashboard.png)

## Fitur Aplikasi
1. **Akses**    
    - Fitur Aplikasi <br>
    Berfungsi mengelola kode otentifikasi pada masing-masing halaman.
    - Entitas Akses <br>
    Berfungsi untuk mengelola entitas/level akses (pengurus) sehingga memungkinkan masing-masing mempunyai hak akses yang berbeda sesuai tugasnya.
    - Akses/Pengguna <br>
    Berfungsi mengelola semua data akses pengguna pada level (Pengurus)
2. Anggota
   - Anggota<br> 
     Berfungsi untuk mengelola semua data anggota, input data anggota, ubah dan hapus. Terdapat filter untuk pencarian, import untuk memasukan data dari excel dan export ke data excel.
   - Keluar & Masuk <br> 
     Menampilkan rekapitulasi data keluar-masuk anggota berdasarkan periode waktu tertentu.
   - Rekap Anggota <br> 
     Menampilkan rekapitulasi jumlah anggota keluar dan masuk berdasarkan divisi/unit kerja (untuk koperasi karyawan)
3. Simpanan Anggota
   - Jenis Simpanan
   - Simpanan Wajib
   - Log Simpanan
   - Rekap Simpanan
4. Pinjaman Anggota
   - Sesi Pinjaman
   - Tagihan/Tunggakan
   - Rekap Pinjaman
5. Barang/Inventory
   - Master Barang
   - Batch & Expired
   - Stock Opename
   - Supplier
6. Transaksi Operasional
   - Kategori Operasional
   - Transaksi Operasional
   - Rekap Transaksi
7. Transaksi Jual/Beli
   - Transaksi Penjualan
   - Transaksi Pembelian
   - Rekap Transaksi
8. Pembukuan Keuangan
   - Bagi Hasil (SHU)
   - Akun Perkiraan
   - Jurnal Keuangan
9. Laporan
   - Simpan Pinjam
   - Buku Bessar
   - Neraca Saldo
   - Laba Rugi
   - Riwayat Transaksi
10. Pengaturan Aplikasi
   - Pengaturan Umum
   - Auto Jurnal
   - Email Gateway
11. Log Aktivitas
12. Konten Bantuan
13. Profil Pengguna

## Instalasi
### Persyaratan Sistem
- PHP 7.4 atau lebih baru
- MySQL 5.7 / MariaDB 10.3 atau lebih baru
- Web Server : Wampserver, Xampp
### Tahapan Instalasi
- Instal webserver (Xampp, Wamp) terlebih dulu kemudian jalankan.
- Simpan folder aplikasi pada directory htdoc (untuk pengguna xampp) atau www (untuk pengguna wamp).
- Masuk ke database mnggunakan phpmyadmin dengan cara ketik localhost/phpmyadmin
- Buat database baru dengan nama apapun (Misalnya : koperasi_v3)
- Import database aplikasi (database standar aplikasi ini disimpan pada folder db).
- Atur variabel koneksi database aplikasi pada file _Config/Connection.php
- Ubah nama database sesuai nama database yang tadi di buat (Misalnya : koperasi_v3).
- Buka aplikasi dengan cara ketik localhost/{nama_folder_aplikasi}
- Lakukan login untuk pertama kali dengan memasukan email : dhiforester@gmail.com dan password : dhiforester





