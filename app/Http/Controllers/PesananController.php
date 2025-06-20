<?php

namespace App\Http\Controllers;

use App\Models\Pesanan;
use App\Models\Layanan;
use App\Models\PaketRevisi;
use App\Models\Pembayaran;
use App\Models\PengajuanRevisi;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Response;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Str;
use Carbon\Carbon;
use Midtrans\Snap;
use Midtrans\Transaction;
use Midtrans\Notification;
use Exception;
use Illuminate\Routing\Controller; // Add this line if it's missing


class PesananController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth:client');
    }

    protected function getClientId()
    {
        return Auth::guard('client')->id();
    }

    public function index()
    {
        $pesanans = Pesanan::where('user_id', $this->getClientId())->latest()->get();
        return view('client.pesanan.index', compact('pesanans'));
    }

    public function create()
    {
        $layanan = Layanan::all();
        $paketRevisi = PaketRevisi::all();
        return view('client.pesanan.create', compact('layanan', 'paketRevisi'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'layanan_id' => 'required|exists:layanan,id',
            'paket_revisi_id' => 'nullable|exists:paket_revisi,id',
            'persyaratan' => 'required|string',
            'file_pesanan' => 'required|file|max:10240',
            'batas_waktu' => 'required|date|after:today',
        ]);

        $nomorPesanan = 'PSN-' . strtoupper(Str::random(10));
        $layanan = Layanan::findOrFail($request->layanan_id);
        $paketRevisi = $request->filled('paket_revisi_id') ? PaketRevisi::find($request->paket_revisi_id) : null;

        $harga = $layanan->harga;
        $totalHarga = $harga + ($paketRevisi->harga ?? 0);
        $revisiTersisa = $paketRevisi->jumlah_revisi ?? 0;

        $filePath = $request->file('file_pesanan')->store('pesanan', 'public');

        $pesanan = Pesanan::create([
            'nomor_pesanan' => $nomorPesanan,
            'user_id' => $this->getClientId(),
            'layanan_id' => $layanan->id,
            'paket_revisi_id' => $paketRevisi?->id,
            'revisi_tersisa' => $revisiTersisa,
            'persyaratan' => $request->persyaratan,
            'status' => 'pending',
            'harga' => $harga,
            'total_harga' => $totalHarga,
            'batas_waktu' => Carbon::parse($request->batas_waktu),
            'file_pesanan' => $filePath,
            'status_revisi' => 'belum_ada',
        ]);

        Pembayaran::create([
            'pesanan_id' => $pesanan->id,
            'jumlah' => $totalHarga,
            'status_pembayaran' => 'pending',
            'metode_pembayaran' => 'midtrans',
        ]);

        return redirect()->route('client.pesanan.show', $pesanan->id)
            ->with('success', 'Pesanan berhasil dibuat. Silakan lanjutkan pembayaran.');
    }

    public function show(Pesanan $pesanan)
    {
        abort_if($pesanan->user_id !== $this->getClientId(), 403);

        $remainingDays = now()->diffInDays(Carbon::parse($pesanan->batas_waktu), false);
        $revisi = PengajuanRevisi::where('pesanan_id', $pesanan->id)->latest()->get();
        
        // Get payment information
        $pembayaran = $pesanan->pembayaran()->latest()->first();
        $canRequestRevision = false;
        
        // Check if user can request a revision
        if ($pesanan->status === 'diproses' && 
            $pesanan->hasil_pesanan && 
            $pesanan->revisi_tersisa > 0 &&
            $pesanan->status_revisi !== 'menunggu') {  // Changed from 'pending' to 'menunggu'
            $canRequestRevision = true;
        }
        
        // Get payment status if exists
        $paymentStatus = $pembayaran ? $pembayaran->status_pembayaran : 'belum_dibayar';
        
        return view('client.pesanan.show', compact(
            'pesanan',
            'revisi',
            'remainingDays',
            'canRequestRevision',
            'paymentStatus',
            'pembayaran'
        ));
    }

    // Method to handle revision request submission
    public function submitRevision(Request $request, Pesanan $pesanan)
{
    abort_if($pesanan->user_id !== $this->getClientId(), 403);
    
    // Validate if user can submit a revision
    if ($pesanan->revisi_tersisa <= 0) {
        return back()->with('error', 'Jumlah revisi Anda sudah habis.');
    }
    
    // Check if there's already a pending revision
    if ($pesanan->status_revisi === 'menunggu') {
        return back()->with('error', 'Anda sudah memiliki pengajuan revisi yang menunggu respons.');
    }
    
    // Validate request
    $request->validate([
        'deskripsi' => 'required|string',
        'file_pendukung' => 'nullable|file|max:10240',
    ]);
    
    $filePath = null;
    if ($request->hasFile('file_pendukung')) {
        $filePath = $request->file('file_pendukung')->store('revisi', 'public');
    }
    
    // Create revision request
    PengajuanRevisi::create([
        'pesanan_id' => $pesanan->id,
        'user_id' => $this->getClientId(),
        'deskripsi' => $request->deskripsi,
        'file_pendukung' => $filePath,
        'status' => 'menunggu',
    ]);
    
    // PERBAIKAN: Hanya update status_revisi, JANGAN kurangi revisi_tersisa
    $pesanan->update([
        'status_revisi' => 'menunggu',
        // revisi_tersisa akan dikurangi saat admin menerima pengajuan
    ]);
    
    return redirect()->route('client.pesanan.show', $pesanan->id)
        ->with('success', 'Pengajuan revisi berhasil dikirim.');
}

