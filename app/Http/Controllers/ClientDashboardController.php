<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Layanan;

class ClientDashboardController extends Controller
{
    /**
     * Menampilkan halaman dashboard client (home).
     */
    public function index()
    {
        return view('client.dashboard');
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
