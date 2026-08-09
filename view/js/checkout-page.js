document.addEventListener('DOMContentLoaded', () => {
    const paymentOptions = document.querySelectorAll('.payment-option');
    const paymentPreviews = document.querySelectorAll('[data-payment-preview]');

    const showPaymentPreview = (method) => {
        paymentPreviews.forEach((preview) => {
            preview.classList.toggle(
                'payment-preview-hidden',
                preview.dataset.paymentPreview !== method
            );
        });
    };

    paymentOptions.forEach((option) => {
        option.addEventListener('click', () => {
            const input = option.querySelector('input[name="phuongthuc"]');

            paymentOptions.forEach((item) => item.classList.remove('selected'));
            option.classList.add('selected');

            if (!input) {
                return;
            }

            input.checked = true;
            showPaymentPreview(input.value);
        });
    });

    const checkedPayment = document.querySelector('input[name="phuongthuc"]:checked');
    if (checkedPayment) {
        showPaymentPreview(checkedPayment.value);
    }
});
