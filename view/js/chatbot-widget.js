window.addEventListener('load', function () {
    const chatbotWidget = document.getElementById('chatbot-widget');
    const circle = document.getElementById('chatbot-circle');
    const widget = document.getElementById('chatbot-widget');
    const chatBody = document.getElementById('chatbot-body');
    const chatHeader = document.getElementById('chatbot-header');
    const userInput = document.getElementById('user-input');
    const sendBtn = document.getElementById('send-btn');
    const apiBase = chatbotWidget?.dataset.apiBase || 'pages/main/chatbot_api.php';

    if (!circle || !widget || !chatBody || !userInput || !sendBtn) {
        return;
    }

    let xOffset = 0;
    let yOffset = 0;
    let initialX = 0;
    let initialY = 0;
    let currentX = 0;
    let currentY = 0;
    let startX = 0;
    let startY = 0;
    let isDragging = false;
    const dragThreshold = 10;

    function clampCirclePosition(x, y) {
        return {
            x: Math.max(0, Math.min(window.innerWidth - 60, x)),
            y: Math.max(0, Math.min(window.innerHeight - 60, y))
        };
    }

    function setCirclePosition(x, y) {
        const position = clampCirclePosition(x, y);
        xOffset = position.x;
        yOffset = position.y;
        currentX = position.x;
        currentY = position.y;
        circle.style.left = position.x + 'px';
        circle.style.top = position.y + 'px';
        circle.style.right = 'unset';
        circle.style.bottom = 'unset';
    }

    function initCirclePosition() {
        const savedPosition = localStorage.getItem('chatbot-pos');

        if (savedPosition) {
            try {
                const position = JSON.parse(savedPosition);
                setCirclePosition(Number(position.x || 0), Number(position.y || 0));
                return;
            } catch (error) {
                localStorage.removeItem('chatbot-pos');
            }
        }

        const rect = circle.getBoundingClientRect();
        setCirclePosition(rect.left, rect.top);
    }

    function openChatbot() {
        document.getElementById('chatbot-noti').style.display = 'none';
        widget.classList.add('open');
        chatBody.style.display = 'flex';

        const rect = circle.getBoundingClientRect();
        const widgetWidth = 350;
        const widgetHeight = 430;
        let left = rect.left + (60 - widgetWidth) / 2;
        let top = rect.top - widgetHeight - 10;

        if (left + widgetWidth > window.innerWidth - 10) {
            left = window.innerWidth - widgetWidth - 10;
        }
        if (left < 10) {
            left = 10;
        }
        if (top < 10) {
            top = rect.bottom + 10;
            if (top + widgetHeight > window.innerHeight - 10) {
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
            if (icon) {
                icon.className = 'fas fa-chevron-down';
            }
            return;
        }

        chatBody.style.display = 'none';
        widget.classList.remove('open');
        if (icon) {
            icon.className = 'fas fa-chevron-up';
        }
    }

    function startDrag(event) {
        if (event.target.closest('.chatbot-widget')) {
            return;
        }

        event.preventDefault();
        const point = event.touches ? event.touches[0] : event;
        initialX = point.clientX - xOffset;
        initialY = point.clientY - yOffset;
        startX = point.clientX;
        startY = point.clientY;
        isDragging = true;
        circle.style.cursor = 'grabbing';

        document.addEventListener('mouseup', endDrag);
        document.addEventListener('mousemove', drag);
        document.addEventListener('touchend', endDrag);
        document.addEventListener('touchmove', drag, { passive: false });
    }

    function drag(event) {
        if (!isDragging) {
            return;
        }

        event.preventDefault();
        const point = event.touches ? event.touches[0] : event;
        setCirclePosition(point.clientX - initialX, point.clientY - initialY);
    }

    function endDrag(event) {
        const point = event.changedTouches ? event.changedTouches[0] : event;
        const diffX = Math.abs(point.clientX - startX);
        const diffY = Math.abs(point.clientY - startY);

        if (diffX < dragThreshold && diffY < dragThreshold) {
            if (widget.classList.contains('open')) {
                toggleChatbot();
            } else {
                openChatbot();
            }
        } else {
            localStorage.setItem('chatbot-pos', JSON.stringify({ x: currentX, y: currentY }));
        }

        isDragging = false;
        circle.style.cursor = 'grab';
        document.removeEventListener('mouseup', endDrag);
        document.removeEventListener('mousemove', drag);
        document.removeEventListener('touchend', endDrag);
        document.removeEventListener('touchmove', drag);
    }

    function formatMessageContent(text) {
        const normalizedText = String(text || '').replace(/<br\s*\/?>/gi, '\n');
        const escapedText = normalizedText
            .replace(/&/g, '&amp;')
            .replace(/</g, '&lt;')
            .replace(/>/g, '&gt;')
            .replace(/"/g, '&quot;')
            .replace(/'/g, '&#039;');

        return escapedText.replace(/\n/g, '<br>');
    }

    function addMessage(text, sender) {
        const messages = document.getElementById('chat-messages');
        const time = new Date().toLocaleTimeString('vi-VN', { hour: '2-digit', minute: '2-digit' });
        const messageDiv = document.createElement('div');

        messageDiv.className = `message ${sender}`;
        messageDiv.innerHTML = `
            <div class="message-content">${formatMessageContent(text)}</div>
            <div class="message-time">${time}</div>
        `;
        messages.appendChild(messageDiv);
        messages.scrollTop = messages.scrollHeight;
    }

    function updateCartBadge(result) {
        if (typeof result !== 'object' || typeof result.cartQuantity === 'undefined') {
            return;
        }

        document.querySelectorAll('.glass-badge').forEach((badge) => {
            badge.textContent = String(result.cartQuantity);
        });
    }

    async function saveChat(message, responseText, matchedKeyword, responseType) {
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
        } catch (error) {
            // Chat history is useful for admin, but it must not block the customer flow.
        }
    }

    async function sendMessage() {
        const message = userInput.value.trim();
        if (!message) {
            return;
        }

        addMessage(message, 'user');
        userInput.value = '';

        const messages = document.getElementById('chat-messages');
        const typingDiv = document.createElement('div');
        typingDiv.id = 'typing-indicator';
        typingDiv.className = 'message bot';
        typingDiv.innerHTML = '<div class="message-content">🤖 Đang xử lý...</div>';
        messages.appendChild(typingDiv);
        messages.scrollTop = messages.scrollHeight;

        window.setTimeout(async () => {
            document.getElementById('typing-indicator')?.remove();

            const result = await window.FastFoodChatbotResponseService.getBotResponse(message, apiBase);
            const responseText = typeof result === 'object' ? result.response : result;
            const matchedKeyword = typeof result === 'object' ? result.keyword : '';
            const responseType = typeof result === 'object' ? result.type : 'static';

            addMessage(responseText, 'bot');
            updateCartBadge(result);
            await saveChat(message, responseText, matchedKeyword, responseType);
        }, 500);
    }

    initCirclePosition();
    window.addEventListener('resize', initCirclePosition);
    circle.addEventListener('mousedown', startDrag);
    circle.addEventListener('touchstart', startDrag, { passive: false });
    chatHeader?.addEventListener('click', toggleChatbot);
    sendBtn.addEventListener('click', sendMessage);
    userInput.addEventListener('keypress', function (event) {
        if (event.key === 'Enter') {
            sendMessage();
        }
    });

    document.querySelectorAll('#quick-buttons button').forEach((button) => {
        button.addEventListener('click', function () {
            const message = this.getAttribute('data-message');
            if (message) {
                userInput.value = message;
                sendMessage();
            }
        });
    });

    if (!localStorage.getItem('chatbot-opened')) {
        window.setTimeout(() => {
            chatBody.style.display = 'flex';
            localStorage.setItem('chatbot-opened', 'true');
        }, 3000);
    }
});
