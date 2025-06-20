<?php

namespace App\Filament\Widgets;

use App\Models\Pesanan;
use App\Models\Layanan;
use Filament\Widgets\ChartWidget;
use Illuminate\Support\Facades\DB;

class PesananLayananWidget extends ChartWidget
{
    protected static ?string $heading = 'Pesanan per Layanan';
    
    protected static ?int $sort = 5;

    protected function getData(): array
    {
        // Mendapatkan 5 layanan dengan pesanan terbanyak
        $topLayanan = Pesanan::select('layanan_id', DB::raw('count(*) as total'))
            ->groupBy('layanan_id')
            ->orderBy('total', 'desc')
            ->limit(5)
            ->get();
            
        $labels = [];
        $data = [];
        
        // Warna acak untuk chart
        $colors = ['#3b82f6', '#ef4444', '#22c55e', '#f97316', '#eab308', '#a855f7', '#ec4899'];
            
        foreach ($topLayanan as $index => $item) {
            $layanan = Layanan::find($item->layanan_id);
            if ($layanan) {
                $labels[] = $layanan->nama;
                $data[] = $item->total;
            }
        }
        
        return [
            'datasets' => [
                [
                    'label' => 'Jumlah Pesanan',
                    'data' => $data,
                    'backgroundColor' => array_slice($colors, 0, count($data)),
                ],
            ],
            'labels' => $labels,
        ];
    }

    protected function getType(): string
    {
        return 'pie';
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