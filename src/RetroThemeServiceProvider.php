<?php

namespace RetroTheme;

use Illuminate\Support\ServiceProvider;
use Filament\Support\Assets\Css;
use Filament\Support\Facades\FilamentAsset;

class RetroThemeServiceProvider extends ServiceProvider
{
    public function boot(): void
    {
        FilamentAsset::register([
            Css::make('retro-theme-css', __DIR__ . '/../resources/css/retro.css'),
        ], 'retro-theme');
    }
}
