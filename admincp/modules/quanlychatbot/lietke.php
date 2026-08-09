<!-- Custom Styles -->
<?php $chatbotAdminCssVersion = filemtime(__DIR__ . '/../../css_admin/pages/chatbot-admin.css'); ?>
<link rel="stylesheet" href="css_admin/pages/chatbot-admin.css?v=<?php echo $chatbotAdminCssVersion; ?>">

<!-- Page Header -->
<div class="chatbot-header-card mb-4">
    <div class="d-flex align-items-center justify-content-between flex-wrap gap-4">
        <div class="d-flex align-items-center gap-4">
            <div class="chatbot-icon-wrapper">
                <i class="fas fa-robot chatbot-header-icon"></i>
            </div>
            <div>
                <h4 class="chatbot-page-title">Quản lý Chatbot</h4>
                <p class="chatbot-page-subtitle">
                    <i class="fas fa-chart-pie me-2"></i>Lịch sử hội thoại và thống kê tương tác
                </p>
            </div>
        </div>
        <div class="d-flex gap-3">
            <div class="stat-card">
                <div class="stat-number" id="stat-today">0</div>
                <div class="stat-label"><i class="fas fa-calendar-day me-1"></i>Hôm nay</div>
            </div>
            <div class="stat-card">
                <div class="stat-number" id="stat-total">0</div>
                <div class="stat-label"><i class="fas fa-database me-1"></i>Tổng</div>
            </div>
        </div>
    </div>
</div>

<!-- Thống kê theo ngày -->
<div class="chart-card mb-4">
    <div class="section-title mb-3">
        <div class="chatbot-section-icon chart">
            <i class="fas fa-chart-line"></i>
        </div>
        Lượt chat 7 ngày gần nhất
    </div>
    <div class="chatbot-chart-wrap">
        <canvas id="chatChart"></canvas>
    </div>
</div>

<!-- Bảng lịch sử chat -->
<div class="history-card mb-4">
    <div class="chatbot-panel-header">
        <div class="section-title chatbot-section-title">
            <div class="chatbot-section-icon history">
                <i class="fas fa-comments"></i>
            </div>
            Lịch sử hội thoại
        </div>
        <div class="d-flex gap-2">
            <select id="filter-type" class="filter-select">
                <option value="">📁 Tất cả loại</option>
                <option value="static">💬 Trả lời tĩnh</option>
                <option value="api_products">🍕 API - Sản phẩm</option>
                <option value="api_price">💰 API - Giá</option>
                <option value="api_promo">🎉 API - Khuyến mãi</option>
                <option value="api_stock">📦 API - Tồn kho</option>
                <option value="fallback">❓ Không hiểu</option>
                <option value="error">⚠️ Lỗi</option>
            </select>
            <button type="button" class="btn-refresh" id="refresh-chat-history">
                <i class="fas fa-sync-alt me-2"></i>Làm mới
            </button>
            <a id="export-chatbot" class="btn-export" href="modules/quanlychatbot/export.php">
                <i class="fas fa-file-export me-2"></i>Xuất file
            </a>
        </div>
    </div>
    <div class="chatbot-table-scroll">
        <table class="chat-table" id="chatHistoryTable">
            <thead>
                <tr>
                    <th class="chatbot-col-id">ID</th>
                    <th class="chatbot-col-time">Thời gian</th>
                    <th class="chatbot-col-question">Câu hỏi (User)</th>
                    <th class="chatbot-col-answer">Trả lời (Bot)</th>
                    <th class="chatbot-col-keyword">Từ khóa</th>
                    <th class="chatbot-col-type">Loại</th>
                    <th class="chatbot-col-ip">IP</th>
                </tr>
            </thead>
            <tbody id="chatHistoryBody">
                <!-- Data loaded via AJAX -->
            </tbody>
        </table>
        <div id="emptyState" class="empty-state chatbot-hidden">
            <i class="fas fa-inbox"></i>
            <h5>Chưa có dữ liệu</h5>
            <p>Chưa có lịch sử chat nào được ghi nhận</p>
        </div>
    </div>
    <div class="d-flex justify-content-center p-4" id="loadMoreContainer">
        <button type="button" class="btn-refresh btn-load-more" id="load-more-chat-history">
            <i class="fas fa-chevron-down me-2"></i>Tải thêm
        </button>
    </div>
</div>

<!-- Top Keywords -->
<div class="chart-card">
    <div class="section-title mb-4">
        <div class="chatbot-section-icon keyword">
            <i class="fas fa-fire"></i>
        </div>
        Từ khóa phổ biến
    </div>
    <div id="topKeywords" class="keyword-cloud">
        <!-- Loaded via AJAX -->
    </div>
</div>

<?php $chatbotAdminJsVersion = filemtime(__DIR__ . '/../../js_admin/pages/chatbot-admin.js'); ?>
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script src="js_admin/pages/chatbot-admin.js?v=<?php echo $chatbotAdminJsVersion; ?>"></script>
