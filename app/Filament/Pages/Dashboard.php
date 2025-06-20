<?php

namespace App\Filament\Pages;

use App\Filament\Widgets\ClientStatisticsWidget;
use App\Filament\Widgets\PesananStatisticsWidget;
use App\Filament\Widgets\PesananOverviewWidget;
use App\Filament\Widgets\PesananDistribusiWidget;
use App\Filament\Widgets\PesananLayananWidget;
use Filament\Pages\Dashboard as BaseDashboard;

class Dashboard extends BaseDashboard
{
    protected static ?string $navigationIcon = 'heroicon-o-home';
    
    // Mengubah menjadi public untuk sesuai dengan class induk
    public function getHeaderWidgets(): array
    {
        return [
            PesananOverviewWidget::class,
            ClientStatisticsWidget::class,
        ];
    }
    
    // Mengubah menjadi public untuk sesuai dengan class induk
    public function getFooterWidgets(): array
    {
        return [];
    }

    // Mengubah menjadi public untuk sesuai dengan class induk
    public function getWidgets(): array
    {
        return [
            PesananStatisticsWidget::class,
            PesananDistribusiWidget::class,
            PesananLayananWidget::class,
        ];
    }
}