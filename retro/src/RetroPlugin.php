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
        $panel->viteTheme('plugins/retro/resources/css/retro.css');
    }

    public function boot(Panel $panel): void
    {
        \Filament\Support\Facades\FilamentView::registerRenderHook(
            \Filament\View\PanelsRenderHook::BODY_END,
            fn (): string => \Illuminate\Support\Facades\Blade::render(<<<'HTML'
<script>
document.addEventListener('DOMContentLoaded', () => {
    let audioCtx = null;
    const playBeep = (freq = 400, type = 'square', duration = 0.05, vol = 0.05) => {
        if (!audioCtx) audioCtx = new (window.AudioContext || window.webkitAudioContext)();
        if(audioCtx.state === 'suspended') audioCtx.resume();
        const osc = audioCtx.createOscillator();
        const gainNode = audioCtx.createGain();
        osc.type = type;
        osc.frequency.setValueAtTime(freq, audioCtx.currentTime);
        gainNode.gain.setValueAtTime(vol, audioCtx.currentTime);
        gainNode.gain.exponentialRampToValueAtTime(0.001, audioCtx.currentTime + duration);
        osc.connect(gainNode);
        gainNode.connect(audioCtx.destination);
        osc.start();
        osc.stop(audioCtx.currentTime + duration);
    };

    const attachAudio = () => {
        document.querySelectorAll('a, button, .fi-sidebar-item-button').forEach(el => {
            if(el.dataset.audioAttached) return;
            el.dataset.audioAttached = "true";
            el.addEventListener('mouseenter', () => playBeep(800, 'square', 0.02, 0.01));
            el.addEventListener('click', () => playBeep(1200, 'sawtooth', 0.1, 0.05));
        });
    };
    attachAudio();
    const observer = new MutationObserver(() => attachAudio());
    observer.observe(document.body, { childList: true, subtree: true });

    const triggerFlicker = () => {
        if (Math.random() > 0.8) {
            document.body.classList.add('flicker');
            setTimeout(() => document.body.classList.remove('flicker'), Math.random() * 100 + 50);
        }
        setTimeout(triggerFlicker, Math.random() * 3000 + 1000);
    };
    triggerFlicker();
});
</script>
HTML
            )
        );
    }
}
