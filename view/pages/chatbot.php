<?php
$chatbot_api = 'pages/main/chatbot_api.php';
?>
<script>window.chatbotApiBase = "<?php echo $chatbot_api; ?>";</script>
<!-- AI Chatbot Widget - Draggable Circle -->
<div class="chatbot-circle" id="chatbot-circle">
    <span class="chatbot-icon">🤖</span>
    <span class="chatbot-notification" id="chatbot-noti">1</span>
</div>

<div class="chatbot-widget" id="chatbot-widget">
    <div class="chatbot-header" id="chatbot-header">
        <span>🤖 FastFood AI</span>
        <i class="fas fa-chevron-down" id="chatbot-toggle-icon"></i>
    </div>
    <div class="chatbot-body" id="chatbot-body">
        <div class="chat-messages" id="chat-messages">
            <div class="message bot">
                <div class="message-content">Xin chào! Tôi là trợ lý AI của FastFood. Tôi có thể giúp bạn tìm hiểu về thực đơn, giá cả, hoặc khuyến mãi. Bạn muốn hỏi gì?</div>
                <div class="message-time">Vừa xong</div>
            </div>
        </div>
        <div class="quick-buttons" id="quick-buttons">
            <button data-message="Thực đơn có gì?">Thực đơn</button>
            <button data-message="Giá món ăn">Giá cả</button>
            <button data-message="Khuyến mãi">Khuyến mãi</button>
            <button data-message="Địa chỉ">Địa chỉ</button>
        </div>
        <div class="chat-input">
            <input type="text" id="user-input" placeholder="Nhập câu hỏi...">
            <button id="send-btn">📤</button>
        </div>
    </div>
</div>

<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">

