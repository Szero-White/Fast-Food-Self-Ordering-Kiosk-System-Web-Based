document.addEventListener('DOMContentLoaded', () => {
    const requiredMessage = (field) => {
        const label = field.closest('.form-group-custom')?.querySelector('.form-label-custom')?.textContent || 'trường bắt buộc';
        const cleanLabel = label.replace('*', '').trim().toLowerCase();

        if (field.tagName === 'SELECT') {
            return 'Vui lòng chọn ' + cleanLabel + '.';
        }

        return 'Vui lòng nhập ' + cleanLabel + '.';
    };

    document.querySelectorAll('input[required], select[required], textarea[required]').forEach((field) => {
        field.addEventListener('invalid', () => {
            if (field.validity.valueMissing) {
                field.setCustomValidity(requiredMessage(field));
                return;
            }

            field.setCustomValidity('');
        });

        field.addEventListener('input', () => field.setCustomValidity(''));
        field.addEventListener('change', () => field.setCustomValidity(''));
    });

    document.querySelectorAll('[data-upload-target]').forEach((trigger) => {
        const targetId = trigger.getAttribute('data-upload-target');
        const input = targetId ? document.getElementById(targetId) : null;

        if (!input) {
            return;
        }

        trigger.addEventListener('click', () => input.click());
        trigger.addEventListener('keydown', (event) => {
            if (event.key === 'Enter' || event.key === ' ') {
                event.preventDefault();
                input.click();
            }
        });
    });

    document.querySelectorAll('[data-preview-target]').forEach((input) => {
        input.addEventListener('change', () => {
            const file = input.files && input.files[0];
            const preview = document.getElementById(input.getAttribute('data-preview-target'));

            if (!file || !preview) {
                return;
            }

            const image = preview.querySelector('img');
            const fileName = preview.querySelector('[data-file-name]');
            const reader = new FileReader();

            reader.addEventListener('load', () => {
                if (image) {
                    image.src = reader.result;
                }

                if (fileName) {
                    fileName.textContent = file.name;
                }

                preview.classList.add('is-visible');
            });

            reader.readAsDataURL(file);
        });
    });
});
