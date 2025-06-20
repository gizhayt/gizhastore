<?php

namespace App\Filament\Widgets;

use App\Models\Pesanan;
use Filament\Widgets\ChartWidget;
use Illuminate\Support\Facades\DB;

class PesananDistribusiWidget extends ChartWidget
{
    protected static ?string $heading = 'Distribusi Status Pesanan';
    
    protected static ?int $sort = 4;

    protected function getData(): array
    {
        $statuses = ['pending', 'diproses', 'selesai', 'revisi', 'dibatalkan'];
        $statusColors = [
            'pending' => '#f97316', // Orange
            'diproses' => '#3b82f6', // Blue
            'selesai' => '#22c55e', // Green
            'revisi' => '#eab308', // Yellow
            'dibatalkan' => '#ef4444', // Red
        ];
        
        $data = [];
        $colors = [];
        
        foreach ($statuses as $status) {
            $count = Pesanan::where('status', $status)->count();
            $data[] = $count;
            $colors[] = $statusColors[$status];
        }
        
        return [
            'datasets' => [
                [
                    'label' => 'Jumlah Pesanan',
                    'data' => $data,
                    'backgroundColor' => $colors,
                ],
            ],
            'labels' => array_map('ucfirst', $statuses),
        ];
    }

    protected function getType(): string
    {
        return 'doughnut';
    }
    
    protected function getOptions(): array
    {
        return [
            'responsive' => true,
            'maintainAspectRatio' => false,
            'plugins' => [
                'legend' => [
                    'position' => 'right',
                ],
                'tooltip' => [
                    'callbacks' => [
                        'label' => '{{value}} pesanan',
                    ],
                ],
            ],
        ];
    }
}