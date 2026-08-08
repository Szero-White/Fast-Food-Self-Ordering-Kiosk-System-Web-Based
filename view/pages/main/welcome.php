<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>FastFood Kiosk - Chào mừng</title>
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }
        body {
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            min-height: 100vh;
            display: flex;
            flex-direction: column;
            justify-content: center;
            align-items: center;
            color: white;
            overflow: hidden;
        }
        .logo-container {
            margin-bottom: 30px;
            animation: float 3s ease-in-out infinite;
        }
        .logo-container img {
            width: 150px;
            height: 150px;
            border-radius: 50%;
            border: 5px solid white;
            box-shadow: 0 10px 30px rgba(0,0,0,0.3);
        }
        @keyframes float {
            0%, 100% { transform: translateY(0px); }
            50% { transform: translateY(-20px); }
        }
        h1 {
            font-size: 3rem;
            margin-bottom: 15px;
            text-shadow: 2px 2px 4px rgba(0,0,0,0.3);
        }
        .tagline {
            font-size: 1.3rem;
            opacity: 0.9;
            margin-bottom: 50px;
        }
        .start-btn {
            background: white;
            color: #667eea;
            border: none;
            padding: 25px 80px;
            font-size: 2rem;
            font-weight: bold;
            border-radius: 50px;
            cursor: pointer;
            box-shadow: 0 10px 30px rgba(0,0,0,0.3);
            transition: all 0.3s;
            text-decoration: none;
            display: inline-block;
        }
        .start-btn:hover {
            transform: scale(1.05);
            box-shadow: 0 15px 40px rgba(0,0,0,0.4);
        }
        .start-btn:active {
            transform: scale(0.98);
        }
        .features {
            display: flex;
            gap: 40px;
            margin-top: 60px;
            font-size: 1rem;
            opacity: 0.8;
        }
        .feature {
            display: flex;
            align-items: center;
            gap: 10px;
        }
        .feature span {
            font-size: 2rem;
        }
        .bounce {
            animation: bounce 2s infinite;
        }
        @keyframes bounce {
            0%, 20%, 50%, 80%, 100% { transform: translateY(0); }
            40% { transform: translateY(-10px); }
            60% { transform: translateY(-5px); }
        }
    </style>
</head>
<body>
    <div class="logo-container">
        <img src="images/1200_50/logo.jpg" alt="FastFood Logo">
    </div>
    
    <h1>🍔 FastFood Kiosk</h1>
    <p class="tagline">Đặt món dễ dàng - Nhanh chóng - Tiện lợi</p>
    
    <a href="index.php?start=1" class="start-btn bounce">
        👆 BẮT ĐẦU
    </a>
    
    <div class="features">
        <div class="feature">
            <span>🍕</span>
            <span>Chọn món dễ dàng</span>
        </div>
        <div class="feature">
            <span>⚡</span>
            <span>Thanh toán nhanh</span>
        </div>
        <div class="feature">
            <span>🎁</span>
            <span>Nhận ưu đãi</span>
        </div>
    </div>
</body>
</html>
