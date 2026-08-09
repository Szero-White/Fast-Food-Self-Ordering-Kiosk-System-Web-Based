document.addEventListener('DOMContentLoaded', () => {
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
