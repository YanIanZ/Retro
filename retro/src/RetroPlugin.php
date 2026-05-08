<?php

namespace RetroTheme;

use Filament\Contracts\Plugin;
use Filament\Panel;

class RetroPlugin implements Plugin
{
    public function getId(): string
    {
        return 'retro';
    }

    public function register(Panel $panel): void
    {
        $panel->viteTheme('plugins/retro/resources/css/retro.css');
    }

    public function boot(Panel $panel): void
    {
        //
    }
}
