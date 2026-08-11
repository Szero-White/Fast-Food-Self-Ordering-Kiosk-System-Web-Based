# Smoke Test

Bộ test này kiểm tra nhanh các luồng quan trọng của dự án trước khi commit hoặc deploy.

## Cách chạy local với XAMPP

1. Bật Apache và MySQL trong XAMPP.
2. Đảm bảo database đã import từ `web_sqli.sql`.
3. Chạy lệnh:

```powershell
php tests/smoke/run_smoke_tests.php
```

Nếu dự án chạy ở URL khác, truyền base URL vào tham số đầu tiên:

```powershell
php tests/smoke/run_smoke_tests.php http://localhost/web_mysqli
```

Hoặc dùng biến môi trường:

```powershell
$env:KIOSK_BASE_URL="http://localhost/web_mysqli"
php tests/smoke/run_smoke_tests.php
```

## Smoke test kiểm tra gì?

- Cú pháp PHP của các file quan trọng.
- Các trang public chính trả về HTTP hợp lệ và không có lỗi PHP.
- Chatbot API trả về JSON hợp lệ.
- Các endpoint xử lý Admin không mở công khai khi chưa đăng nhập.
- File secret AI được Git ignore.
- Thư mục upload có `.htaccess` và `.gitkeep`.

## Ghi chú

Đây không phải PHPUnit đầy đủ. Mục tiêu là phát hiện nhanh lỗi nghiêm trọng trước khi demo/deploy mà không cần cài thêm framework test.
