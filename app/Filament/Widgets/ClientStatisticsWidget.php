<?php

namespace App\Filament\Widgets;

use App\Models\Client;
use App\Models\Pesanan;
use App\Models\User;
use Filament\Widgets\StatsOverviewWidget as BaseWidget;
use Filament\Widgets\StatsOverviewWidget\Stat;
use Carbon\Carbon;

class ClientStatisticsWidget extends BaseWidget
{
    protected static ?int $sort = 2;
    
    protected function getStats(): array
    {
        // Jumlah total client
        $totalClients = Client::count();
        
        // Jumlah client baru bulan ini
        $newClients = Client::whereMonth('created_at', Carbon::now()->month)
            ->whereYear('created_at', Carbon::now()->year)
            ->count();
            
        // Jumlah client dengan pesanan aktif
        $clientsWithActiveOrders = Pesanan::whereIn('status', ['pending', 'diproses', 'revisi'])
            ->join('users', 'pesanan.user_id', '=', 'users.id')
            ->join('clients', 'users.id', '=', 'clients.user_id')
            ->distinct('clients.id')
            ->count();
        
        $increasePct = User::count() > 0 ? round(($newClients / User::count()) * 100, 1) : 0;
        
        return [
            Stat::make('Total Client', $totalClients)
                ->description('Semua client yang terdaftar')
                ->descriptionIcon('heroicon-m-user-group')
                ->color('primary'),
                
            Stat::make('Client Baru', $newClients)
                ->description($increasePct . '% dari bulan lalu')
                ->descriptionIcon('heroicon-m-arrow-trending-up')
                ->color('success'),
                
            Stat::make('Client dengan Pesanan Aktif', $clientsWithActiveOrders)
                ->description('Pesanan pending, diproses, atau revisi')
                ->descriptionIcon('heroicon-m-document-check')
                ->color('warning'),
        ];
    }
}