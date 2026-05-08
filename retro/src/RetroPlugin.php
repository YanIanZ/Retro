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
        // Inject CSS and JS via Filament assets
        $panel->assets([
            Css::make('retro-css', __DIR__ . '/../resources/css/retro.css'),
            Js::make('retro-js', __DIR__ . '/../resources/js/retro.js'),
        ]);
    }

    public function boot(Panel $panel): void
    {
        // Boot logic
    }
}
