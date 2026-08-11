/**
 * Auto reset timer for kiosk mode.
 * Returns the customer flow to the welcome screen after a short idle period.
 */
(function () {
    const timeoutSeconds = Number(window.KIOSK_TIMEOUT_SECONDS || 120);
    const warningSeconds = Number(window.KIOSK_WARNING_SECONDS || 10);
    const activityEvents = ['click', 'touchstart', 'keydown'];
    const resetUrl = 'pages/main/reset_session.php';
    const welcomeUrl = 'index.php?quanly=welcome';

    let deadline = Date.now() + timeoutSeconds * 1000;
    let timer = null;
    let isResetting = false;

    function remainingSeconds() {
        return Math.max(0, Math.ceil((deadline - Date.now()) / 1000));
    }

    function removeWarning() {
        const warning = document.getElementById('kiosk-timeout-warning');
        if (warning) {
            warning.remove();
        }
    }

    function resetTimer() {
        if (isResetting) {
            return;
        }

        deadline = Date.now() + timeoutSeconds * 1000;
        removeWarning();
    }

    function renderWarning(secondsLeft) {
        let warning = document.getElementById('kiosk-timeout-warning');

        if (!warning) {
            warning = document.createElement('div');
            warning.id = 'kiosk-timeout-warning';
            warning.className = 'kiosk-timeout-warning';
            warning.setAttribute('role', 'status');
            warning.setAttribute('aria-live', 'polite');
            document.body.appendChild(warning);
        }

        warning.innerHTML = `
            <strong>Bạn đang tạm dừng thao tác</strong>
            <span>Hệ thống sẽ trở về màn hình chờ sau ${secondsLeft} giây. Chạm vào màn hình để tiếp tục.</span>
        `;
    }

    function redirectToWelcome() {
        window.location.replace(welcomeUrl);
    }

    function resetSessionAndRedirect() {
        if (isResetting) {
            return;
        }

        isResetting = true;
        clearInterval(timer);

        fetch(resetUrl, { cache: 'no-store' })
            .then(redirectToWelcome)
            .catch(redirectToWelcome);
    }

    function tick() {
        const secondsLeft = remainingSeconds();

        if (secondsLeft <= 0) {
            resetSessionAndRedirect();
            return;
        }

        if (secondsLeft <= warningSeconds) {
            renderWarning(secondsLeft);
            return;
        }

        removeWarning();
    }

    activityEvents.forEach(function (eventName) {
        document.addEventListener(eventName, resetTimer, { passive: true });
    });

    timer = window.setInterval(tick, 250);
})();
