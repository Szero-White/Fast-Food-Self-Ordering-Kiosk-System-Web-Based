const currentDate = document.getElementById('currentDate');

if (currentDate) {
    const dateOptions = { weekday: 'long', year: 'numeric', month: 'long', day: 'numeric' };
    currentDate.textContent = new Date().toLocaleDateString('vi-VN', dateOptions);
}
