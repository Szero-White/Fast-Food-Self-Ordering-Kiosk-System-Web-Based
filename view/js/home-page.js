document.addEventListener('DOMContentLoaded', function () {
    var categorySelect = document.getElementById('homeCategorySelect');

    if (!categorySelect || categorySelect.dataset.scrollMode !== 'section') {
        return;
    }

    categorySelect.addEventListener('change', function () {
        var categoryId = categorySelect.value;
        var target = categoryId ? document.getElementById('danhmuc-' + categoryId) : document.querySelector('.menu-section');

        if (!target) {
            return;
        }

        target.scrollIntoView({
            behavior: 'smooth',
            block: 'start'
        });
    });
});