public function showRevisionForm(Pesanan $pesanan)
{
    abort_if($pesanan->user_id !== $this->getClientId(), 403);
    
    // PERBAIKAN: Tambahkan validasi status revisi
    if ($pesanan->revisi_tersisa <= 0) {
        return back()->with('error', 'Jumlah revisi Anda sudah habis.');
    }
    
    if ($pesanan->status_revisi === 'menunggu') {
        return back()->with('error', 'Anda sudah memiliki pengajuan revisi yang menunggu respons.');
    }
    
    // Hanya bisa mengajukan revisi jika status_revisi adalah 'belum_ada'
    if ($pesanan->status_revisi !== 'belum_ada') {
        return back()->with('error', 'Tidak dapat mengajukan revisi pada status saat ini.');
    }
    
    return view('client.pesanan.revision-form', compact('pesanan'));
}

    public function downloadHasilRevisi(PengajuanRevisi $pengajuanRevisi)
    {
        // Pastikan pengguna adalah pemilik pengajuan revisi
        abort_if($pengajuanRevisi->pesanan->user_id !== $this->getClientId(), 403);

        // Cek ke database untuk hasil revisi terkait
        $hasilRevisi = $pengajuanRevisi->hasilRevisi()->latest()->first();
        
        if (!$hasilRevisi || !$hasilRevisi->file_hasil) {
            return back()->with('error', 'File hasil revisi belum tersedia.');
        }

        // Cek format file hasil (bisa berupa array atau string)
        $fileHasil = $hasilRevisi->file_hasil;
        
        // Jika file_hasil berupa string JSON array, decode ke array
        if (is_string($fileHasil) && Str::startsWith($fileHasil, '[')) {
            $fileHasil = json_decode($fileHasil, true);
        }
        
        // Jika hasil sudah array, ambil file pertama
        if (is_array($fileHasil)) {
            if (count($fileHasil) === 0) {
                return back()->with('error', 'Tidak ada file hasil revisi yang tersedia untuk diunduh.');
            }
            
            $filePath = $fileHasil[0]; // Ambil file pertama
        } else {
            // Jika hasil sudah string biasa
            $filePath = $fileHasil;
        }
        
        // Cek keberadaan file di disk
        if (!Storage::disk('public')->exists($filePath)) {
            return back()->with('error', 'File hasil revisi tidak ditemukan.');
        }
        
        // Unduh file
        $path = Storage::disk('public')->path($filePath);
        return Response::download($path);
    }

    public function completeOrder(Pesanan $pesanan)
    {
        // Pastikan hanya pemilik pesanan yang bisa menyelesaikan
        abort_if($pesanan->user_id !== $this->getClientId(), 403, 'Anda tidak memiliki akses ke pesanan ini.');
        
        // Pastikan pesanan sudah diproses dan memiliki hasil
        if ($pesanan->status !== 'diproses' || !$pesanan->hasil_pesanan) {
            return back()->with('error', 'Pesanan tidak dapat diselesaikan karena belum diproses atau belum memiliki hasil.');
        }
        
        // Jika pesanan masih dalam status revisi menunggu, tidak bisa diselesaikan
        if ($pesanan->status_revisi === 'menunggu') {
            return back()->with('error', 'Pesanan tidak dapat diselesaikan karena masih menunggu proses revisi.');
        }
        
        // Update status pesanan menjadi selesai
        $pesanan->update([
            'status' => 'selesai',
            'tanggal_selesai' => now() // Carbon instance
        ]);
        
        return redirect()->route('client.pesanan.show', $pesanan->id)
            ->with('success', 'Pesanan berhasil diselesaikan. Terima kasih telah menggunakan layanan kami.');
    }

    // Method to download hasil pesanan
    public function downloadHasil(Pesanan $pesanan)
    {
        // Pastikan hanya user yang berhak yang bisa mengakses
        abort_if($pesanan->user_id !== $this->getClientId(), 403);
    
        if (!$pesanan->hasil_pesanan) {
            return back()->with('error', 'File hasil belum tersedia.');
        }
    
        // Cek apakah hasil_pesanan berupa array (misal disimpan sebagai JSON)
        $hasil = $pesanan->hasil_pesanan;
    
        // Jika hasil_pesanan berupa string JSON array, decode ke array
        if (is_string($hasil) && Str::startsWith($hasil, '[')) {
            $hasil = json_decode($hasil, true);
        }
    
        // Jika hasil sudah array, ambil file pertama
        if (is_array($hasil)) {
            if (count($hasil) === 0) {
                return back()->with('error', 'Tidak ada file yang tersedia untuk diunduh.');
            }
    
            $filePath = $hasil[0]; // Ambil file pertama
        } else {
            // Jika hasil sudah string biasa
            $filePath = $hasil;
        }
    
        // Cek keberadaan file di disk
        if (!Storage::disk('public')->exists($filePath)) {
            return back()->with('error', 'File tidak ditemukan.');
        }
    
        // Unduh file
        $path = Storage::disk('public')->path($filePath);
        return Response::download($path);
    }

    public function download(Pesanan $pesanan)
    {
        abort_if($pesanan->user_id !== $this->getClientId(), 403);

        if (!$pesanan->file_pesanan || !Storage::disk('public')->exists($pesanan->file_pesanan)) {
            return back()->with('error', 'File pesanan tidak tersedia atau tidak ditemukan.');
        }

        $path = Storage::disk('public')->path($pesanan->file_pesanan);
        return Response::download($path);
    }

    public function processPayment(Pesanan $pesanan)
    {
        abort_if($pesanan->user_id !== $this->getClientId(), 403);

        $pembayaran = $pesanan->pembayaran()->where('status_pembayaran', 'pending')->first();
        if (!$pembayaran) {
            $pembayaran = Pembayaran::create([
                'pesanan_id' => $pesanan->id,
                'jumlah' => $pesanan->total_harga,
                'status_pembayaran' => 'pending',
                'metode_pembayaran' => 'midtrans',
            ]);
        }

        if ($pembayaran->status_pembayaran !== 'pending') {
            return redirect()->route('client.pesanan.show', $pesanan->id)
                ->with('info', 'Pembayaran sudah diproses.');
        }

        try {
            \Midtrans\Config::$serverKey = config('midtrans.server_key');
            \Midtrans\Config::$isProduction = false;
            \Midtrans\Config::$isSanitized = true;
            \Midtrans\Config::$is3ds = true;

            $params = [
                'transaction_details' => [
                    'order_id' => $pesanan->nomor_pesanan,
                    'gross_amount' => (int) $pesanan->total_harga,
                ],
                'customer_details' => [
                    'first_name' => Auth::guard('client')->user()->name,
                    'email' => Auth::guard('client')->user()->email,
                ],
                'item_details' => [
                    [
                        'id' => $pesanan->layanan_id,
                        'price' => (int) $pesanan->harga,
                        'quantity' => 1,
                        'name' => $pesanan->layanan->nama ?? 'Layanan',
                    ],
                ],
                'enabled_payments' => [
                    'credit_card', 'bca_va', 'bni_va', 'bri_va', 'mandiri_va',
                    'permata_va', 'gopay', 'shopeepay'
                ],
                'callbacks' => [
                    'finish' => route('client.pesanan.payment.finish', $pesanan->id),
                ]
            ];
            
            // Add paket revisi to item details if exists
            if ($pesanan->paket_revisi_id) {
                $params['item_details'][] = [
                    'id' => 'paket-' . $pesanan->paket_revisi_id,
                    'price' => (int) ($pesanan->total_harga - $pesanan->harga),
                    'quantity' => 1,
                    'name' => $pesanan->paketRevisi->nama ?? 'Paket Revisi',
                ];
            }

            $snapToken = Snap::getSnapToken($params);
            $pembayaran->update([
                'snap_token' => $snapToken,
                'kode_pembayaran' => $pesanan->nomor_pesanan,
            ]);

            return view('client.pesanan.payment', compact('snapToken', 'pesanan'));
        } catch (Exception $e) {
            Log::error('Midtrans error: ' . $e->getMessage());
            return back()->with('error', 'Gagal memproses pembayaran.');
        }
    }

    public function handlePaymentNotification(Request $request)
    {
        try {
            $notif = new Notification();
            $data = (array) $notif;
            $orderId = $data['order_id'] ?? null;
            $transactionStatus = $data['transaction_status'] ?? null;
            $fraudStatus = $data['fraud_status'] ?? null;
            $paymentType = $data['payment_type'] ?? null;

            $pesanan = Pesanan::where('nomor_pesanan', $orderId)->firstOrFail();
            $pembayaran = $pesanan->pembayaran()->latest()->first();

            if (!$pembayaran) {
                throw new Exception('Pembayaran tidak ditemukan');
            }

            $status = match ($transactionStatus) {
                'settlement' => 'berhasil',
                'capture' => ($paymentType === 'credit_card' && $fraudStatus !== 'challenge') ? 'berhasil' : 'challenge',
                'pending' => 'pending',
                'expire' => 'expired',
                'cancel' => 'gagal',
                'deny' => 'gagal',
                default => 'pending',
            };

            $pembayaran->update([
                'status_pembayaran' => $status,
                'metode_pembayaran' => $paymentType,
                'tanggal_pembayaran' => now(),
            ]);

            if ($status === 'success') {
                $pesanan->update(['status' => 'diproses']);
            }

            return response()->json(['status' => 'ok']);
        } catch (Exception $e) {
            Log::error('Midtrans notification error: ' . $e->getMessage());
            return response()->json(['status' => 'error'], 500);
        }
    }

    // Method to handle payment completion
    public function handlePaymentFinish(Pesanan $pesanan)
    {
        try {
            // Verify that the order belongs to the authenticated user
            abort_if($pesanan->user_id !== $this->getClientId(), 403);

            // Configure Midtrans
            \Midtrans\Config::$serverKey = config('midtrans.server_key');
            \Midtrans\Config::$isProduction = false;

            // Get transaction status from Midtrans
            Log::info('Checking transaction status for order: ' . $pesanan->nomor_pesanan);
            $status = Transaction::status($pesanan->nomor_pesanan);
            
            // Convert to array and log for debugging
            $data = (array) $status;
            Log::info('Midtrans response data: ', $data);
            
            $transactionStatus = $data['transaction_status'] ?? null;
            $paymentType = $data['payment_type'] ?? null;
            $fraudStatus = $data['fraud_status'] ?? null;

            Log::info("Transaction status: $transactionStatus, Payment type: $paymentType, Fraud status: $fraudStatus");

            // Get payment record
            $pembayaran = $pesanan->pembayaran()->latest()->first();
            if (!$pembayaran) {
                Log::error('Payment data not found for order: ' . $pesanan->nomor_pesanan);
                throw new Exception('Data pembayaran tidak ditemukan');
            }

            // Map transaction status to payment status
            // Use standard status values that match the database column definition
            $statusPembayaran = match ($transactionStatus) {
                'settlement' => 'berhasil',
                'capture' => ($paymentType === 'credit_card' && $fraudStatus !== 'challenge') ? 'berhasil' : 'pending',
                'pending' => 'pending',
                'expire' => 'expired',
                'cancel' => 'dibatalkan',
                'deny' => 'ditolak',
                default => 'pending',
            };

            Log::info("Mapped payment status: $statusPembayaran");

            // Update payment record
            $pembayaran->update([
                'status_pembayaran' => $statusPembayaran,
                'metode_pembayaran' => $paymentType,
                'tanggal_pembayaran' => now(),
            ]);

            // Update order status if payment was successful
            if ($statusPembayaran === 'berhasil') {
                $pesanan->update(['status' => 'diproses']);
                return redirect()->route('client.pesanan.show', $pesanan->id)
                    ->with('success', 'Pembayaran berhasil.');
            }

            return redirect()->route('client.pesanan.show', $pesanan->id)
                ->with('info', 'Status pembayaran: ' . $statusPembayaran);
        } catch (Exception $e) {
            // Log detailed error information
            Log::error('Payment finish error: ' . $e->getMessage());
            Log::error('Error trace: ' . $e->getTraceAsString());
            
            // Redirect with error message that includes more details
            return redirect()->route('client.pesanan.show', $pesanan->id)
                ->with('error', 'Gagal memproses status pembayaran: ' . $e->getMessage());
        }
    }

    public function checkPaymentStatus(Request $request, Pesanan $pesanan)
    {
        if ($pesanan->user_id !== $this->getClientId()) {
            abort(403);
        }

        try {
            \Midtrans\Config::$serverKey = config('midtrans.server_key');
            \Midtrans\Config::$isProduction = false;

            $status = Transaction::status($pesanan->nomor_pesanan);
            $data = (array) $status;
            $transactionStatus = $data['transaction_status'] ?? null;
            $paymentType = $data['payment_type'] ?? null;

            $statusPembayaran = match ($transactionStatus) {
                'settlement', 'capture' => 'berhasil',
                'pending' => 'pending',
                'expire' => 'expired',
                'cancel' => 'dibatalkan',
                'deny' => 'ditolak',
                default => 'pending',
            };

            $pembayaran = $pesanan->pembayaran()->latest()->first();
            if ($pembayaran) {
                $pembayaran->update([
                    'status_pembayaran' => $statusPembayaran,
                    'metode_pembayaran' => $paymentType,
                    'tanggal_pembayaran' => now(),
                ]);
            }

            if ($statusPembayaran === 'berhasil') {
                $pesanan->update(['status' => 'diproses']);
            }

            return response()->json(['status' => $statusPembayaran]);
        } catch (Exception $e) {
            return response()->json(['error' => 'Gagal memeriksa status'], 500);
        }
    }
}