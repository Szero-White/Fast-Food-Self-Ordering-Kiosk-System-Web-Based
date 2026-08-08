/**
 * Auto Reset Timer for Kiosk Mode
 * Reset về màn hình chính sau 2 phút (120 giây) không hoạt động
 */

(function() {
    // Thời gian chờ (giây) - 30 giây cho test (có thể đổi thành 120 = 2 phút)
    const TIMEOUT_SECONDS = 120;
    let timeLeft = TIMEOUT_SECONDS;
    let timer = null;
    let warningShown = false;
    
    // Các sự kiện người dùng tương tác
    const events = ['click', 'touchstart', 'mousemove', 'keypress', 'scroll'];
    
    // Reset timer khi có tương tác
    function resetTimer() {
        timeLeft = TIMEOUT_SECONDS;
        warningShown = false;
        
        // Ẩn cảnh báo nếu đang hiện
        const warning = document.getElementById('timeout-warning');
        if (warning) {
            warning.remove();
        }
    }
    
    // Hiển thị cảnh báo
    function showWarning() {
        if (warningShown) return;
        warningShown = true;
        
        const warning = document.createElement('div');
        warning.id = 'timeout-warning';
        warning.style.cssText = `
            position: fixed;
            top: 0;
            left: 0;
            right: 0;
            background: linear-gradient(135deg, #f39c12 0%, #e74c3c 100%);
            color: white;
            padding: 20px;
            text-align: center;
            font-size: 1.2rem;
            font-weight: bold;
            z-index: 10000;
            box-shadow: 0 5px 20px rgba(0,0,0,0.3);
            animation: slideDown 0.3s ease;
        `;
        warning.innerHTML = `
            ⚠️ Bạn đang không hoạt động<br>
            <small>Hệ thống sẽ reset sau ${timeLeft} giây nữa - Chạm vào màn hình để tiếp tục</small>
        `;
        
        // Thêm animation
        const style = document.createElement('style');
        style.textContent = `
            @keyframes slideDown {
                from { transform: translateY(-100%); }
                to { transform: translateY(0); }
            }
        `;
        document.head.appendChild(style);
        
        document.body.appendChild(warning);
    }
    
    // Thực hiện reset
    function doReset() {
        // Xóa session và chuyển về welcome
        fetch('pages/main/reset_session.php')
            .then(() => {
                window.location.href = 'index.php?quanly=welcome';
            })
            .catch(() => {
                // Fallback nếu fetch lỗi - chuyển trực tiếp
                window.location.href = 'index.php?quanly=welcome';
            });
    }
    
    // Timer chính
    function startTimer() {
        timer = setInterval(function() {
            timeLeft--;
            console.log('⏱️ Time left:', timeLeft, 'seconds');
            
            // Hiển thị cảnh báo khi còn 10 giây
            if (timeLeft <= 10 && timeLeft > 0) {
                showWarning();
                // Cập nhật số giây trong cảnh báo
                const warning = document.getElementById('timeout-warning');
                if (warning) {
                    warning.innerHTML = `
                        ⚠️ Bạn đang không hoạt động<br>
                        <small>Hệ thống sẽ reset sau ${timeLeft} giây nữa - Chạm vào màn hình để tiếp tục</small>
                    `;
                }
            }
            
            // Reset khi hết giờ
            if (timeLeft <= 0) {
                console.log('⏰ Timeout! Resetting...');
                clearInterval(timer);
                doReset();
            }
        }, 1000);
    }
    
    // Lắng nghe sự kiện người dùng
    events.forEach(function(event) {
        document.addEventListener(event, resetTimer, { passive: true });
    });
    
    // Bắt đầu timer
    startTimer();
    
    // Debug: Log để kiểm tra
    console.log('✅ Kiosk timeout started: ' + TIMEOUT_SECONDS + ' seconds (thay đổi ở đầu file timeout.js)');
    console.log('📍 Sẽ cảnh báo khi còn 10 giây');
})();
