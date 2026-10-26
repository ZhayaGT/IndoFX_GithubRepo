<?php

namespace IndoFX\Controllers;

use IndoFX\Core\Controller;

class HomeController extends Controller
{
    public function index()
    {
        // Data sementara untuk dikirim ke view
        $data = [
            'title' => 'Beranda IndoFX'
        ];

        // Mencetak langsung ke browser untuk tes sistem routing.
        // Jika teks ini muncul di browser, berarti Routing dan Controller sudah saling terhubung dengan sukses!
        echo "<h1>Sistem Back End IndoFX Berjalan!</h1>";
        echo "<p>Router berhasil memanggil HomeController.</p>";

        // Nanti, ketika Front End Developer sudah membuat file view-nya,
        // hapus baris echo di atas dan hilangkan tanda komentar pada kode di bawah ini:
        // $this->view('catalog/home', $data);
    }
}