<script>
window.addEventListener('load', function() {
    // Test API connectivity immediately on load
    const apiBase = window.chatbotApiBase || 'pages/main/chatbot_api.php';
    fetch(apiBase + '?action=get_products')
        .then(r => r.json())
        .then(d => console.log('[CHATBOT-TEST] API OK:', d.count, 'products'))
        .catch(e => console.error('[CHATBOT-TEST] API FAILED:', e.message, '| URL:', apiBase));
    
    // Chatbot AI Logic
    const botResponses = {
        // Greetings
        'chao': 'Xin chào! 👋 Tôi là trợ lý AI của FastFood. Hôm nay bạn muốn tìm hiểu gì nào?',
        'xin chao': 'Xin chào! 👋 Tôi là trợ lý AI của FastFood. Hôm nay bạn muốn tìm hiểu gì nào?',
        'hello': 'Hello! 👋 Tôi là trợ lý AI của FastFood. Bạn cần giúp gì không?',
        'hi': 'Hi! 👋 FastFood AI đây. Bạn muốn hỏi gì?',
        'hey': 'Hey! 👋 FastFood AI đây. Mình giúp gì được cho bạn?',
        // Name
        'ten': 'Tôi là FastFood AI 🤖 - trợ lý ảo của nhà hàng FastFood.',
        'la ai': 'Tôi là FastFood AI 🤖 - trợ lý ảo của nhà hàng FastFood.',
        // Thanks
        'cam on': 'Không có gì! 😊 Rất vui được giúp bạn. Nếu cần hỗ trợ thêm, cứ hỏi nhé!',
        'thanks': 'Không có gì! 😊 Rất vui được giúp bạn. Nếu cần hỗ trợ thêm, cứ hỏi nhé!',
        // Goodbye
        'tam biet': 'Tạm biệt! 👋 Chúc bạn có một bữa ăn ngon miệng. Hẹn gặp lại!',
        'bye': 'Tạm biệt! 👋 Chúc bạn có một bữa ăn ngon miệng. Hẹn gặp lại!',
        'hen gap lai': 'Hẹn gặp lại bạn! 👋 Chúc bạn ngon miệng!',
        // Help
        'hoi': 'Bạn có thể hỏi tôi về: thực đơn, giá cả, khuyến mãi, địa chỉ, giờ mở cửa, đặt hàng hoặc giao hàng.',
        'giup': 'Bạn có thể hỏi tôi về: thực đơn, giá cả, khuyến mãi, địa chỉ, giờ mở cửa, đặt hàng hoặc giao hàng.',
        'giup do': 'Bạn có thể hỏi tôi về: thực đơn, giá cả, khuyến mãi, địa chỉ, giờ mở cửa, đặt hàng hoặc giao hàng.',
        'tu van': 'Bạn có thể hỏi tôi về: thực đơn, giá cả, khuyến mãi, địa chỉ, giờ mở cửa, đặt hàng hoặc giao hàng.',
        // Location
        'dia chi': '📍 Cửa hàng của chúng tôi tại: Quận 7, Thành phố Hồ Chí Minh. Hotline: 1900 6099.',
        'o dau': '📍 Cửa hàng của chúng tôi tại: Quận 7, Thành phố Hồ Chí Minh. Hotline: 1900 6099.',
        'lien he': '📞 Hotline: 1900 6099 | 📍 Địa chỉ: Quận 7, TP.HCM | 📧 Email: congtoan2k4@gmail.com',
        'sdt': '📞 Hotline: 1900 6099 | 📍 Địa chỉ: Quận 7, TP.HCM | 📧 Email: congtoan2k4@gmail.com',
        // Opening hours
        'gio mo cua': '⏰ Cửa hàng mở cửa từ 9:00 sáng đến 22:00 tối, cả tuần kể cả ngày lễ. Đến sớm để chọn món ngon nhé!',
        'mo cua': '⏰ Cửa hàng mở cửa từ 9:00 sáng đến 22:00 tối, cả tuần kể cả ngày lễ.',
        'dong cua': '⏰ Cửa hàng đóng cửa lúc 22:00 tối. Hôm nay còn mở nếu bạn đến trước 22:00 nhé!',
        // Order
        'dat hang': '📞 Bạn có thể gọi hotline 1900 6099 hoặc đến trực tiếp cửa hàng để đặt món. Chúng tôi phục vụ tận nơi tại quán!',
        'dat mon': '📞 Bạn có thể gọi hotline 1900 6099 hoặc đến trực tiếp cửa hàng để đặt món. Chúng tôi phục vụ tận nơi tại quán!',
        'mua': '📞 Bạn có thể gọi hotline 1900 6099 hoặc đến trực tiếp cửa hàng để đặt món.',
        // Delivery
        'ship': '🛵 Hiện tại chúng tôi chỉ phục vụ tại cửa hàng. Bạn đến trực tiếp để thưởng thức món ngon nóng hổi nhé!',
        'giao hang': '🛵 Hiện tại chúng tôi chỉ phục vụ tại cửa hàng. Bạn đến trực tiếp để thưởng thức món ngon nóng hổi nhé!',
        'mang ve': '🛍️ Bạn có thể đến cửa hàng đặt món và mang về. Chúng tôi hỗ trợ đóng gói cẩn thận!'
    };

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
        console.log('Opening chatbot');
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
        console.log('Chatbot opened at', left, top);
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
            const result = await getBotResponse(message);
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
            } catch(e) {
                console.log('[CHATBOT] Failed to save chat history:', e);
            }
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

    async function getBotResponse(message) {
        try {
            // Manual Vietnamese normalization - handles both upper and lowercase without toLowerCase
            const normalizeVN = (str) => {
                return str
                    .replace(/[àáạảãâầấậẩẫăằắặẳẵÀÁẠẢÃÂẦẤẬẨẪĂẰẮẶẲẴ]/g, 'a')
                    .replace(/[èéẹẻẽêềếệểễÈÉẸẺẼÊỀẾỆỂỄ]/g, 'e')
                    .replace(/[ìíịỉĩÌÍỊỈĨ]/g, 'i')
                    .replace(/[òóọỏõôồốộổỗơờớợởỡÒÓỌỎÕÔỒỐỘỔỖƠỜỚỢỞỠ]/g, 'o')
                    .replace(/[ùúụủũưừứựửữÙÚỤỦŨƯỪỨỰỬỮ]/g, 'u')
                    .replace(/[ỳýỵỷỹỲÝỴỶỸ]/g, 'y')
                    .replace(/[đĐ]/g, 'd')
                    .toLowerCase();
            };
            const msgNorm = normalizeVN(message);
            const hasKeyword = (keywords) => keywords.some(k => {
                const normK = normalizeVN(k);
                // Kiểm tra word boundary: từ phải đứng riêng, không phải substring
                const pattern = new RegExp('(?:^|\\s)' + normK.replace(/[.*+?^${}()|[\]\\]/g, '\\$&') + '(?:$|\\s)');
                return pattern.test(msgNorm);
            });
            
            console.log('[CHATBOT] User message:', message, '| normalized:', msgNorm);
            
            // API base URL - configurable via PHP variable
            const apiBase = window.chatbotApiBase || 'pages/main/chatbot_api.php';
            console.log('[CHATBOT] API base:', apiBase);
            
            // Menu / Thực đơn
            if (hasKeyword(['thực đơn', 'thuc don', 'menu', 'món ăn', 'mon an', 'có gì', 'co gi', 'danh sách', 'danh sach'])) {
            console.log('[DEBUG-MENU] Entered menu IF block');
            try {
                const url = apiBase + '?action=get_products';
                console.log('[DEBUG-MENU] Fetching:', url);
                const res = await fetch(url);
                console.log('[DEBUG-MENU] Fetch response status:', res.status, 'ok:', res.ok);
                if (!res.ok) {
                    console.error('[DEBUG-MENU] API error - not ok:', res.statusText);
                    throw new Error('API responded with status ' + res.status);
                }
                const data = await res.json();
                console.log('[DEBUG-MENU] Data received, count:', data.count, 'success:', data.success);
                if (data.success && data.count > 0) {
                    let reply = '🍕 Hiện tại chúng tôi có ' + data.count + ' món đang bán:\n';
                    data.data.slice(0, 5).forEach((p, i) => {
                        reply += (i+1) + '. ' + p.tensanpham + ' - ' + parseInt(p.giasp).toLocaleString() + 'đ\n';
                    });
                    if (data.count > 5) reply += '... và ' + (data.count - 5) + ' món khác!';
                    console.log('[DEBUG-MENU] Returning product list');
                    return {response: reply, keyword: 'thuc don', type: 'api_products'};
                } else {
                    console.log('[DEBUG-MENU] API success but no data or not success');
                    return {response: '🍽️ Hiện tại không có món nào trong thực đơn.', keyword: 'thuc don', type: 'api_products'};
                }
            } catch(e) {
                console.error('[DEBUG-MENU] API error:', e.message);
                console.error('[DEBUG-MENU] Error stack:', e.stack);
                // If API fails, don't fallthrough - return an error message
                return {response: '❌ Không thể kết nối tới server. Vui lòng thử lại sau!', keyword: '', type: 'error'};
            }
        }
        
        // Giá cả
        if (hasKeyword(['giá', 'gia', 'bao nhiêu', 'bao nhieu', 'tiền', 'tien', 'đắt', 'dat', 'rẻ', 're'])) {
            const productMatch = message.match(/giá?\s+(.+?)(?:\s+bao|\s+nhieu|$)/i);
            if (productMatch) {
                try {
                    const res = await fetch(apiBase + '?action=search_product&keyword=' + encodeURIComponent(productMatch[1]));
                    const data = await res.json();
                    if (data.success && data.data.length > 0) {
                        const p = data.data[0];
                        return {response: '💰 ' + p.tensanpham + ' có giá ' + parseInt(p.giasp).toLocaleString() + 'đ. Còn ' + p.soluong + ' phần!', keyword: 'gia', type: 'api_price'};
                    } else {
                        return {response: 'Không tìm thấy món này. Giá trung bình khoảng ' + (data.success ? parseInt(data.data.avg_price || 0).toLocaleString() + 'đ' : 'không rõ') + '.', keyword: 'gia', type: 'api_price'};
                    }
                } catch(e) {
                    console.error('Search error:', e);
                    return {response: '❌ Lỗi kết nối server khi tìm giá món.', keyword: '', type: 'error'};
                }
            }
            try {
                const res = await fetch(apiBase + '?action=get_price_range');
                const data = await res.json();
                if (data.success) {
                    return {response: '💵 Giá từ ' + parseInt(data.data.min_price).toLocaleString() + 'đ đến ' + parseInt(data.data.max_price).toLocaleString() + 'đ.', keyword: 'gia', type: 'api_price'};
                } else {
                    return {response: '❌ API trả về lỗi: ' + (data.message || 'không rõ'), keyword: '', type: 'error'};
                }
            } catch(e) {
                console.error('Price range error:', e);
                return {response: '❌ Không thể lấy khoảng giá. Vui lòng thử lại!', keyword: '', type: 'error'};
            }
        }

        // Khuyến mãi
        if (hasKeyword(['khuyến mãi', 'khuyen mai', 'giảm giá', 'giam gia', 'ưu đãi', 'uu dai', 'sale'])) {
            try {
                const res = await fetch(apiBase + '?action=get_promotions');
                const data = await res.json();
                if (data.success && data.data.length > 0) {
                    let reply = '🎉 Khuyến mãi:\n';
                    data.data.forEach((p, i) => { reply += (i+1) + '. ' + p.tenbaiviet + '\n'; });
                    return {response: reply, keyword: 'khuyen mai', type: 'api_promo'};
                } else {
                    return {response: '🎊 Hiện tại không có khuyến mãi nào.', keyword: 'khuyen mai', type: 'api_promo'};
                }
            } catch(e) {
                console.error('Promotions error:', e);
                return {response: '❌ Lỗi kết nối khi lấy khuyến mãi.', keyword: '', type: 'error'};
            }
        }

        // Tồn kho
        if (hasKeyword(['còn', 'con', 'hết', 'het', 'tồn kho', 'ton kho', 'có không'])) {
            const productMatch = message.match(/(?:còn|het|hết)\s+(.+?)(?:\s+không|$)/i) || message.match(/(.+?)\s+(?:còn|het|hết)/i);
            if (productMatch) {
                try {
                    const res = await fetch(apiBase + '?action=check_stock&product=' + encodeURIComponent(productMatch[1]));
                    const data = await res.json();
                    if (data.success) {
                        if (data.data.soluong > 0) return {response: '✅ Còn ' + data.data.soluong + ' phần ' + data.data.tensanpham + '!', keyword: 'con hang', type: 'api_stock'};
                        else return {response: '❌ ' + data.data.tensanpham + ' đã hết hàng.', keyword: 'het hang', type: 'api_stock'};
                    } else {
                        return {response: 'Không tìm thấy món này.', keyword: '', type: 'api_stock'};
                    }
                } catch(e) {
                    console.error('Stock check error:', e);
                    return {response: '❌ Lỗi kết nối khi kiểm tra tồn kho.', keyword: '', type: 'error'};
                }
            }
        }
        
        // Fallback to static responses
        for (const key in botResponses) {
            const normKey = normalizeVN(key);
            const keyPattern = new RegExp('(?:^|\\s)' + normKey.replace(/[.*+?^${}()|[\]\\]/g, '\\$&') + '(?:$|\\s)');
            if (keyPattern.test(msgNorm)) return {response: botResponses[key], keyword: key, type: 'static'};
        }
        
        return {response: 'Xin lỗi, tôi chưa hiểu ý bạn lắm 😅 Bạn thử hỏi bằng tiếng Việt không dấu hoặc dùng các từ khóa như:<br>• "Thực đơn có gì?"<br>• "Giá pizza bao nhiêu?"<br>• "Còn gà rán không?"<br>• "Khuyến mãi hiện tại"<br>• "Địa chỉ cửa hàng"<br>• "Giờ mở cửa"', keyword: '', type: 'fallback'};
        } catch (e) {
            console.error('[CHATBOT] CRITICAL ERROR:', e);
            return {response: '❌ Bot gặp lỗi nghiêm trọng: ' + e.message + '. Vui lòng mở F12 Console để xem chi tiết.', keyword: '', type: 'error'};
        }
    }

    // Auto open on first visit
    if (!localStorage.getItem('chatbot-opened')) {
        setTimeout(() => {
            document.getElementById('chatbot-body').style.display = 'flex';
            localStorage.setItem('chatbot-opened', 'true');
        }, 3000);
    }
});
</script>

