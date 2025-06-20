<?php

namespace App\Filament\Widgets;

use App\Models\Pesanan;
use Filament\Widgets\StatsOverviewWidget as BaseWidget;
use Filament\Widgets\StatsOverviewWidget\Stat;
use Carbon\Carbon;

class PesananOverviewWidget extends BaseWidget
{
    protected static ?int $sort = 3;
    
    protected function getStats(): array
    {
        // Total pesanan
        $totalPesanan = Pesanan::count();
        
        // Total pendapatan (dari total_harga)
        $totalPendapatan = Pesanan::sum('total_harga');
        
        // Pesanan yang harus segera diselesaikan (1-3 hari lagi)
        $deadline = Pesanan::whereIn('status', ['pending', 'diproses', 'revisi'])
            ->whereBetween('batas_waktu', [
                Carbon::now(), 
                Carbon::now()->addDays(3)
            ])
            ->count();
            
        // Pesanan selesai bulan ini
        $pesananSelesaiBulanIni = Pesanan::where('status', 'selesai')
            ->whereMonth('updated_at', Carbon::now()->month)
            ->whereYear('updated_at', Carbon::now()->year)
            ->count();
            
        return [
            Stat::make('Total Pesanan', $totalPesanan)
                ->description('Semua pesanan')
                ->descriptionIcon('heroicon-m-shopping-cart')
                ->color('primary'),
                
            Stat::make('Total Pendapatan', 'Rp ' . number_format($totalPendapatan, 0, ',', '.'))
                ->description('Dari semua pesanan')
                ->descriptionIcon('heroicon-m-banknotes')
                ->color('success'),
                
            Stat::make('Deadline < 3 Hari', $deadline)
                ->description('Pesanan yang harus segera diselesaikan')
                ->descriptionIcon('heroicon-m-clock')
                ->color($deadline > 0 ? 'danger' : 'success'),
                
            Stat::make('Pesanan Selesai Bulan Ini', $pesananSelesaiBulanIni)
                ->description('Bulan ' . Carbon::now()->format('F'))
                ->descriptionIcon('heroicon-m-check-badge')
                ->color('success'),
        ];
    }
}