document.addEventListener('DOMContentLoaded', () => {
    let seconds = 10;
    const countdownEl = document.getElementById('countdown');

    if (!countdownEl) {
        return;
    }

    const timer = window.setInterval(() => {
        seconds -= 1;
        countdownEl.textContent = seconds;

        if (seconds <= 0) {
            window.clearInterval(timer);
            window.location.href = 'index.php?quanly=welcome';
        }
    }, 1000);
});
