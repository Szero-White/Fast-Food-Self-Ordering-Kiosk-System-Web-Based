<!-- Custom Styles -->
<style>
.chatbot-header-card {
    background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
    border-radius: 16px;
    padding: 24px;
    color: white;
    box-shadow: 0 10px 40px rgba(102, 126, 234, 0.3);
}
.chatbot-icon-wrapper {
    width: 70px;
    height: 70px;
    background: rgba(255,255,255,0.2);
    border-radius: 16px;
    display: flex;
    align-items: center;
    justify-content: center;
    backdrop-filter: blur(10px);
}
.stat-card {
    background: rgba(255,255,255,0.15);
    border-radius: 12px;
    padding: 16px 24px;
    text-align: center;
    backdrop-filter: blur(10px);
    border: 1px solid rgba(255,255,255,0.2);
    transition: transform 0.3s ease, box-shadow 0.3s ease;
}
.stat-card:hover {
    transform: translateY(-3px);
    box-shadow: 0 8px 25px rgba(0,0,0,0.15);
}
.stat-number {
    font-size: 32px;
    font-weight: 800;
    color: white;
    line-height: 1;
}
.stat-label {
    font-size: 13px;
    color: rgba(255,255,255,0.8);
    margin-top: 4px;
}
.chart-card {
    background: white;
    border-radius: 16px;
    padding: 20px;
    box-shadow: 0 4px 20px rgba(0,0,0,0.05);
    border: 1px solid #f0f0f0;
}
.history-card {
    background: white;
    border-radius: 16px;
    box-shadow: 0 4px 20px rgba(0,0,0,0.05);
    border: 1px solid #f0f0f0;
    overflow: hidden;
}
.chat-table {
    width: 100%;
    border-collapse: separate;
    border-spacing: 0;
}
.chat-table th {
    background: linear-gradient(135deg, #f8f9fa 0%, #e9ecef 100%);
    padding: 16px 12px;
    font-weight: 600;
    color: #495057;
    font-size: 13px;
    text-transform: uppercase;
    letter-spacing: 0.5px;
    border-bottom: 2px solid #dee2e6;
}
.chat-table td {
    padding: 14px 12px;
    border-bottom: 1px solid #f1f3f5;
    vertical-align: top;
}
.chat-table tr:hover td {
    background: #f8f9ff;
}
.chat-table tr:last-child td {
    border-bottom: none;
}
.user-message {
    background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
    color: white;
    padding: 10px 14px;
    border-radius: 14px 14px 14px 4px;
    font-size: 14px;
    display: inline-block;
    max-width: 100%;
    word-wrap: break-word;
}
.bot-response {
    background: #f8f9fa;
    color: #495057;
    padding: 10px 14px;
    border-radius: 14px 14px 4px 14px;
    font-size: 14px;
    display: inline-block;
    max-width: 100%;
    word-wrap: break-word;
    border: 1px solid #e9ecef;
}
.badge-custom {
    padding: 6px 12px;
    border-radius: 20px;
    font-size: 12px;
    font-weight: 500;
}
.badge-static { background: #e3f2fd; color: #1976d2; }
.badge-products { background: #e8f5e9; color: #388e3c; }
.badge-price { background: #fff3e0; color: #f57c00; }
.badge-promo { background: #fce4ec; color: #c2185b; }
.badge-stock { background: #f3e5f5; color: #7b1fa2; }
.badge-fallback { background: #f5f5f5; color: #616161; }
.badge-error { background: #ffebee; color: #d32f2f; }
.keyword-tag {
    background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
    color: white;
    padding: 4px 10px;
    border-radius: 12px;
    font-size: 11px;
    font-weight: 500;
}
.time-badge {
    background: #f8f9fa;
    color: #6c757d;
    padding: 4px 10px;
    border-radius: 8px;
    font-size: 12px;
}
.section-title {
    font-size: 18px;
    font-weight: 700;
    color: #333;
    display: flex;
    align-items: center;
    gap: 10px;
}
.filter-select {
    border: 2px solid #e9ecef;
    border-radius: 10px;
    padding: 10px 14px;
    font-size: 14px;
    transition: all 0.3s;
}
.filter-select:focus {
    border-color: #667eea;
    box-shadow: 0 0 0 3px rgba(102, 126, 234, 0.1);
}
.btn-refresh {
    background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
    color: white;
    border: none;
    padding: 10px 20px;
    border-radius: 10px;
    font-weight: 500;
    transition: transform 0.3s, box-shadow 0.3s;
}
.btn-refresh:hover {
    transform: translateY(-2px);
    box-shadow: 0 8px 20px rgba(102, 126, 234, 0.3);
}
.keyword-cloud {
    display: flex;
    flex-wrap: wrap;
    gap: 10px;
}
.keyword-item {
    background: white;
    border: 2px solid #e9ecef;
    padding: 10px 16px;
    border-radius: 25px;
    display: flex;
    align-items: center;
    gap: 8px;
    transition: all 0.3s;
}
.keyword-item:hover {
    border-color: #667eea;
    transform: translateY(-2px);
    box-shadow: 0 4px 12px rgba(102, 126, 234, 0.15);
}
.keyword-count {
    background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
    color: white;
    width: 28px;
    height: 28px;
    border-radius: 50%;
    display: flex;
    align-items: center;
    justify-content: center;
    font-size: 12px;
    font-weight: 700;
}
.empty-state {
    text-align: center;
    padding: 60px 20px;
    color: #6c757d;
}
.empty-state i {
    font-size: 64px;
    color: #dee2e6;
    margin-bottom: 20px;
}
</style>

<!-- Page Header -->
<div class="chatbot-header-card mb-4">
    <div class="d-flex align-items-center justify-content-between flex-wrap gap-4">
        <div class="d-flex align-items-center gap-4">
            <div class="chatbot-icon-wrapper">
                <i class="fas fa-robot" style="color: white; font-size: 32px;"></i>
            </div>
            <div>
                <h4 style="margin: 0; font-weight: 800; color: white; font-size: 28px;">Quản lý Chatbot</h4>
                <p style="margin: 5px 0 0 0; color: rgba(255,255,255,0.85); font-size: 15px;">
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
        <div style="width: 40px; height: 40px; background: linear-gradient(135deg, #667eea 0%, #764ba2 100%); border-radius: 10px; display: flex; align-items: center; justify-content: center;">
            <i class="fas fa-chart-line" style="color: white; font-size: 18px;"></i>
        </div>
        Lượt chat 7 ngày gần nhất
    </div>
    <div style="height: 300px;">
        <canvas id="chatChart"></canvas>
    </div>
</div>

<!-- Bảng lịch sử chat -->
<div class="history-card mb-4">
    <div style="padding: 20px; border-bottom: 1px solid #f0f0f0; display: flex; justify-content: space-between; align-items: center; flex-wrap: wrap; gap: 15px;">
        <div class="section-title" style="margin: 0;">
            <div style="width: 40px; height: 40px; background: linear-gradient(135deg, #00c6ff 0%, #0072ff 100%); border-radius: 10px; display: flex; align-items: center; justify-content: center;">
                <i class="fas fa-comments" style="color: white; font-size: 18px;"></i>
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
            <button class="btn-refresh" onclick="loadChatHistory()">
                <i class="fas fa-sync-alt me-2"></i>Làm mới
            </button>
        </div>
    </div>
    <div style="overflow-x: auto;">
        <table class="chat-table" id="chatHistoryTable">
            <thead>
                <tr>
                    <th style="width: 60px;">ID</th>
                    <th style="width: 140px;">Thời gian</th>
                    <th style="min-width: 200px;">Câu hỏi (User)</th>
                    <th style="min-width: 250px;">Trả lời (Bot)</th>
                    <th style="width: 100px;">Từ khóa</th>
                    <th style="width: 120px;">Loại</th>
                    <th style="width: 80px;">IP</th>
                </tr>
            </thead>
            <tbody id="chatHistoryBody">
                <!-- Data loaded via AJAX -->
            </tbody>
        </table>
        <div id="emptyState" class="empty-state" style="display: none;">
            <i class="fas fa-inbox"></i>
            <h5>Chưa có dữ liệu</h5>
            <p>Chưa có lịch sử chat nào được ghi nhận</p>
        </div>
    </div>
    <div class="d-flex justify-content-center p-4" id="loadMoreContainer">
        <button class="btn-refresh" onclick="loadMore()" style="background: #f8f9fa; color: #667eea;">
            <i class="fas fa-chevron-down me-2"></i>Tải thêm
        </button>
    </div>
</div>

<!-- Top Keywords -->
<div class="chart-card">
    <div class="section-title mb-4">
        <div style="width: 40px; height: 40px; background: linear-gradient(135deg, #ff6b6b 0%, #ee5a24 100%); border-radius: 10px; display: flex; align-items: center; justify-content: center;">
            <i class="fas fa-fire" style="color: white; font-size: 18px;"></i>
        </div>
        Từ khóa phổ biến
    </div>
    <div id="topKeywords" class="keyword-cloud">
        <!-- Loaded via AJAX -->
    </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
let offset = 0;
const limit = 50;
let currentFilter = '';
const chatbotApiBase = window.location.port === '8001'
    ? 'http://localhost:8000/pages/main/chatbot_api.php'
    : '../view/pages/main/chatbot_api.php';

// Type labels with custom badges
const typeLabels = {
    'static': '<span class="badge-custom badge-static"><i class="fas fa-comment me-1"></i>Tĩnh</span>',
    'api_products': '<span class="badge-custom badge-products"><i class="fas fa-utensils me-1"></i>Sản phẩm</span>',
    'api_price': '<span class="badge-custom badge-price"><i class="fas fa-tag me-1"></i>Giá</span>',
    'api_promo': '<span class="badge-custom badge-promo"><i class="fas fa-gift me-1"></i>Khuyến mãi</span>',
    'api_stock': '<span class="badge-custom badge-stock"><i class="fas fa-box me-1"></i>Tồn kho</span>',
    'fallback': '<span class="badge-custom badge-fallback"><i class="fas fa-question me-1"></i>Không hiểu</span>',
    'error': '<span class="badge-custom badge-error"><i class="fas fa-exclamation me-1"></i>Lỗi</span>'
};

function loadChatHistory(reset = true) {
    if (reset) {
        offset = 0;
        document.getElementById('chatHistoryBody').innerHTML = '';
    }
    
    const type = document.getElementById('filter-type')?.value || '';
    
    fetch(`${chatbotApiBase}?action=get_chat_history&limit=${limit}&offset=${offset}`)
        .then(r => r.json())
        .then(data => {
            if (data.success) {
                renderTable(data.data);
                if (data.data.length < limit) {
                    document.getElementById('loadMoreContainer').style.display = 'none';
                } else {
                    document.getElementById('loadMoreContainer').style.display = 'block';
                }
            }
        });
}

function renderTable(rows) {
    const tbody = document.getElementById('chatHistoryBody');
    const emptyState = document.getElementById('emptyState');
    
    if (rows.length === 0 && offset === 0) {
        emptyState.style.display = 'block';
        return;
    }
    emptyState.style.display = 'none';
    
    rows.forEach(row => {
        const tr = document.createElement('tr');
        const date = new Date(row.created_at);
        const timeStr = date.toLocaleDateString('vi-VN') + '<br><small style="color:#999;">' + date.toLocaleTimeString('vi-VN') + '</small>';
        
        tr.innerHTML = `
            <td><span style="font-weight:700;color:#667eea;">#${row.id}</span></td>
            <td><div class="time-badge"><i class="far fa-clock me-1"></i>${timeStr}</div></td>
            <td><div class="user-message">${escapeHtml(row.user_message)}</div></td>
            <td><div class="bot-response">${escapeHtml(row.bot_response)}</div></td>
            <td>${row.matched_keyword ? `<span class="keyword-tag">${row.matched_keyword}</span>` : '<span style="color:#ccc;">-</span>'}</td>
            <td>${typeLabels[row.response_type] || row.response_type}</td>
            <td><small style="color:#6c757d;font-family:monospace;">${row.user_ip || '-'}</small></td>
        `;
        tbody.appendChild(tr);
    });
}

function escapeHtml(text) {
    if (!text) return '';
    const div = document.createElement('div');
    div.textContent = text;
    return div.innerHTML;
}

function loadMore() {
    offset += limit;
    loadChatHistory(false);
}

function loadStats() {
    fetch(`${chatbotApiBase}?action=get_chat_stats`)
        .then(r => r.json())
        .then(data => {
            if (data.success) {
                document.getElementById('stat-today').textContent = data.data.today_chats;
                document.getElementById('stat-total').textContent = data.data.total_chats;
                
                // Render top keywords
                const kwContainer = document.getElementById('topKeywords');
                if (data.data.top_keywords.length === 0) {
                    kwContainer.innerHTML = '<p style="color:#999;">Chưa có dữ liệu từ khóa</p>';
                } else {
                    kwContainer.innerHTML = data.data.top_keywords.map(k => 
                        `<div class="keyword-item">
                            <span style="font-weight:500;color:#333;">${k.matched_keyword}</span>
                            <span class="keyword-count">${k.count}</span>
                        </div>`
                    ).join('');
                }
            }
        });
}

function loadChart() {
    fetch(`${chatbotApiBase}?action=get_chat_stats_by_date`)
        .then(r => r.json())
        .then(data => {
            if (data.success) {
                const ctx = document.getElementById('chatChart').getContext('2d');
                
                // Create gradient
                const gradient = ctx.createLinearGradient(0, 0, 0, 300);
                gradient.addColorStop(0, 'rgba(102, 126, 234, 0.3)');
                gradient.addColorStop(1, 'rgba(102, 126, 234, 0.0)');
                
                new Chart(ctx, {
                    type: 'line',
                    data: {
                        labels: data.data.dates.map(d => new Date(d).toLocaleDateString('vi-VN', {day: '2-digit', month: '2-digit'})),
                        datasets: [{
                            label: 'Số lượt chat',
                            data: data.data.counts,
                            borderColor: '#667eea',
                            backgroundColor: gradient,
                            borderWidth: 3,
                            pointBackgroundColor: '#fff',
                            pointBorderColor: '#667eea',
                            pointBorderWidth: 2,
                            pointRadius: 5,
                            pointHoverRadius: 7,
                            tension: 0.4,
                            fill: true
                        }]
                    },
                    options: {
                        responsive: true,
                        maintainAspectRatio: false,
                        plugins: {
                            legend: { display: false }
                        },
                        scales: {
                            y: { 
                                beginAtZero: true, 
                                ticks: { 
                                    stepSize: 1,
                                    color: '#6c757d',
                                    font: { size: 11 }
                                },
                                grid: {
                                    color: 'rgba(0,0,0,0.05)',
                                    borderDash: [5, 5]
                                }
                            },
                            x: {
                                ticks: {
                                    color: '#6c757d',
                                    font: { size: 11 }
                                },
                                grid: {
                                    display: false
                                }
                            }
                        }
                    }
                });
            }
        });
}

// Filter change
document.getElementById('filter-type')?.addEventListener('change', () => loadChatHistory(true));

// Load on page load
loadStats();
loadChatHistory();
loadChart();
</script>
