<?php

namespace App\Http\Controllers;

use App\Models\Layanan;
use Illuminate\Http\Request;

class ClientController extends Controller
{
    /**
     * Display the layanan (services) index page for clients
     *
     * @return \Illuminate\Contracts\View\View
     */
    public function layanan()
    {
        // Fetch all layanan (services) to display to the client
        $layanan = Layanan::with('paketRevisi')->get();
        
        return view('client.layanan.index', compact('layanan'));
    }

    /**
     * Display the client dashboard
     *
     * @return \Illuminate\Contracts\View\View
     */
    public function dashboard()
    {
        return view('client.dashboard');
    }

    /**
     * Log out the client
     *
     * @param Request $request
     * @return \Illuminate\Http\RedirectResponse
     */
    public function logout(Request $request)
    {
        auth()->guard('client')->logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();
        
        return redirect()->route('login');
    }
}