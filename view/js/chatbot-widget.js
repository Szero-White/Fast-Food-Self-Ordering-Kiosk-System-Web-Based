window.addEventListener('load', function() {
    const chatbotWidget = document.getElementById('chatbot-widget');
    const apiBase = chatbotWidget?.dataset.apiBase || 'pages/main/chatbot_api.php';

    // Load saved position
    let xOffset = 0, yOffset = 0;
    let initialX, initialY;
    let currentX = 0, currentY = 0;
    let isDragging = false;
    let startX = 0, startY = 0;
    const dragThreshold = 10;

    const circle = document.getElementById('chatbot-circle');
    const widget = document.getElementById('chatbot-widget');
    const chatBody = document.getElementById('chatbot-body');

    // Initialize position from actual rendered position or saved localStorage
    function initCirclePosition() {
        const savedPos = localStorage.getItem('chatbot-pos');
        const vw = window.innerWidth;
        const vh = window.innerHeight;
        if (savedPos) {
            const pos = JSON.parse(savedPos);
            let x = Math.max(0, Math.min(vw - 60, pos.x));
            let y = Math.max(0, Math.min(vh - 60, pos.y));
            circle.style.left = x + 'px';
            circle.style.top  = y + 'px';
            circle.style.right = 'unset';
            circle.style.bottom = 'unset';
            xOffset = x;
            yOffset = y;
        } else {
            // No saved position: read actual rendered position from CSS
            const rect = circle.getBoundingClientRect();
            xOffset = rect.left;
            yOffset = rect.top;
            circle.style.left = rect.left + 'px';
            circle.style.top  = rect.top + 'px';
            circle.style.right = 'unset';
            circle.style.bottom = 'unset';
        }
    }

    initCirclePosition();
    window.addEventListener('resize', initCirclePosition);

    // Attach drag event listeners
    circle.addEventListener('mousedown', startDrag);
    circle.addEventListener('touchstart', startDrag, { passive: false });

    function startDrag(e) {
        if (e.target.closest('.chatbot-widget')) return;
        e.preventDefault();
        const cx = e.clientX || (e.touches ? e.touches[0].clientX : 0);
        const cy = e.clientY || (e.touches ? e.touches[0].clientY : 0);
        initialX = cx - xOffset;
        initialY = cy - yOffset;
        startX = cx;
        startY = cy;
        isDragging = true;
        circle.style.cursor = 'grabbing';
        document.addEventListener('mouseup', endDrag);
        document.addEventListener('mousemove', drag);
        document.addEventListener('touchend', endDrag);
        document.addEventListener('touchmove', drag);
    }
    
    function drag(e) {
        if (!isDragging) return;
        e.preventDefault();
        let clientX, clientY;
        if (e.type === 'touchmove') {
            clientX = e.touches[0].clientX;
            clientY = e.touches[0].clientY;
        } else {
            clientX = e.clientX;
            clientY = e.clientY;
        }
        currentX = clientX - initialX;
        currentY = clientY - initialY;
        
        // Giới hạn trong màn hình
        currentX = Math.max(0, Math.min(window.innerWidth - 60, currentX));
        currentY = Math.max(0, Math.min(window.innerHeight - 60, currentY));
        
        xOffset = currentX;
        yOffset = currentY;
        circle.style.left = currentX + 'px';
        circle.style.top = currentY + 'px';
        circle.style.right = 'auto';
        circle.style.bottom = 'auto';
    }
    
    function endDrag(e) {
        let endX, endY;
        if (e.type === 'touchend') {
            endX = e.changedTouches[0].clientX;
            endY = e.changedTouches[0].clientY;
        } else {
            endX = e.clientX;
            endY = e.clientY;
        }
        let diffX = Math.abs(endX - startX);
        let diffY = Math.abs(endY - startY);
        if (diffX < dragThreshold && diffY < dragThreshold) {
            if (widget.classList.contains('open')) {
                toggleChatbot();
            } else {
                openChatbot();
            }
        } else {
            // Kiểm tra xem vị trí có hợp lệ trước khi lưu
            if (currentX >= 0 && currentX + 60 <= window.innerWidth && 
                currentY >= 0 && currentY + 60 <= window.innerHeight) {
                localStorage.setItem('chatbot-pos', JSON.stringify({x: currentX, y: currentY}));
            }
        }
        initialX = currentX;
        initialY = currentY;
        isDragging = false;
        circle.style.cursor = 'grab';
        document.removeEventListener('mouseup', endDrag);
        document.removeEventListener('mousemove', drag);
        document.removeEventListener('touchend', endDrag);
        document.removeEventListener('touchmove', drag);
    }
    
    function openChatbot() {
        document.getElementById('chatbot-noti').style.display = 'none';
        widget.classList.add('open');
        chatBody.style.display = 'flex';
        const rect = circle.getBoundingClientRect();
        const widgetWidth = 350;
        const widgetHeight = 430;
        
        // Tính left: căn giữa với circle
        let left = rect.left + (60 - widgetWidth) / 2;
        // Giới hạn left không được âm và không vượt ra bên phải
        if (left + widgetWidth > window.innerWidth - 10) {
            left = window.innerWidth - widgetWidth - 10;
        }
        if (left < 10) {
            left = 10;
        }
        
        // Tính top: ưu tiên đặt trên circle, nếu không có chỗ thì đặt dưới
        let top = rect.top - widgetHeight - 10;
        
        if (top < 10) {
            // Không đủ chỗ trên, thử đặt dưới
            top = rect.bottom + 10;
            if (top + widgetHeight > window.innerHeight - 10) {
                // Không đủ chỗ dưới, đặt ở trên nhưng giới hạn từ top 10
                top = 10;
            }
        }
        
        widget.style.left = left + 'px';
        widget.style.top = top + 'px';
    }
    
    function toggleChatbot() {
        const icon = document.getElementById('chatbot-toggle-icon');
        if (chatBody.style.display === 'none') {
            chatBody.style.display = 'flex';
            icon.className = 'fas fa-chevron-down';
        } else {
            chatBody.style.display = 'none';
            widget.classList.remove('open');
            icon.className = 'fas fa-chevron-up';
        }
    }

    // Attach header click listener
    const chatHeader = document.getElementById('chatbot-header');
    if (chatHeader) {
        chatHeader.addEventListener('click', toggleChatbot);
    }

    function sendQuickMessage(message) {
        document.getElementById('user-input').value = message;
        sendMessage();
    }

    async function sendMessage() {
        const input = document.getElementById('user-input');
        const message = input.value.trim();
        if (!message) return;

        addMessage(message, 'user');
        input.value = '';

        const messages = document.getElementById('chat-messages');
        const typingDiv = document.createElement('div');
        typingDiv.id = 'typing-indicator';
        typingDiv.className = 'message bot';
        typingDiv.innerHTML = '<div class="message-content">🤖 Đang tìm kiếm...</div>';
        messages.appendChild(typingDiv);
        messages.scrollTop = messages.scrollHeight;

        setTimeout(async () => {
            document.getElementById('typing-indicator')?.remove();
            const result = await window.FastFoodChatbotResponseService.getBotResponse(message, apiBase);
            // result có thể là string (cũ) hoặc object (mới)
            const responseText = typeof result === 'object' ? result.response : result;
            const matchedKeyword = typeof result === 'object' ? result.keyword : '';
            const responseType = typeof result === 'object' ? result.type : 'static';

            addMessage(responseText, 'bot');

            // Lưu lịch sử chat
            try {
                await fetch(apiBase + '?action=save_chat', {
                    method: 'POST',
                    headers: { 'Content-Type': 'application/json' },
                    body: JSON.stringify({
                        user_message: message,
                        bot_response: responseText,
                        matched_keyword: matchedKeyword,
                        response_type: responseType
                    })
                });
            } catch(e) {}
        }, 800);
    }

    // Attach input event listeners
    const userInput = document.getElementById('user-input');
    const sendBtn = document.getElementById('send-btn');

    if (userInput) {
        userInput.addEventListener('keypress', function(e) {
            if (e.key === 'Enter') sendMessage();
        });
    }
    if (sendBtn) {
        sendBtn.addEventListener('click', sendMessage);
    }

    // Attach quick button listeners
    const quickButtons = document.querySelectorAll('#quick-buttons button');
    quickButtons.forEach(btn => {
        btn.addEventListener('click', function() {
            const msg = this.getAttribute('data-message');
            if (msg) {
                userInput.value = msg;
                sendMessage();
            }
        });
    });

    function addMessage(text, sender) {
        const messages = document.getElementById('chat-messages');
        const time = new Date().toLocaleTimeString('vi-VN', { hour: '2-digit', minute: '2-digit' });
        
        const messageDiv = document.createElement('div');
        messageDiv.className = `message ${sender}`;
        messageDiv.innerHTML = `
            <div class="message-content">${text}</div>
            <div class="message-time">${time}</div>
        `;
        messages.appendChild(messageDiv);
        messages.scrollTop = messages.scrollHeight;
    }

    // Auto open on first visit
    if (!localStorage.getItem('chatbot-opened')) {
        setTimeout(() => {
            document.getElementById('chatbot-body').style.display = 'flex';
            localStorage.setItem('chatbot-opened', 'true');
        }, 3000);
    }
});
