<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Layanan;
use App\Models\PaketRevisi;

class WelcomeController extends Controller
{
    /**
     * Menampilkan halaman welcome/landing page.
     */
    public function index()
    {
        // Mengambil data paket revisi yang aktif untuk services section
        $paketRevisi = PaketRevisi::where('aktif', true)->get();
        
        // Mengambil data layanan untuk projects section
        $layanan = Layanan::all();
        
        return view('welcome', compact('paketRevisi', 'layanan'));
    }
}