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
    'api_cart_add': '<span class="badge-custom badge-cart"><i class="fas fa-cart-plus me-1"></i>Thêm giỏ hàng</span>',
    'ai_gemini': '<span class="badge-custom badge-ai"><i class="fas fa-sparkles me-1"></i>AI Gemini</span>',
    'fallback': '<span class="badge-custom badge-fallback"><i class="fas fa-question me-1"></i>Không hiểu</span>',
    'error': '<span class="badge-custom badge-error"><i class="fas fa-exclamation me-1"></i>Lỗi</span>'
};

function loadChatHistory(reset = true) {
    if (reset) {
        offset = 0;
        document.getElementById('chatHistoryBody').innerHTML = '';
    }
    
    const type = document.getElementById('filter-type')?.value || '';
    currentFilter = type;
    updateExportLink();
    
    fetch(`${chatbotApiBase}?action=get_chat_history&limit=${limit}&offset=${offset}&type=${encodeURIComponent(type)}`)
        .then(r => r.json())
        .then(data => {
            if (data.success) {
                renderTable(data.data);
                if (data.data.length < limit) {
                    document.getElementById('loadMoreContainer').style.display = 'none';
                } else {
                    document.getElementById('loadMoreContainer').style.display = 'block';
                }
            } else {
                renderHistoryError(data.message || 'Không thể tải lịch sử chatbot.');
            }
        })
        .catch(() => {
            renderHistoryError('Không thể kết nối API lịch sử chatbot.');
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
        const timeStr = date.toLocaleDateString('vi-VN') + '<br><small class="chatbot-time-detail">' + date.toLocaleTimeString('vi-VN') + '</small>';
        
        tr.innerHTML = `
            <td><span class="chatbot-id">#${row.id}</span></td>
            <td><div class="time-badge"><i class="far fa-clock me-1"></i>${timeStr}</div></td>
            <td><div class="user-message">${escapeHtml(row.user_message)}</div></td>
            <td><div class="bot-response">${escapeHtml(row.bot_response)}</div></td>
            <td>${row.matched_keyword ? `<span class="keyword-tag">${row.matched_keyword}</span>` : '<span class="chatbot-muted">-</span>'}</td>
            <td>${typeLabels[row.response_type] || row.response_type}</td>
            <td><small class="chatbot-ip">${row.user_ip || '-'}</small></td>
        `;
        tbody.appendChild(tr);
    });
}

function renderHistoryError(message) {
    const tbody = document.getElementById('chatHistoryBody');
    const emptyState = document.getElementById('emptyState');
    const loadMore = document.getElementById('loadMoreContainer');

    if (emptyState) {
        emptyState.style.display = 'none';
    }
    if (loadMore) {
        loadMore.style.display = 'none';
    }
    if (tbody) {
        tbody.innerHTML = `
            <tr>
                <td colspan="7">
                    <div class="chatbot-admin-error">
                        <i class="fas fa-circle-exclamation"></i>
                        ${escapeHtml(message)}
                    </div>
                </td>
            </tr>
        `;
    }
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

function updateExportLink() {
    const exportLink = document.getElementById('export-chatbot');
    if (!exportLink) return;

    exportLink.href = currentFilter
        ? `modules/quanlychatbot/export.php?type=${encodeURIComponent(currentFilter)}`
        : 'modules/quanlychatbot/export.php';
    exportLink.innerHTML = '<i class="fas fa-file-export me-2"></i>Xu&#7845;t file';
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
                    kwContainer.innerHTML = '<p class="chatbot-muted">Chưa có dữ liệu từ khóa</p>';
                } else {
                    kwContainer.innerHTML = data.data.top_keywords.map(k => 
                        `<div class="keyword-item">
                            <span class="keyword-name">${k.matched_keyword}</span>
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
document.getElementById('refresh-chat-history')?.addEventListener('click', () => loadChatHistory(true));
document.getElementById('load-more-chat-history')?.addEventListener('click', loadMore);

// Load on page load
loadStats();
updateExportLink();
loadChatHistory();
loadChart();
