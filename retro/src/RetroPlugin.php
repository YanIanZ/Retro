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
        \Filament\Support\Facades\FilamentView::registerRenderHook(
            \Filament\View\PanelsRenderHook::BODY_END,
            fn (): string => \Illuminate\Support\Facades\Blade::render(<<<'HTML'
<script>
document.addEventListener('DOMContentLoaded', () => {
    /* ── Brutalist Micro-Interactions ── */
    let audioCtx = null;
    const playClick = (freq = 220, dur = 0.03, vol = 0.015) => {
        try {
            if (!audioCtx) audioCtx = new (window.AudioContext || window.webkitAudioContext)();
            if (audioCtx.state === 'suspended') audioCtx.resume();
            const o = audioCtx.createOscillator();
            const g = audioCtx.createGain();
            o.type = 'sine';
            o.frequency.setValueAtTime(freq, audioCtx.currentTime);
            g.gain.setValueAtTime(vol, audioCtx.currentTime);
            g.gain.exponentialRampToValueAtTime(0.0001, audioCtx.currentTime + dur);
            o.connect(g);
            g.connect(audioCtx.destination);
            o.start();
            o.stop(audioCtx.currentTime + dur);
        } catch(e) {}
    };

    /* ── Attach click sounds ── */
    const wire = () => {
        document.querySelectorAll('a, button, .fi-sidebar-item-button, .fi-btn, .fi-tab').forEach(el => {
            if (el.dataset.bw) return;
            el.dataset.bw = '1';
            el.addEventListener('click', () => playClick(340 + Math.random() * 200, 0.04));
        });
    };
    wire();
    new MutationObserver(wire).observe(document.body, { childList: true, subtree: true });

    /* ── Shadow press effect on brutalist cards ── */
    document.addEventListener('mousedown', e => {
        const card = e.target.closest('.fi-card, .fi-section, .fi-ta');
        if (card) {
            card.style.transition = 'transform 0.08s, box-shadow 0.08s';
            card.style.transform = 'translate(3px, 3px)';
            card.style.boxShadow = '1px 1px 0 var(--hair)';
        }
    });
    document.addEventListener('mouseup', () => {
        document.querySelectorAll('.fi-card, .fi-section, .fi-ta').forEach(c => {
            c.style.transform = '';
            c.style.boxShadow = '';
        });
    });

    /* ── Stagger sidebar items on load ── */
    document.querySelectorAll('.fi-sidebar-item').forEach((item, i) => {
        item.style.opacity = '0';
        item.style.transform = 'translateX(-12px)';
        item.style.transition = 'opacity 0.3s ease, transform 0.3s ease';
        setTimeout(() => {
            item.style.opacity = '1';
            item.style.transform = 'translateX(0)';
        }, 60 * i);
    });
});
</script>
HTML
            )
        );
    }
}
