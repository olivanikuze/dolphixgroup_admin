// Apply saved mode on page load
document.addEventListener('DOMContentLoaded', () => {
    const mode = localStorage.getItem('mode') || 'light-mode';
    document.body.className = mode;
    updateIcon(mode);
});

function toggleMode() {
    const body = document.body;
    body.classList.toggle('dark-mode');
    body.classList.toggle('light-mode');
    
    const mode = body.classList.contains('dark-mode') ? 'dark-mode' : 'light-mode';
    localStorage.setItem('mode', mode);
    updateIcon(mode);
}

function updateIcon(mode) {
    const icon = document.querySelector('.mode-toggle');
    icon.className = `fas mode-toggle ${mode === 'dark-mode' ? 'fa-sun' : 'fa-moon'}`;
}
