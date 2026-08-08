/* ============================================
   FastFood Admin - JavaScript Functions
   ============================================ */

document.addEventListener('DOMContentLoaded', function() {
    // Sidebar Toggle
    const sidebar = document.getElementById('sidebar');
    const mainContent = document.getElementById('mainContent');
    const sidebarToggle = document.getElementById('sidebarToggle');
    const menuToggle = document.getElementById('menuToggle');
    
    // Create overlay for mobile
    const overlay = document.createElement('div');
    overlay.className = 'sidebar-overlay';
    document.body.appendChild(overlay);
    
    // Toggle sidebar on desktop
    if (sidebarToggle) {
        sidebarToggle.addEventListener('click', function() {
            sidebar.classList.toggle('collapsed');
            mainContent.classList.toggle('expanded');
            
            // Save state to localStorage
            const isCollapsed = sidebar.classList.contains('collapsed');
            localStorage.setItem('sidebarCollapsed', isCollapsed);
            
            // Update toggle icon
            if (isCollapsed) {
                sidebarToggle.innerHTML = '<i class="fas fa-arrow-right"></i>';
            } else {
                sidebarToggle.innerHTML = '<i class="fas fa-bars"></i>';
            }

            // Trên màn hình nhỏ, luôn bật .show để sidebar không bị translate ra ngoài
            if (window.innerWidth <= 1024) {
                sidebar.classList.add('show');
                overlay.classList.add('show');
            }
        });
    }
    
    // Toggle sidebar on mobile
    if (menuToggle) {
        menuToggle.addEventListener('click', function() {
            sidebar.classList.toggle('show');
            overlay.classList.toggle('show');
        });
    }
    
    // Close sidebar when clicking overlay
    overlay.addEventListener('click', function() {
        sidebar.classList.remove('show');
        overlay.classList.remove('show');
    });
    
    // Restore sidebar state
    const savedState = localStorage.getItem('sidebarCollapsed');
    if (savedState === 'true') {
        sidebar.classList.add('collapsed');
        mainContent.classList.add('expanded');
        sidebarToggle.innerHTML = '<i class="fas fa-arrow-right"></i>';

        // Trên màn hình nhỏ, đảm bảo sidebar không bị translate ra ngoài
        if (window.innerWidth <= 1024) {
            sidebar.classList.add('show');
            overlay.classList.add('show');
        }
    } else {
        sidebarToggle.innerHTML = '<i class="fas fa-bars"></i>';
    }
    
    // Active menu item
    const currentPage = window.location.search;
    const navLinks = document.querySelectorAll('.nav-link');
    
    navLinks.forEach(link => {
        const href = link.getAttribute('href');
        if (currentPage && href.includes(currentPage.split('&')[0])) {
            link.classList.add('active');
        } else if (!currentPage && href === 'index.php') {
            link.classList.add('active');
        }
    });
    
    // Update page title based on active menu
    const activeLink = document.querySelector('.nav-link.active');
    if (activeLink) {
        const pageTitle = document.getElementById('pageTitle');
        if (pageTitle) {
            const titleText = activeLink.querySelector('span');
            if (titleText) {
                pageTitle.textContent = titleText.textContent;
            }
        }
    }
    
    // Initialize CKEditor if present
    if (typeof CKEDITOR !== 'undefined') {
        const editorElements = document.querySelectorAll('textarea[data-editor]');
        editorElements.forEach(element => {
            CKEDITOR.replace(element.name);
        });
    }
    
    // Form validation enhancement
    const forms = document.querySelectorAll('form');
    forms.forEach(form => {
        form.addEventListener('submit', function(e) {
            const requiredFields = form.querySelectorAll('[required]');
            let isValid = true;
            
            requiredFields.forEach(field => {
                if (!field.value.trim()) {
                    isValid = false;
                    field.classList.add('is-invalid');
                    
                    // Add shake animation
                    field.style.animation = 'shake 0.5s ease';
                    setTimeout(() => {
                        field.style.animation = '';
                    }, 500);
                } else {
                    field.classList.remove('is-invalid');
                    field.classList.add('is-valid');
                }
            });
            
            if (!isValid) {
                e.preventDefault();
            }
        });
    });
    
    // Image preview for file uploads
    const imageInputs = document.querySelectorAll('input[type="file"][accept*="image"]');
    imageInputs.forEach(input => {
        input.addEventListener('change', function(e) {
            const file = e.target.files[0];
            if (file) {
                const reader = new FileReader();
                const previewContainer = input.closest('.image-upload') || 
                                        input.parentElement.querySelector('.image-preview') ||
                                        document.createElement('div');
                
                reader.onload = function(e) {
                    if (!previewContainer.classList.contains('image-preview')) {
                        previewContainer.classList.add('image-preview');
                        previewContainer.style.marginTop = '15px';
                        input.parentElement.appendChild(previewContainer);
                    }
                    
                    previewContainer.innerHTML = `
                        <img src="${e.target.result}" alt="Preview" style="max-width: 200px; border-radius: 8px; box-shadow: 0 4px 15px rgba(0,0,0,0.1);">
                        <p style="margin-top: 8px; font-size: 12px; color: #888;">${file.name}</p>
                    `;
                };
                
                reader.readAsDataURL(file);
            }
        });
    });
    
    // Confirmation for delete actions
    const deleteButtons = document.querySelectorAll('.btn-delete, [data-confirm]');
    deleteButtons.forEach(btn => {
        btn.addEventListener('click', function(e) {
            const message = this.getAttribute('data-confirm') || 'Bạn có chắc chắn muốn xóa?';
            if (!confirm(message)) {
                e.preventDefault();
            }
        });
    });
    
    // Table row selection
    const tableRows = document.querySelectorAll('.custom-table tbody tr');
    tableRows.forEach(row => {
        row.addEventListener('click', function(e) {
            if (!e.target.closest('a') && !e.target.closest('button')) {
                this.classList.toggle('selected');
            }
        });
    });
    
    // Search functionality
    const searchInputs = document.querySelectorAll('[data-search]');
    searchInputs.forEach(input => {
        const targetTable = document.querySelector(input.getAttribute('data-search'));
        if (targetTable) {
            input.addEventListener('keyup', function() {
                const searchTerm = this.value.toLowerCase();
                const rows = targetTable.querySelectorAll('tbody tr');
                
                rows.forEach(row => {
                    const text = row.textContent.toLowerCase();
                    row.style.display = text.includes(searchTerm) ? '' : 'none';
                });
            });
        }
    });
    
    // Add smooth scrolling
    document.querySelectorAll('a[href^="#"]').forEach(anchor => {
        anchor.addEventListener('click', function(e) {
            e.preventDefault();
            const target = document.querySelector(this.getAttribute('href'));
            if (target) {
                target.scrollIntoView({
                    behavior: 'smooth',
                    block: 'start'
                });
            }
        });
    });
    
    // Notification auto-hide
    const alerts = document.querySelectorAll('.alert:not(.alert-permanent)');
    alerts.forEach(alert => {
        setTimeout(() => {
            alert.style.opacity = '0';
            alert.style.transform = 'translateX(100%)';
            setTimeout(() => alert.remove(), 300);
        }, 5000);
    });
});

