/**
 * Auto Reset Timer for Kiosk Mode
 * Trở về màn hình chờ sau 60 giây không có thao tác chủ động.
 */
(function () {
    const TIMEOUT_SECONDS = 60;
    const WARNING_SECONDS = 10;
    let timeLeft = TIMEOUT_SECONDS;
    let timer = null;
    let warningShown = false;

    // Chỉ thao tác chủ động mới gia hạn phiên kiosk.
    const events = ['click', 'touchstart', 'keydown'];

    function removeWarning() {
        const warning = document.getElementById('timeout-warning');
        if (warning) {
            warning.remove();
        }
    }

    function resetTimer() {
        timeLeft = TIMEOUT_SECONDS;
        warningShown = false;
        removeWarning();
    }

    function showWarning() {
        let warning = document.getElementById('timeout-warning');

        if (!warning) {
            warning = document.createElement('div');
            warning.id = 'timeout-warning';
            warning.style.cssText = `
                position: fixed;
                top: 0;
                left: 0;
                right: 0;
                background: linear-gradient(135deg, #f39c12 0%, #e74c3c 100%);
                color: white;
                padding: 18px 20px;
                text-align: center;
                font-size: 1.1rem;
                font-weight: 700;
                z-index: 10000;
                box-shadow: 0 5px 20px rgba(0,0,0,0.3);
            `;
            document.body.appendChild(warning);
        }

        warning.innerHTML = `
            ⚠️ Bạn đang không hoạt động<br>
            <small>Hệ thống sẽ trở về màn hình chờ sau ${timeLeft} giây nữa - Chạm vào màn hình để tiếp tục</small>
        `;
        warningShown = true;
    }

    function doReset() {
        clearInterval(timer);
        fetch('pages/main/reset_session.php')
            .then(function () {
                window.location.href = 'index.php?quanly=welcome';
            })
            .catch(function () {
                window.location.href = 'index.php?quanly=welcome';
            });
    }

    function startTimer() {
        timer = setInterval(function () {
            timeLeft--;

            if (timeLeft <= WARNING_SECONDS && timeLeft > 0) {
                showWarning();
            }

            if (timeLeft <= 0) {
                doReset();
            }
        }, 1000);
    }

    events.forEach(function (eventName) {
        document.addEventListener(eventName, resetTimer, { passive: true });
    });

    startTimer();
})();
