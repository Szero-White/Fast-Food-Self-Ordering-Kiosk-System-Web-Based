document.addEventListener('DOMContentLoaded', () => {
    document.querySelectorAll('[data-asset-file-input]').forEach((input) => {
        input.addEventListener('change', () => {
            const targetId = input.getAttribute('data-file-name-target');
            const target = targetId ? document.getElementById(targetId) : null;

            if (!target) {
                return;
            }

            target.textContent = input.files && input.files.length > 0 ? input.files[0].name : 'Chưa chọn tệp nào';
        });
    });
});
