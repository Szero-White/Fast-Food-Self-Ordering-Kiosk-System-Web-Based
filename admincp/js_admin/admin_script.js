/* ============================================
   FastFood Admin - JavaScript Functions
   ============================================ */

document.addEventListener('DOMContentLoaded', function() {
    const csrfTokenMeta = document.querySelector('meta[name="admin-csrf-token"]');
    const csrfToken = csrfTokenMeta ? csrfTokenMeta.getAttribute('content') : '';

    if (csrfToken) {
        document.querySelectorAll('form[method="POST"], form[method="post"]').forEach(form => {
            if (!form.querySelector('input[name="csrf_token"]')) {
                const input = document.createElement('input');
                input.type = 'hidden';
                input.name = 'csrf_token';
                input.value = csrfToken;
                form.appendChild(input);
            }
        });

        document.querySelectorAll('a[href*="xuly.php"]').forEach(link => {
            const href = link.getAttribute('href') || '';
            if (!href || href.includes('csrf_token=')) {
                return;
            }

            const hashIndex = href.indexOf('#');
            const baseHref = hashIndex >= 0 ? href.slice(0, hashIndex) : href;
            const hash = hashIndex >= 0 ? href.slice(hashIndex) : '';
            const separator = baseHref.includes('?') ? '&' : '?';

            link.setAttribute('href', `${baseHref}${separator}csrf_token=${encodeURIComponent(csrfToken)}${hash}`);
        });
    }

    // Sidebar Toggle
    const sidebar = document.getElementById('sidebar');
    const sidebarNav = document.querySelector('.sidebar-nav');
    const mainContent = document.getElementById('mainContent');
    const sidebarToggle = document.getElementById('sidebarToggle');
    
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

    // Restore sidebar scroll position after navigating between admin modules
    if (sidebarNav) {
        const savedSidebarScroll = sessionStorage.getItem('adminSidebarScrollTop');
        const savedClickedOffset = sessionStorage.getItem('adminSidebarClickedOffset');
        const activeMenuLink = sidebarNav.querySelector('a.nav-link.active');

        if (activeMenuLink && savedClickedOffset !== null) {
            const navTop = sidebarNav.getBoundingClientRect().top;
            const linkTop = activeMenuLink.getBoundingClientRect().top;
            const targetOffset = parseInt(savedClickedOffset, 10) || 0;
            sidebarNav.scrollTop += linkTop - navTop - targetOffset;
        } else if (savedSidebarScroll !== null && sidebarNav.classList.contains('is-restoring-scroll')) {
            sidebarNav.scrollTop = parseInt(savedSidebarScroll, 10) || 0;
        }

        if (sidebarNav.classList.contains('is-restoring-scroll')) {
            sidebarNav.classList.remove('is-restoring-scroll');
        }

        sidebarNav.addEventListener('scroll', function() {
            sessionStorage.setItem('adminSidebarScrollTop', String(sidebarNav.scrollTop));
        }, { passive: true });

        sidebarNav.querySelectorAll('a.nav-link').forEach(link => {
            link.addEventListener('click', function() {
                const navTop = sidebarNav.getBoundingClientRect().top;
                const linkTop = this.getBoundingClientRect().top;
                sessionStorage.setItem('adminSidebarScrollTop', String(sidebarNav.scrollTop));
                sessionStorage.setItem('adminSidebarClickedOffset', String(Math.max(0, linkTop - navTop)));
            });
        });
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
                    
                    field.classList.add('admin-shake');
                    setTimeout(() => {
                        field.classList.remove('admin-shake');
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
    const imageInputs = document.querySelectorAll('input[type="file"][accept*="image"]:not([data-preview-target])');
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
                        input.parentElement.appendChild(previewContainer);
                    }
                    
                    previewContainer.innerHTML = `
                        <img src="${e.target.result}" alt="Ảnh xem trước" class="crud-current-image">
                        <p class="crud-muted mt-2">${file.name}</p>
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
                return;
            }

            const href = this.getAttribute('href') || '';
            const isDeleteLink = this.tagName === 'A' && href.includes('xuly.php');
            if (isDeleteLink && csrfToken) {
                e.preventDefault();

                const form = document.createElement('form');
                form.method = 'POST';
                form.action = href;
                form.style.display = 'none';

                const input = document.createElement('input');
                input.type = 'hidden';
                input.name = 'csrf_token';
                input.value = csrfToken;
                form.appendChild(input);

                document.body.appendChild(form);
                form.submit();
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
        const target = document.querySelector(input.getAttribute('data-search'));
        if (target) {
            input.addEventListener('keyup', function() {
                const searchTerm = this.value.toLowerCase();
                const rows = target.querySelectorAll('tbody tr');
                const items = rows.length > 0 ? rows : target.children;
                
                Array.from(items).forEach(item => {
                    const text = item.textContent.toLowerCase();
                    item.hidden = !text.includes(searchTerm);
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

