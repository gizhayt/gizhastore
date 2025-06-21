<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Layanan;
use App\Models\PaketRevisi;

class ClientDashboardController extends Controller
{
    /**
     * Menampilkan halaman dashboard client (home).
     */
    public function index()
    {
        // Mengambil data paket revisi yang aktif untuk services section
        $paketRevisi = PaketRevisi::where('aktif', true)->get();
        
        // Mengambil data layanan untuk projects section
        $layanan = Layanan::all();
        
        return view('client.dashboard', compact('paketRevisi', 'layanan'));
    }

    /**
     * Menampilkan halaman layanan (services).
     */
    public function listLayanan()
    {
        $layanan = Layanan::all();
        return view('client.layanan.index', compact('layanan'));
    }

    /**
     * Logout client dari sistem.
     */
    public function logout(Request $request)
    {
        auth()->guard('client')->logout();

        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect()->route('client.login'); // Pastikan route ini ada
    }
}