<style>
    /* Draggable Circle Button */
    .chatbot-circle {
        position: fixed;
        left: unset;
        top: unset;
        bottom: 20px;
        right: 20px;
        width: 60px;
        height: 60px;
        background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
        border-radius: 50%;
        display: flex;
        align-items: center;
        justify-content: center;
        cursor: grab;
        z-index: 10000;
        box-shadow: 0 4px 15px rgba(102, 126, 234, 0.4);
        transition: transform 0.2s, box-shadow 0.2s;
        user-select: none;
    }
    .chatbot-circle:active { cursor: grabbing; transform: scale(1.1); box-shadow: 0 6px 20px rgba(102, 126, 234, 0.6); }
    .chatbot-circle:hover { transform: scale(1.05); }
    .chatbot-circle .chatbot-icon { font-size: 28px; }
    .chatbot-circle .chatbot-notification {
        position: absolute;
        top: -2px;
        right: -2px;
        background: #ff4757;
        color: white;
        font-size: 12px;
        font-weight: bold;
        width: 22px;
        height: 22px;
        border-radius: 50%;
        display: flex;
        align-items: center;
        justify-content: center;
        border: 2px solid white;
    }
    .chatbot-circle.hidden { display: none; }
    
    /* Chat Widget */
    .chatbot-widget {
        position: fixed;
        width: 350px;
        background: white;
        border-radius: 20px;
        box-shadow: 0 10px 40px rgba(0,0,0,0.2);
        z-index: 9999;
        overflow: hidden;
        font-family: 'Segoe UI', sans-serif;
        display: none;
    }
    .chatbot-widget.open { display: block; }
    .chatbot-header {
        background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
        color: white;
        padding: 15px 20px;
        display: flex;
        justify-content: space-between;
        align-items: center;
        cursor: pointer;
        font-weight: bold;
        border-radius: 20px 20px 0 0;
        user-select: none;
    }
    .chatbot-body {
        display: none;
        flex-direction: column;
        height: 400px;
        background: #f8f9fa;
    }
    .chat-messages {
        flex: 1;
        overflow-y: auto;
        padding: 20px;
    }
    .message { margin-bottom: 15px; max-width: 80%; }
    .message.user { margin-left: auto; }
    .message-content {
        padding: 12px 15px;
        border-radius: 15px;
        font-size: 0.95rem;
        line-height: 1.5;
    }
    .message.bot .message-content { background: white; color: #333; border: 1px solid #e0e0e0; }
    .message.user .message-content { background: linear-gradient(135deg, #667eea 0%, #764ba2 100%); color: white; }
    .message-time { font-size: 0.75rem; color: #999; margin-top: 5px; text-align: right; }
    .quick-buttons {
        display: flex;
        gap: 8px;
        padding: 15px;
        flex-wrap: wrap;
        background: white;
        border-top: 1px solid #e0e0e0;
    }
    .quick-buttons button {
        padding: 8px 12px;
        border: 1px solid #667eea;
        background: white;
        color: #667eea;
        border-radius: 20px;
        cursor: pointer;
        font-size: 0.85rem;
        transition: all 0.3s;
    }
    .quick-buttons button:hover { background: #667eea; color: white; }
    .chat-input {
        display: flex;
        padding: 15px;
        background: white;
        border-top: 1px solid #e0e0e0;
    }
    .chat-input input {
        flex: 1;
        padding: 12px 15px;
        border: 2px solid #e0e0e0;
        border-radius: 25px;
        outline: none;
        font-size: 0.95rem;
    }
    .chat-input input:focus { border-color: #667eea; }
    .chat-input button {
        margin-left: 10px;
        padding: 10px 15px;
        background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
        color: white;
        border: none;
        border-radius: 50%;
        cursor: pointer;
        font-size: 1.1rem;
        transition: transform 0.2s;
    }
    .chat-input button:hover { transform: scale(1.1); }
</style>