// Utility functions
function formatCurrency(amount) {
    return new Intl.NumberFormat('vi-VN', {
        style: 'currency',
        currency: 'VND'
    }).format(amount);
}

function formatDate(date) {
    return new Intl.DateTimeFormat('vi-VN', {
        year: 'numeric',
        month: 'long',
        day: 'numeric'
    }).format(new Date(date));
}

function debounce(func, wait) {
    let timeout;
    return function executedFunction(...args) {
        const later = () => {
            clearTimeout(timeout);
            func(...args);
        };
        clearTimeout(timeout);
        timeout = setTimeout(later, wait);
    };
}

// Add CSS animation for shake effect
const style = document.createElement('style');
style.textContent = `
    @keyframes shake {
        0%, 100% { transform: translateX(0); }
        25% { transform: translateX(-10px); }
        75% { transform: translateX(10px); }
    }
    
    .is-invalid {
        border-color: #e74c3c !important;
        box-shadow: 0 0 0 4px rgba(231, 76, 60, 0.1) !important;
    }
    
    .is-valid {
        border-color: #2ecc71 !important;
        box-shadow: 0 0 0 4px rgba(46, 204, 113, 0.1) !important;
    }
    
    .custom-table tbody tr.selected {
        background: rgba(102, 126, 234, 0.1) !important;
    }
`;
document.head.appendChild(style);
