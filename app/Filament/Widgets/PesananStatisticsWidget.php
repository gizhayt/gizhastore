<?php

namespace App\Filament\Widgets;

use App\Models\Pesanan;
use Filament\Widgets\ChartWidget;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;

class PesananStatisticsWidget extends ChartWidget
{
    protected static ?string $heading = 'Statistik Pesanan';
    
    protected static ?int $sort = 1;

    protected function getData(): array
    {
        // Data untuk 6 bulan terakhir
        $months = collect(range(5, 0))->map(function ($i) {
            return Carbon::now()->subMonths($i)->format('M');
        });

        // Mendapatkan jumlah pesanan per bulan berdasarkan status
        $statusColors = [
            'pending' => '#f97316', // Orange
            'diproses' => '#3b82f6', // Blue
            'selesai' => '#22c55e', // Green
            'revisi' => '#eab308', // Yellow
            'dibatalkan' => '#ef4444', // Red
        ];
        
        $datasets = [];
        
        // Data untuk semua status pesanan
        foreach ($statusColors as $status => $color) {
            $statusData = collect(range(5, 0))
                ->map(function ($i) use ($status) {
                    $date = Carbon::now()->subMonths($i);
                    $startOfMonth = $date->copy()->startOfMonth();
                    $endOfMonth = $date->copy()->endOfMonth();
                    
                    return Pesanan::where('status', $status)
                        ->whereBetween('created_at', [$startOfMonth, $endOfMonth])
                        ->count();
                });
                
            $datasets[] = [
                'label' => ucfirst($status),
                'data' => $statusData->toArray(),
                'backgroundColor' => $color,
                'borderColor' => $color,
            ];
        }

        return [
            'labels' => $months->toArray(),
            'datasets' => $datasets,
        ];
    }

    protected function getType(): string
    {
        return 'bar';
    }
    
    protected function getOptions(): array
    {
        return [
            'plugins' => [
                'legend' => [
                    'position' => 'top',
                ],
            ],
            'scales' => [
                'y' => [
                    'beginAtZero' => true,
                    'ticks' => [
                        'precision' => 0,
                    ],
                ],
            ],
            'responsive' => true,
            'maintainAspectRatio' => false,
        ];
    }
}