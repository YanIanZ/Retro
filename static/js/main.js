document.addEventListener('DOMContentLoaded', () => {
    
    // ---------------------------------------------------------
    // 1. BOOT SEQUENCE ANIMATION
    // ---------------------------------------------------------
    const hasBooted = sessionStorage.getItem('neo-pelican-booted');
    
    if (!hasBooted) {
        const bootScreen = document.createElement('div');
        bootScreen.id = 'boot-screen';
        bootScreen.innerHTML = `<div id="boot-text"></div>`;
        document.body.appendChild(bootScreen);
        
        const bootLines = [
            "BIOS DATE 01/01/26 14:22:01 VER 1.00",
            "CPU: NEURAL PROCESSOR 8.4 GHz",
            "Memory Test : 640K OK",
            "Initializing Hardware... OK",
            "Mounting C:\\ PELICAN_SYS... OK",
            "Loading Kernel...",
            "Starting Services...",
            "SYSTEM READY."
        ];
        
        const bootTextDiv = document.getElementById('boot-text');
        let lineIndex = 0;
        
        const typeLine = () => {
            if (lineIndex < bootLines.length) {
                bootTextDiv.innerHTML += `<div>> ${bootLines[lineIndex]}</div>`;
                lineIndex++;
                setTimeout(typeLine, Math.random() * 200 + 50);
            } else {
                setTimeout(() => {
                    bootScreen.style.opacity = '0';
                    setTimeout(() => {
                        bootScreen.remove();
                        sessionStorage.setItem('neo-pelican-booted', 'true');
                    }, 500);
                }, 800);
            }
        };
        
        setTimeout(typeLine, 500);
    }

    // ---------------------------------------------------------
    // 2. RETRO AUDIO CUES (Web Audio API)
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

    // Add event listeners to all links for audio
    document.querySelectorAll('a').forEach(link => {
        link.addEventListener('mouseenter', () => playBeep(800, 'square', 0.02, 0.02));
        link.addEventListener('click', () => playBeep(1200, 'sawtooth', 0.1, 0.05));
    });

    // ---------------------------------------------------------
    // 3. SCANLINE FLICKER
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

    // ---------------------------------------------------------
    // 4. INTERACTIVE COMMAND LINE
    // ---------------------------------------------------------
    const cmdInput = document.getElementById('cmd-input');
    if (cmdInput) {
        cmdInput.addEventListener('keydown', (e) => {
            if (e.key === 'Enter') {
                const val = cmdInput.value.trim().toLowerCase();
                if (val === 'cd home' || val === 'cd /') {
                    window.location.href = '/';
                } else if (val.startsWith('cd ')) {
                    const dir = val.replace('cd ', '').trim();
                    window.location.href = `/${dir}.html`;
                } else if (val === 'help') {
                    alert("COMMANDS:\n cd [dir] - Navigate to a page\n cd home - Go to homepage\n clear - Clear input");
                } else if (val === 'clear' || val === 'cls') {
                    cmdInput.value = '';
                } else {
                    alert(`Bad command or file name: ${val}`);
                }
                cmdInput.value = '';
            }
        });
        
        // Keep focus on click anywhere in the footer
        document.querySelector('.terminal-footer').addEventListener('click', () => {
            cmdInput.focus();
        });
    }
});
