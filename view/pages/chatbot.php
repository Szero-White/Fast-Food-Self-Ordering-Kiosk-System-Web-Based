<?php
$chatbot_api = 'pages/main/chatbot_api.php';
?>
<!-- AI Chatbot Widget - Draggable Circle -->
<div class="chatbot-circle" id="chatbot-circle">
    <span class="chatbot-icon">🤖</span>
    <span class="chatbot-notification" id="chatbot-noti">1</span>
</div>

<div class="chatbot-widget" id="chatbot-widget" data-api-base="<?php echo htmlspecialchars($chatbot_api, ENT_QUOTES, 'UTF-8'); ?>">
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
<?php
$chatbotWidgetCssVersion = filemtime(__DIR__ . '/../css/chatbot-widget.css');
$chatbotResponseServiceVersion = filemtime(__DIR__ . '/../js/chatbot-response-service.js');
$chatbotWidgetJsVersion = filemtime(__DIR__ . '/../js/chatbot-widget.js');
?>
<link rel="stylesheet" href="css/chatbot-widget.css?v=<?php echo $chatbotWidgetCssVersion; ?>">
<script src="js/chatbot-response-service.js?v=<?php echo $chatbotResponseServiceVersion; ?>"></script>
<script src="js/chatbot-widget.js?v=<?php echo $chatbotWidgetJsVersion; ?>"></script>
