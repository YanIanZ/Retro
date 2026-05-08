<?php

namespace RetroTheme;

use Filament\Contracts\Plugin;
use Filament\Panel;
use Filament\Support\Assets\Css;
use Filament\Support\Assets\Js;

class RetroPlugin implements Plugin
{
    public function getId(): string
    {
        return 'retro';
    }

    public function register(Panel $panel): void
    {
        // Registration logic
    }

    public function boot(Panel $panel): void
    {
        \Filament\Support\Facades\FilamentAsset::register([
            Css::make('retro-css', __DIR__ . '/../resources/css/retro.css'),
            Js::make('retro-js', __DIR__ . '/../resources/js/retro.js'),
        ], 'retro');
    }
}
