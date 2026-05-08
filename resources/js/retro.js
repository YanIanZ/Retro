document.addEventListener('DOMContentLoaded', () => {
    // ---------------------------------------------------------
    // 1. RETRO AUDIO CUES (Web Audio API)
    // ---------------------------------------------------------
    let audioCtx = null;

    const playBeep = (freq = 400, type = 'square', duration = 0.05, vol = 0.05) => {
        if (!audioCtx) {
            audioCtx = new (window.AudioContext || window.webkitAudioContext)();
        }
        if(audioCtx.state === 'suspended') {
            audioCtx.resume();
        }
        
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

    // Attach to Filament navigation links and buttons
    const attachAudio = () => {
        document.querySelectorAll('a, button, .fi-sidebar-item-button').forEach(el => {
            // avoid attaching multiple times
            if(el.dataset.audioAttached) return;
            el.dataset.audioAttached = "true";

            el.addEventListener('mouseenter', () => playBeep(800, 'square', 0.02, 0.01));
            el.addEventListener('click', () => playBeep(1200, 'sawtooth', 0.1, 0.05));
        });
    };

    attachAudio();
    // Re-attach audio on Livewire navigation/DOM mutations
    const observer = new MutationObserver((mutations) => {
        attachAudio();
    });
    observer.observe(document.body, { childList: true, subtree: true });

    // ---------------------------------------------------------
    // 2. SCANLINE FLICKER
    // ---------------------------------------------------------
    const triggerFlicker = () => {
        if (Math.random() > 0.8) {
            document.body.classList.add('flicker');
            setTimeout(() => {
                document.body.classList.remove('flicker');
            }, Math.random() * 100 + 50);
        }
        setTimeout(triggerFlicker, Math.random() * 3000 + 1000);
    };
    triggerFlicker();
});
