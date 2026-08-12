-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Máy chủ: 127.0.0.1
-- Thời gian đã tạo: Th8 12, 2026 lúc 04:24 AM
-- Phiên bản máy phục vụ: 10.4.32-MariaDB
-- Phiên bản PHP: 8.2.12

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Cơ sở dữ liệu: `web_sqli`
--

-- --------------------------------------------------------

--
-- Cấu trúc bảng cho bảng `tbl_admin`
--

CREATE TABLE `tbl_admin` (
  `id_admin` int(11) NOT NULL,
  `username` varchar(100) NOT NULL,
  `password` varchar(100) NOT NULL,
  `admin_status` int(11) NOT NULL,
  `security_question` varchar(255) DEFAULT 'Thú cưng yêu thích của bạn là gì?',
  `security_answer` varchar(100) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Đang đổ dữ liệu cho bảng `tbl_admin`
--

INSERT INTO `tbl_admin` (`id_admin`, `username`, `password`, `admin_status`, `security_question`, `security_answer`) VALUES
(4, 'toan', '$2y$10$QWDAPSDB3aokVxftOJQHYemMDg/LsZhrWdAaJ7obx5v.2H2CegaZa', 2, 'Thú cưng yêu thích của bạn là gì?', '$2y$10$t.CG1Syy9Y8qJrsCxKYntey8IkeF2vIL6pEGtihJqRz87SULagnjy');

-- --------------------------------------------------------

--
-- Cấu trúc bảng cho bảng `tbl_baiviet`
--

CREATE TABLE `tbl_baiviet` (
  `id_bv` int(11) NOT NULL,
  `tenbaiviet` varchar(200) NOT NULL,
  `tomtat` longtext NOT NULL,
  `noidung` longtext NOT NULL,
  `hinhanh` varchar(100) NOT NULL,
  `id_danhmuc` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Đang đổ dữ liệu cho bảng `tbl_baiviet`
--

INSERT INTO `tbl_baiviet` (`id_bv`, `tenbaiviet`, `tomtat`, `noidung`, `hinhanh`, `id_danhmuc`) VALUES
(13, '🎈QUẨY TIỆC LỚN NHỎ, CÓ KFC LO! ️🎊', 'Ưu đãi rõ ràng thế này thì đơn giản thôi: Thứ 3 & Thứ 5 Mua 1 Tặng 1 Pizza Tụ tập gia đình hay hội ngộ bạn bè, có KFC, gặp mặt đã vui thưởng gà thêm nhiệt\n🎉 Chiết khấu lên đến 20%\n🎉 Miễn phí giao hàng', 'Tụ tập gia đình hay hội ngộ bạn bè, có KFC, gặp mặt đã vui thưởng gà thêm nhiệt\n🎉 Chiết khấu lên đến 20%\n🎉 Miễn phí giao hàng', '1716387211_443845123_845179787653865_6063365132974545575_n.jpg', 12),
(14, '🎊 BỪNG TIỆC GÀ RÁN, SINH NHẬT HOÀNH TRÁNG ️🎊', '🔥 QUẨY CÙNG CHEER TEAM\n🔥 CHỚP QUÀ SIÊU XỊNNN', '🔥 QUẨY CÙNG CHEER TEAM\n🔥 CHỚP QUÀ SIÊU XỊNNN\n🎊 Hè nắng nóng quá đi, nhưng vẫn không là gì so với độ nóng của đội Cheer Team nhà Hut. Đặc biệt, mỗi khi Cheer Team biểu diễn thì quà tặng sẽ tung khắp lối.', '1624437657500_540.png', 12),
(15, 'GIẢM 50% TỔNG HÓA ĐƠN & FREESHIP', '⏰ LAST CALL SUMMER DEAL\r\n CHILL TIỆC NHIỀU MÓN ĐỈNH 🔥🔥🔥', '💪 THỬ TÀI BÉ YÊU, TẾT THIẾU NHI Ý NGHĨA 💞\n🤩 Cơ hội để bé thỏa sức vui chơi & có thêm trải nghiệm mới dịp 1/6 này với chương trình “Bé Học Làm Đầu Bếp Pizza”', '1716389147_OIP.jpg', 10),
(16, '🌈 TIỆC BẾ GIẢNG, THÊM HOÀNH TRÁNG VỚI COMBO CHỈ TỪ 299K ', '🚨 CẢNH BÁO, HÌNH ẢNH NÀY PHÁT RA ĐỘ NGONNN\r\n🤤 Ăn Pizza là phải tươi rói & ngon lành cỡ này nè cả nhà ơi', '🚨 CẢNH BÁO, HÌNH ẢNH NÀY PHÁT RA ĐỘ NGONNN\r\n🤤 Ăn Pizza là phải tươi rói & ngon lành cỡ này nè cả nhà ơi', '441526081_848742820630895_3442272778759241973_n.jpg', 12),
(17, 'Menu CP Five Star – Gà rán, xúc xích và món ăn vặt hấp dẫn', 'Menu CP Five Star mang đến nhiều lựa chọn hấp dẫn như gà rán giòn, xúc xích, chả cá xiên que và các món ăn nhẹ. Giá cả hợp lý, phù hợp với học sinh, sinh viên và những ai yêu thích đồ ăn nhanh.', 'CP Five Star là thương hiệu đồ ăn nhanh quen thuộc với nhiều món ăn ngon, tiện lợi và giá cả phải chăng. Menu đa dạng từ các món xiên que đến gà rán giòn rụm, đáp ứng nhu cầu ăn nhanh và thưởng thức tại chỗ.\r\n\r\n🌭 Xúc xích và món xiên\r\nXúc xích SUMO: 10.000đ/que\r\nXúc xích HORAPA: 15.000đ/que\r\nXúc xích CORN-TORPEDO: 10.000đ/que\r\nXúc xích hồ lô: 10.000đ/que\r\nXúc xích phô mai: 8.000đ/que\r\nChả cá que: 8.000đ/que\r\nChả gà xiên que: 16.000đ/que\r\nChả kỳ cá: 15.000đ/que\r\nCá viên / bò viên / tàu hũ cá: 8.000đ/que\r\n🍗 Gà rán\r\nGà truyền thống:\r\nĐùi góc tư: 40.000đ\r\nCánh gà: 23.000đ\r\nSườn gà: 20.000đ\r\nĐùi Chicky: 24.000đ/cái\r\nGà giòn cay: 28.000đ/miếng\r\nCánh teen teen: 23.000đ/cái\r\nGà xù vàng: 25.000đ/miếng\r\nGà ZO ZO giòn: 28.000đ/miếng\r\nGà giòn giòn: 28.000đ/miếng\r\n🧀 Món đặc biệt\r\nGà phô mai: 45.000đ/miếng\r\nGà Taekbak: 25.000đ/miếng\r\n🍟 Món ăn nhẹ\r\nKhoai tây lắc (phô mai / rong biển): 15.000đ/phần\r\nBánh bao: từ 7.000đ – 15.000đ/cái\r\nChân gà chiên giòn: 10.000đ/cặp', '1777935659_foody-ga-ran-five-star-doi-can-357-637353448397244485.jpeg', 12),
(18, 'Combo siêu ngon siêu hấp dẫn thèm chảy nước miếng', 'Không ngon cũng lấy hết xiền, ăn mặc thả ga, ngồi cười la lết ', 'Không ngon cũng lấy hết xiền, ăn mặc thả ga, ngồi cười la lết thức ăn ngon hết sảy con bà bảy', '1777937895_1551666192189_540.png', 16),
(19, 'Freeship cả năm – 365 ngày hoàn tiền hấp dẫn từ VPBank Shopee', 'Chương trình ưu đãi từ VPBank Shopee mang đến nhiều quyền lợi hấp dẫn như hoàn tiền mọi chi tiêu, miễn phí vận chuyển cả năm và nhiều quà tặng online. Đăng ký nhanh chóng, mở thẻ miễn phí và tận hưởng ưu đãi dài hạn.', 'Chương trình ưu đãi từ VPBank kết hợp cùng Shopee mang đến trải nghiệm mua sắm tiện lợi và tiết kiệm hơn bao giờ hết. Với thông điệp “Freeship cả năm – 365 ngày hoàn tiền”, người dùng có thể tận hưởng hàng loạt lợi ích khi sử dụng thẻ.\r\n\r\n💳 Quyền lợi nổi bật\r\nHoàn tiền cho mọi chi tiêu\r\nMiễn phí vận chuyển suốt 365 ngày\r\nNhận nhiều quà tặng và ưu đãi online\r\nĐăng ký mở thẻ nhanh chóng, không mất phí\r\n🎁 Ưu đãi dành cho người dùng\r\n\r\nChủ thẻ có thể sử dụng để thanh toán mua sắm trực tuyến, đặc biệt trên nền tảng Shopee, và nhận được nhiều ưu đãi độc quyền. Đây là lựa chọn phù hợp cho những ai thường xuyên mua sắm online và muốn tối ưu chi phí.\r\n\r\n⚡ Đăng ký dễ dàng\r\n\r\nViệc mở thẻ được thực hiện nhanh chóng, thao tác đơn giản, không yêu cầu thủ tục phức tạp. Người dùng có thể bắt đầu sử dụng ngay sau khi đăng ký thành công.', '1778055658_1.jpg', 10),
(20, '🍔 Ưu Đãi Burger Đặc Biệt – Giảm Đến 50% Hôm Nay!', '🔥 Cơ hội thưởng thức burger thơm ngon với ưu đãi lên đến 50%! 🍔 Thịt nướng đậm vị, rau tươi giòn và sốt đặc biệt đang chờ bạn. Đặt món ngay hôm nay để không bỏ lỡ chương trình hấp dẫn này! 🎉', '🎉 BURGER SPECIAL – ƯU ĐÃI CỰC HẤP DẪN!\r\n\r\nBạn là tín đồ của burger? 🍔 Đây chính là cơ hội không nên bỏ lỡ!\r\n\r\nBurger đặc biệt được kết hợp từ:\r\n\r\n🍞 Bánh burger mềm thơm\r\n🥩 Thịt nướng đậm đà, mọng nước\r\n🥬 Rau xanh tươi giòn\r\n🧀 Phô mai béo thơm\r\n🌶️ Sốt đặc biệt đầy hấp dẫn\r\n\r\n🔥 Đặc biệt, trong thời gian diễn ra chương trình, khách hàng có cơ hội nhận ưu đãi lên đến 50% cho các sản phẩm và combo được áp dụng.\r\n\r\n👨‍👩‍👧‍👦 Dù là bữa trưa nhanh, buổi gặp gỡ bạn bè hay một bữa ăn cùng gia đình, burger đặc biệt luôn là lựa chọn tuyệt vời.\r\n\r\n⏰ Ưu đãi có thời hạn – số lượng có hạn!\r\n\r\n🛒 Đặt món ngay hôm nay và tận hưởng hương vị burger hấp dẫn với mức giá cực kỳ ưu đãi!\r\n\r\n🖼️ Hình ảnh đại diện:\r\nDùng ảnh burger khuyến mãi bạn vừa gửi.\r\n\r\nNếu muốn nhìn giống tin khuyến mãi của KFC/Lotteria/Jollibee hơn, tiêu đề mình sẽ chọn:\r\n\r\n🔥 DEAL HOT: BURGER ĐẶC BIỆT GIẢM ĐẾN 50% 🍔', 'posts/20260809093245-b95a41d7-1716270389_oip-3.jpg', 17),
(21, '🍕🔥 Pizza An Viên – 100% Bột Tươi, Ngon Khác Biệt, Freeship Cực Đã! 🚚', '🍕 Thưởng thức ngay các vị pizza siêu hấp dẫn tại Pizza An Viên với 100% bột tươi, nguyên liệu chất lượng và hương vị thơm ngon khó cưỡng. Nhiều lựa chọn hấp dẫn như Margherita, Pepperoni, Vegetarian, Seafood Pizza… đặc biệt còn có FREE SHIP cực tiện lợi! 😍🚚', '🤩 TÍN ĐỒ PIZZA ƠI, BẠN ĐÃ SẴN SÀNG CHƯA? 🍕\r\n\r\nNếu bạn đang tìm một món ăn vừa ngon – hấp dẫn – tiện lợi thì Pizza An Viên chính là lựa chọn không nên bỏ lỡ! 🔥\r\n\r\n✨ Với tiêu chí “100% bột tươi – Chúng tôi khác!”, mỗi chiếc pizza tại đây đều được chuẩn bị kỹ lưỡng từ phần đế bánh đến lớp topping đầy đặn bên trên. Bánh thơm, nóng hổi, đậm vị và cực kỳ cuốn hút ngay từ ánh nhìn đầu tiên! 😋\r\n\r\n💥 Menu hấp dẫn với nhiều hương vị cho bạn lựa chọn:\r\n🍅 Margherita – vị truyền thống nhẹ nhàng, dễ ăn\r\n🍖 Pepperoni – đậm đà, thơm ngon cực cuốn\r\n🥦 Vegetarian – thanh nhẹ, nhiều rau củ tươi ngon\r\n🔥 Crazy Pizza – hương vị độc đáo, hấp dẫn\r\n🍕 Meat Lovers – dành cho team mê thịt\r\n🦐 Seafood Pizza – thơm ngon với hải sản hấp dẫn\r\n\r\n❤️ Không chỉ ngon miệng, pizza tại đây còn được làm từ nguyên liệu chất lượng, mang đến cảm giác tươi mới và tròn vị trong từng miếng bánh.\r\n\r\n🚚 Điểm cộng siêu thích:\r\n✅ Nhiều vị pizza hấp dẫn\r\n✅ Bột tươi thơm ngon, khác biệt\r\n✅ Phù hợp ăn cùng gia đình, bạn bè, đồng nghiệp\r\n✅ FREESHIP tiện lợi, đặt là có ngay\r\n\r\n🎉 Dù là bữa trưa, bữa tối, tiệc nhỏ hay đơn giản là lúc thèm một món gì đó thật ngon, Pizza An Viên đều có thể làm bạn hài lòng.\r\n\r\n👉 Gọi ngay – đặt ngay – thưởng thức ngay để cảm nhận vị ngon hấp dẫn từ những chiếc pizza nóng hổi nhé! 🍕🔥', 'posts/20260810042707-b01600d7-to-roi-pizza-400x281.png', 12),
(22, '🍔🔥 Combo Burger Bò Siêu Ngon – Cắn Một Miếng Là Mê! 🍟', '🍔 Burger bò thơm lừng, phô mai béo ngậy, rau tươi giòn mát kết hợp cùng 🍟 khoai tây chiên vàng giòn cực cuốn. Một combo hoàn hảo cho những chiếc bụng đang “réo gọi”! 😋🔥\r\n\r\nNội dung chi tiết', '🍔 TEAM MÊ BURGER ĐÂU RỒI? 🤩\r\n\r\nNếu bạn đang tìm một món ăn vừa ngon – no – tiện lợi thì combo Burger Bò & Khoai Tây Chiên chắc chắn là lựa chọn không nên bỏ qua! 🔥\r\n\r\n🥩 Thịt bò đậm đà, mềm mọng và thơm hấp dẫn.\r\n🧀 Phô mai béo ngậy tan nhẹ trong từng miếng cắn.\r\n🥬 Rau xanh tươi giòn giúp hương vị cân bằng, không bị ngấy.\r\n🍅 Kết hợp cùng rau củ và nước sốt đặc biệt tạo nên chiếc burger đầy đặn, hấp dẫn.\r\n🍟 Và tất nhiên không thể thiếu khoai tây chiên vàng giòn, ăn kèm là hết sẩy!\r\n\r\n✨ Một miếng burger – một miếng khoai – thêm chút nước ngọt mát lạnh là đủ cho một bữa ăn cực kỳ “đã”! 😍\r\n\r\n👫 Phù hợp để ăn trưa, ăn tối, tụ tập bạn bè hay đơn giản là tự thưởng cho bản thân sau một ngày dài.\r\n\r\n🔥 Đừng để chiếc bụng phải chờ lâu!\r\n👉 Ghé ngay và thưởng thức combo Burger Bò & Khoai Tây Chiên hấp dẫn này nhé! 🍔🍟❤️', 'posts/20260810042939-13126dea-oip-6.jpg', 12),
(23, '🌭🔥 Super Delicious – Bánh Mì Kẹp Siêu Ngon Giảm Ngay 50%! 😍', '🌭 Thưởng thức ngay món bánh mì kẹp siêu hấp dẫn với phần nhân đầy đặn, rau tươi giòn ngon và hương vị cực cuốn. Đặc biệt ưu đãi giảm 50% cùng dịch vụ giao hàng nhanh chóng 24/7! 🚚🔥', '🤩 TÍN ĐỒ ĂN VẶT NGON ĐÂU RỒI?\r\n\r\nMột món ngon vừa hấp dẫn, vừa tiện lợi lại còn đang có ưu đãi lớn thì ngại gì mà không thử ngay? 🌭✨\r\n\r\nChiếc bánh mì kẹp siêu ngon với phần nhân đầy ụ, thịt thơm mềm hấp dẫn, kết hợp cùng rau xanh tươi mát và lớp bánh vàng ngon bắt mắt sẽ khiến bạn chỉ muốn thưởng thức ngay từ cái nhìn đầu tiên! 😋\r\n\r\n💥 Điểm hấp dẫn của món này:\r\n🌭 Nhân bánh đầy đặn, thơm ngon\r\n🥬 Rau tươi giòn giúp cân bằng hương vị\r\n🔥 Món ăn nóng hổi, hấp dẫn\r\n🚚 Giao hàng 24/7, tiện lợi mọi lúc\r\n💸 Ưu đãi giảm ngay 50%, quá hời để thưởng thức\r\n\r\nDù là bữa xế, bữa tối hay những lúc bụng đói bất chợt, đây chắc chắn là lựa chọn tuyệt vời dành cho bạn. Ăn một lần là nhớ mãi, nhìn một lần là muốn đặt ngay! ❤️\r\n\r\n📞 Đừng chần chừ nữa, lên đơn ngay hôm nay để tận hưởng món ngon cùng ưu đãi cực hấp dẫn nhé!', 'posts/20260810043018-d68e4667-oip-1.jpg', 12),
(24, '🍔🔥 Burger Siêu Ngon – Combo Đậm Vị, Ăn Là Ghiền! 🍟😍', '🍔 Burger nóng hổi với phần nhân đầy đặn, thịt thơm ngon, rau tươi giòn và sốt đậm vị, ăn kèm khoai tây chiên vàng giòn cực cuốn. Một combo hoàn hảo cho những ngày muốn tự thưởng cho bản thân! 😋🍟', '🤩 TEAM BURGER ĐÂU RỒI?\r\n\r\nChỉ cần nhìn thôi là đã thấy đói rồi! 🍔🔥 Một chiếc burger hấp dẫn với phần bánh mềm thơm, lớp thịt đậm đà, rau xanh tươi mát và phần sốt béo ngon hòa quyện trong từng miếng cắn.\r\n\r\n🍟 Đi kèm là khoai tây chiên vàng giòn, nóng hổi, thơm ngon và cực kỳ hợp khi thưởng thức cùng burger.\r\n\r\n✨ Điểm hấp dẫn của combo:\r\n🥩 Thịt thơm ngon, đậm vị\r\n🧀 Nhân đầy đặn, béo thơm hấp dẫn\r\n🥬 Rau tươi giòn giúp món ăn không bị ngấy\r\n🍟 Khoai tây chiên giòn rụm cực cuốn\r\n🔥 Phù hợp cho bữa trưa, bữa tối hoặc ăn cùng bạn bè\r\n\r\n😋 Một miếng burger mềm thơm, thêm vài miếng khoai giòn và một ly nước mát lạnh là đủ tạo nên một bữa ăn cực kỳ thỏa mãn!\r\n\r\n👫 Dù là tụ tập bạn bè, hẹn hò cuối tuần hay đơn giản chỉ muốn thưởng cho mình một món ngon, combo này đều là lựa chọn rất đáng thử.\r\n\r\n👉 Đừng để chiếc bụng phải chờ lâu!\r\n🍔🍟 Ghé ngay và thưởng thức combo Burger & Khoai Tây Chiên siêu hấp dẫn nhé! ❤️🔥', 'posts/20260810043127-5805f865-oip-2.jpg', 12),
(25, '🍕🔥 MUA 1 TẶNG 1 MỖI NGÀY – Ăn Pizza Thả Ga, Deal Cực Đã! 🎁', '🎉 Deal hấp dẫn dành cho hội mê Pizza! 🍕 MUA 1 TẶNG 1 MỖI NGÀY – thưởng thức pizza thơm ngon cùng ưu đãi cực hời. 🚀 Đặc biệt còn có MIỄN PHÍ GIAO HÀNG ~22.000 VNĐ. Rủ ngay hội bạn cùng thưởng thức thôi! 😍🔥', '🍕🔥 MUA 1 TẶNG 1 – DEAL NGON KHÔNG THỂ BỎ LỠ! 🔥🍕\r\n\r\nBạn là tín đồ của Pizza? 😍 Vậy thì đừng bỏ qua chương trình khuyến mãi cực hấp dẫn này!\r\n\r\n🎁 ƯU ĐÃI NỔI BẬT:\r\n🍕 Mua 1 Pizza – Tặng 1 Pizza\r\n🎉 Áp dụng MỖI NGÀY\r\n🛵 Miễn phí giao hàng ~22.000 VNĐ\r\n🥤 Thưởng thức Pizza nóng hổi cùng Coca-Cola mát lạnh\r\n👨‍👩‍👧‍👦 Cực thích hợp để ăn cùng bạn bè, gia đình và đồng nghiệp\r\n\r\n✨ Pizza nóng hổi với lớp phô mai béo ngậy 🧀, topping đầy đặn 🍤🍖🫑 cùng phần đế bánh thơm ngon đang chờ bạn thưởng thức!\r\n\r\n💥 MUA 1 ĐƯỢC 2 – VỪA NGON VỪA TIẾT KIỆM!\r\n\r\n📲 Nhanh tay đặt món và tận hưởng bữa tiệc Pizza thật hoành tráng ngay hôm nay! 🍕🎊\r\n\r\n⚡ ĐỪNG BỎ LỠ – DEAL HOT ĐANG CHỜ BẠN! 🔥🔥🔥', 'posts/20260811094605-343e98ed-62f4d8146797635-62b6a039cae38.jpg', 12),
(26, '🍜🔥 THỎA THÍCH ĂN VẶT – ẤM THỰC SỰ HẤP DẪN CHỈ TỪ 15K! 🌶️🥢', '🎉 Hội mê ăn vặt đâu rồi! 😍 Cơ hội thưởng thức hàng loạt món ngon nóng hổi 🍜🍢 với ưu đãi 15K cho đơn chỉ từ 100K. Tha hồ gọi món, ăn ngon hết ý mà vẫn tiết kiệm túi tiền! 🔥💸', '🍜🔥 THỎA THÍCH ĂN VẶT – ẤM BỤNG, NO NÊ! 🔥🍜\r\n\r\nBạn đang thèm một bữa ăn nóng hổi, cay cay và đầy hấp dẫn? 🌶️🤤 Deal 15K chính là lý do tuyệt vời để rủ hội bạn cùng nhau thưởng thức ngay hôm nay!\r\n\r\n🎟️ ƯU ĐÃI HẤP DẪN:\r\n💰 Ưu đãi 15K\r\n🛒 Áp dụng cho đơn chỉ từ 100K\r\n🍜 Mì nóng hổi với topping hấp dẫn\r\n🍢 Đa dạng món ăn vặt để tha hồ lựa chọn\r\n🌶️ Hương vị đậm đà, cay ngon kích thích vị giác\r\n👫 Cực thích hợp để ăn cùng bạn bè, đồng nghiệp hoặc hội mê ăn vặt\r\n\r\n🥢 Một tô mì nóng nghi ngút khói 🍜, thêm topping đầy đặn 🥩🥬, kết hợp cùng những món ăn vặt hấp dẫn – nhìn thôi đã muốn thưởng thức ngay! 😋\r\n\r\n💥 ĂN NGON – SĂN DEAL – TIẾT KIỆM HẾT Ý! 💥\r\n\r\n📲 Nhanh tay gọi món và tận hưởng ngay một bữa ăn thật ngon cùng ưu đãi 15K cho đơn từ 100K nhé! 🎉\r\n\r\n⚡ DEAL NGON ĐANG CHỜ – ĐỪNG BỎ LỠ! 🔥🍜🥢', 'posts/20260811095030-bc469e4b-7fed2aa5b71e323f9ed2cbad621d40c2.jpg', 10),
(27, '🍗🔥 HÈ THẢ GA – GÀ GIÒN CỰC ĐÃ, COMBO CHỈ 139K! 🍟🥤', '🎉 Hè này ăn gì cho thật đã? 😍 Thưởng thức ngay combo gà rán giòn rụm 🍗 + khoai tây chiên 🍟 + Pepsi mát lạnh 🥤 + kem mát lạnh 🍦 với mức giá ưu đãi chỉ 139.000đ. Một combo hấp dẫn cho những buổi tụ tập cùng bạn bè và gia đình! 🔥', '🍗☀️ HÈ THẢ GA – GÀ GIÒN CỰC ĐÃ! ☀️🍗\r\n\r\nMùa hè thêm vui với một bữa tiệc gà rán vàng giòn, nóng hổi và đầy hấp dẫn! 🤤🔥 Rủ ngay hội bạn và gia đình cùng thưởng thức combo cực đã với mức giá ưu đãi.\r\n\r\n🎁 COMBO HẤP DẪN BAO GỒM:\r\n🍗 Gà rán vàng giòn, hấp dẫn\r\n🍟 Khoai tây chiên giòn thơm\r\n🥤 Pepsi mát lạnh, giải nhiệt cực đã\r\n🍦 Kem mềm mịn, ngọt mát\r\n🍠 Món ăn kèm hấp dẫn\r\n\r\n💥 GIÁ ƯU ĐÃI CHỈ 139.000Đ\r\n176.000đ ➜ 🔥 CÒN 139.000Đ\r\n\r\n👨‍👩‍👧‍👦 Combo phù hợp để cùng bạn bè và gia đình thưởng thức, vừa no nê vừa tiết kiệm! 🎊\r\n\r\n📍 Theo thông tin trên chương trình, ưu đãi áp dụng tại khu vực miền Trung.\r\n\r\n⚡ GÀ GIÒN RỤM – KHOAI GIÒN TAN – NƯỚC MÁT LẠNH, CÒN CHỜ GÌ MÀ KHÔNG THƯỞNG THỨC! 🍗🍟🥤✨', 'posts/20260811095125-caffb36d-30-6-3.jpg', 12),
(29, '🎄🍗 COMBO MÊ HỘI CHỈ 149K – Ăn Ngon Rộn Ràng, Nhận Ngay Tuần Lộc! 🎁🦌', '🎉 Mùa lễ hội thêm vui với Combo Mê Hội chỉ 149.000đ! 🍗🍝 Thưởng thức gà giòn rụm, Mỳ Ý đậm đà, khoai tây lắc BBQ và Pepsi mát lạnh 🥤. Đặc biệt còn được tặng ngay 01 Tuần Lộc siêu đáng yêu 🦌🎁. Nhanh tay thưởng thức trước khi quà tặng hết nhé! 🔥', '🎄✨ VUI RỘN RÀNG CÙNG COMBO MÊ HỘI – CHỈ 149K! ✨🎄\r\n\r\nMùa lễ hội này ăn gì vừa ngon vừa vui? 😍 Jollibee mang đến Combo Mê Hội với hàng loạt món hấp dẫn và đặc biệt có thêm quà tặng Tuần Lộc cực dễ thương! 🦌❤️\r\n\r\n🎁 COMBO 01 – 149.000Đ\r\n🍗 02 Gà Giòn\r\n🍝 01 Mỳ Ý\r\n🍟 01 Khoai tây lắc BBQ vừa\r\n🥤 02 Pepsi vừa\r\n🦌 Tặng 01 Tuần Lộc\r\n\r\n🎁 COMBO 02 – 149.000Đ\r\n🍗 01 Gà Giòn\r\n🍝 02 Mỳ Ý\r\n🍟 01 Khoai tây lắc BBQ vừa\r\n🥤 02 Pepsi vừa\r\n🦌 Tặng 01 Tuần Lộc\r\n\r\n💥 ĐỒNG GIÁ CHỈ 149.000Đ/COMBO – tha hồ lựa chọn combo phù hợp với sở thích của bạn! 😋🍗\r\n\r\n📅 Thời gian áp dụng:\r\n👉 Từ 01/12/2025 – 31/01/2026 hoặc đến khi hết quà tặng.\r\n\r\n🦌🎁 Vừa thưởng thức món ngon, vừa mang về một bé Tuần Lộc đáng yêu – quá thích hợp cho không khí lễ hội cuối năm! 🎄✨\r\n\r\n📲 Nhanh tay thưởng thức Combo Mê Hội và nhận quà trước khi hết nhé! 🍗🍝🍟🥤\r\n\r\n🔥 ĂN NGON – QUÀ XINH – MÊ HỘI HẾT Ý! 🎉🦌❤️', 'posts/20260811151222-f407ae9f-vn-11134259-820l4-miwtm9zog1kwbe.jpg', 10);

-- --------------------------------------------------------

--
-- Cấu trúc bảng cho bảng `tbl_banner`
--

CREATE TABLE `tbl_banner` (
  `id_banner` int(11) NOT NULL,
  `title` varchar(200) NOT NULL,
  `subtitle` varchar(255) DEFAULT NULL,
  `image_path` varchar(255) NOT NULL,
  `image_source` enum('asset','upload') NOT NULL DEFAULT 'asset',
  `link` varchar(255) DEFAULT NULL,
  `sort_order` int(11) NOT NULL DEFAULT 0,
  `is_active` tinyint(1) NOT NULL DEFAULT 1,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Đang đổ dữ liệu cho bảng `tbl_banner`
--

INSERT INTO `tbl_banner` (`id_banner`, `title`, `subtitle`, `image_path`, `image_source`, `link`, `sort_order`, `is_active`, `created_at`, `updated_at`) VALUES
(1, 'FastFood Kiosk', 'Đặt món nhanh, trãi nghiệm như kiosk tại cửa hàng.', 'banners/BANNER+BOGO3+SING+NEW.jpg', 'asset', '', 1, 1, '2026-08-08 13:02:46', '2026-08-11 08:34:32'),
(3, 'Ưu đãi nổi bật', '', 'banners/BANNER+LIME+(1).png', 'asset', '', 4, 1, '2026-08-08 13:02:46', '2026-08-11 00:17:20'),
(8, 'banner 2', '', 'banners/20260811020825-24fb0fb5-pizza-food-social-media-web-banner-cover-graphics-.jpg', 'upload', '', 0, 1, '2026-08-11 00:08:25', '2026-08-12 00:50:15'),
(16, '4', '', 'banners/20260812074858-663d03b9-fast-food-business-promotion-web-banner-template-d.jpg', 'upload', '', 6, 1, '2026-08-12 00:48:58', '2026-08-12 00:49:57');

-- --------------------------------------------------------

--
-- Cấu trúc bảng cho bảng `tbl_banner_setting`
--

CREATE TABLE `tbl_banner_setting` (
  `id` tinyint(1) NOT NULL DEFAULT 1,
  `visible_limit` int(11) NOT NULL DEFAULT 0,
  `updated_at` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Đang đổ dữ liệu cho bảng `tbl_banner_setting`
--

INSERT INTO `tbl_banner_setting` (`id`, `visible_limit`, `updated_at`) VALUES
(1, 0, '2026-08-11 00:08:45');

-- --------------------------------------------------------

--
-- Cấu trúc bảng cho bảng `tbl_chatbot_history`
--

CREATE TABLE `tbl_chatbot_history` (
  `id` int(11) NOT NULL,
  `user_message` text NOT NULL,
  `bot_response` text NOT NULL,
  `matched_keyword` varchar(255) DEFAULT NULL,
  `response_type` varchar(50) DEFAULT 'static',
  `user_ip` varchar(45) DEFAULT NULL,
  `user_agent` varchar(255) DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Đang đổ dữ liệu cho bảng `tbl_chatbot_history`
--

INSERT INTO `tbl_chatbot_history` (`id`, `user_message`, `bot_response`, `matched_keyword`, `response_type`, `user_ip`, `user_agent`, `created_at`) VALUES
(12, 'Giờ mở cửa', '⏰ Cửa hàng mở cửa từ 9:00 sáng đến 22:00 tối, cả tuần kể cả ngày lễ. Đến sớm để chọn món ngon nhé!', 'gio mo cua', 'static', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/147.0.0.0 Safari/537.36 Edg/147.0.0.0', '2026-05-06 10:34:40'),
(13, 'Còn gà rán không', 'Không tìm thấy món này.', '', 'api_stock', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/147.0.0.0 Safari/537.36 Edg/147.0.0.0', '2026-05-06 10:34:47'),
(14, 'mon gi ngon', 'Xin lỗi, tôi chưa hiểu ý bạn lắm 😅 Bạn thử hỏi bằng tiếng Việt không dấu hoặc dùng các từ khóa như:<br>• \"Thực đơn có gì?\"<br>• \"Giá pizza bao nhiêu?\"<br>• \"Còn gà rán không?\"<br>• \"Khuyến mãi hiện tại\"<br>• \"Địa chỉ cửa hàng\"<br>• \"Giờ mở cửa\"', '', 'fallback', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/147.0.0.0 Safari/537.36 Edg/147.0.0.0', '2026-05-06 10:34:55'),
(15, 'Thực đơn có gì', '🍕 Hiện tại chúng tôi có 10 món đang bán:\n1. Cơm trắng - 25,000đ\n2. Pepsi - 25,000đ\n3. Pizza Hải Sản - 120,000đ\n4. Súp Rong Biển - 45,000đ\n5. Aquafina - 15,000đ\n... và 5 món khác!', 'thuc don', 'api_products', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/147.0.0.0 Safari/537.36 Edg/147.0.0.0', '2026-05-06 10:35:05'),
(16, 'Thực đơn có gì?', '🍕 Hiện tại chúng tôi có 10 món đang bán:\n1. Salad gà giòn tươi mát - 45,000đ\n2. Cơm trắng - 25,000đ\n3. Pepsi - 25,000đ\n4. Pizza Hải Sản - 120,000đ\n5. Súp Rong Biển - 45,000đ\n... và 5 món khác!', 'thuc don', 'api_products', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/147.0.0.0 Safari/537.36 Edg/147.0.0.0', '2026-05-06 10:37:22'),
(17, '5 món khác', 'Xin lỗi, tôi chưa hiểu ý bạn lắm 😅 Bạn thử hỏi bằng tiếng Việt không dấu hoặc dùng các từ khóa như:<br>• \"Thực đơn có gì?\"<br>• \"Giá pizza bao nhiêu?\"<br>• \"Còn gà rán không?\"<br>• \"Khuyến mãi hiện tại\"<br>• \"Địa chỉ cửa hàng\"<br>• \"Giờ mở cửa\"', '', 'fallback', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/147.0.0.0 Safari/537.36 Edg/147.0.0.0', '2026-05-06 10:37:36'),
(18, 'Địa chỉ', '📍 Cửa hàng của chúng tôi tại: Quận 7, Thành phố Hồ Chí Minh. Hotline: 1900 6099.', 'dia chi', 'static', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/147.0.0.0 Safari/537.36 Edg/147.0.0.0', '2026-05-06 10:52:48'),
(19, 'Địa chỉ', '📍 Cửa hàng của chúng tôi tại: Quận 7, Thành phố Hồ Chí Minh. Hotline: 1900 1234.', 'dia chi', 'static', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/147.0.0.0 Safari/537.36 Edg/147.0.0.0', '2026-05-07 05:21:29'),
(20, 'lien he', '📞 Hotline: 1900 1234 | 📍 Địa chỉ: Quận 7, TP.HCM | 📧 Email: congtoan2k4@gmail.com', 'lien he', 'static', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/147.0.0.0 Safari/537.36 Edg/147.0.0.0', '2026-05-07 05:21:34'),
(21, 'Khuyến mãi', '🎉 Khuyến mãi:\n1. GIẢM 50% TỔNG HÓA ĐƠN & FREESHIP\n', 'khuyen mai', 'api_promo', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/147.0.0.0 Safari/537.36 Edg/147.0.0.0', '2026-05-07 05:36:46'),
(22, 'Địa chỉ', '📍 Cửa hàng của chúng tôi tại: Quận 7, Thành phố Hồ Chí Minh. Hotline: 1900 1234.', 'dia chi', 'static', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/147.0.0.0 Safari/537.36 Edg/147.0.0.0', '2026-05-07 05:36:48'),
(23, 'Thực đơn có gì?', '🍕 Hiện tại chúng tôi có 10 món đang bán:\n1. Salad gà giòn tươi mát - 45,000đ\n2. Cơm trắng - 25,000đ\n3. Pepsi - 25,000đ\n4. Pizza Hải Sản - 120,000đ\n5. Súp Rong Biển - 45,000đ\n... và 5 món khác!', 'thuc don', 'api_products', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/147.0.0.0 Safari/537.36 Edg/147.0.0.0', '2026-05-07 05:36:50'),
(24, 'Địa chỉ', '📍 Cửa hàng của chúng tôi tại: Quận 7, Thành phố Hồ Chí Minh. Hotline: 1900 6099.', 'dia chi', 'static', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/147.0.0.0 Safari/537.36 Edg/147.0.0.0', '2026-05-07 05:43:15'),
(25, 'so dien thoai', 'Xin lỗi, tôi chưa hiểu ý bạn lắm 😅 Bạn thử hỏi bằng tiếng Việt không dấu hoặc dùng các từ khóa như:<br>• \"Thực đơn có gì?\"<br>• \"Giá pizza bao nhiêu?\"<br>• \"Còn gà rán không?\"<br>• \"Khuyến mãi hiện tại\"<br>• \"Địa chỉ cửa hàng\"<br>• \"Giờ mở cửa\"', '', 'fallback', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/147.0.0.0 Safari/537.36 Edg/147.0.0.0', '2026-05-07 05:43:21'),
(26, 'Khuyến mãi', '🎉 Khuyến mãi:\n1. GIẢM 50% TỔNG HÓA ĐƠN & FREESHIP\n', 'khuyen mai', 'api_promo', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/147.0.0.0 Safari/537.36 Edg/147.0.0.0', '2026-05-07 05:43:24'),
(27, 'Giá món ăn', '🍕 Hiện tại chúng tôi có 10 món đang bán:\n1. Salad gà giòn tươi mát - 45,000đ\n2. Cơm trắng - 25,000đ\n3. Pepsi - 25,000đ\n4. Pizza Hải Sản - 120,000đ\n5. Súp Rong Biển - 45,000đ\n... và 5 món khác!', 'thuc don', 'api_products', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/147.0.0.0 Safari/537.36 Edg/147.0.0.0', '2026-05-07 05:43:26'),
(28, 'Thực đơn có gì?', '🍕 Hiện tại chúng tôi có 10 món đang bán:\n1. Salad gà giòn tươi mát - 45,000đ\n2. Cơm trắng - 25,000đ\n3. Pepsi - 25,000đ\n4. Pizza Hải Sản - 120,000đ\n5. Súp Rong Biển - 45,000đ\n... và 5 món khác!', 'thuc don', 'api_products', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/147.0.0.0 Safari/537.36 Edg/147.0.0.0', '2026-05-07 05:43:27'),
(29, 'Thực đơn có gì?', '🍕 Hiện tại chúng tôi có 10 món đang bán:\n1. Salad gà giòn tươi mát - 45,000đ\n2. Cơm trắng - 25,000đ\n3. Pepsi - 25,000đ\n4. Pizza Hải Sản - 120,000đ\n5. Súp Rong Biển - 45,000đ\n... và 5 món khác!', 'thuc don', 'api_products', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Code/1.118.1 Chrome/142.0.7444.265 Electron/39.8.8 Safari/537.36', '2026-05-07 06:01:23'),
(30, 'Thực đơn có gì?', '🍕 Hiện tại chúng tôi có 10 món đang bán:\n1. Salad gà giòn tươi mát - 45,000đ\n2. Cơm trắng - 25,000đ\n3. Pepsi - 25,000đ\n4. Pizza Hải Sản - 120,000đ\n5. Súp Rong Biển - 45,000đ\n... và 5 món khác!', 'thuc don', 'api_products', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Code/1.118.1 Chrome/142.0.7444.265 Electron/39.8.8 Safari/537.36', '2026-05-07 06:01:24'),
(31, 'Địa chỉ', '📍 Cửa hàng của chúng tôi tại: Quận 7, Thành phố Hồ Chí Minh. Hotline: 1900 6099.', 'dia chi', 'static', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Code/1.118.1 Chrome/142.0.7444.265 Electron/39.8.8 Safari/537.36', '2026-05-07 06:01:26'),
(32, 'Giá món ăn', '🍕 Hiện tại chúng tôi có 10 món đang bán:\n1. Salad gà giòn tươi mát - 45,000đ\n2. Cơm trắng - 25,000đ\n3. Pepsi - 25,000đ\n4. Pizza Hải Sản - 120,000đ\n5. Súp Rong Biển - 45,000đ\n... và 5 món khác!', 'thuc don', 'api_products', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Code/1.118.1 Chrome/142.0.7444.265 Electron/39.8.8 Safari/537.36', '2026-05-07 06:01:26'),
(33, 'Khuyến mãi', '🎉 Khuyến mãi:\n1. GIẢM 50% TỔNG HÓA ĐƠN & FREESHIP\n', 'khuyen mai', 'api_promo', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Code/1.118.1 Chrome/142.0.7444.265 Electron/39.8.8 Safari/537.36', '2026-05-07 06:01:26'),
(34, 'Giá món ăn', '🍕 Hiện tại chúng tôi có 10 món đang bán:\n1. Salad gà giòn tươi mát - 45,000đ\n2. Cơm trắng - 25,000đ\n3. Pepsi - 25,000đ\n4. Pizza Hải Sản - 120,000đ\n5. Súp Rong Biển - 45,000đ\n... và 5 món khác!', 'thuc don', 'api_products', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Code/1.118.1 Chrome/142.0.7444.265 Electron/39.8.8 Safari/537.36', '2026-05-07 06:01:27'),
(35, 'Khuyến mãi', '🎉 Khuyến mãi:\n1. GIẢM 50% TỔNG HÓA ĐƠN & FREESHIP\n', 'khuyen mai', 'api_promo', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Code/1.118.1 Chrome/142.0.7444.265 Electron/39.8.8 Safari/537.36', '2026-05-07 06:01:27'),
(36, 'Địa chỉ', '📍 Cửa hàng của chúng tôi tại: Quận 7, Thành phố Hồ Chí Minh. Hotline: 1900 6099.', 'dia chi', 'static', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Code/1.118.1 Chrome/142.0.7444.265 Electron/39.8.8 Safari/537.36', '2026-05-07 06:01:27'),
(37, 'Giá món ăn', '🍕 Hiện tại chúng tôi có 10 món đang bán:\n1. Salad gà giòn tươi mát - 45,000đ\n2. Cơm trắng - 25,000đ\n3. Pepsi - 25,000đ\n4. Pizza Hải Sản - 120,000đ\n5. Súp Rong Biển - 45,000đ\n... và 5 món khác!', 'thuc don', 'api_products', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Code/1.118.1 Chrome/142.0.7444.265 Electron/39.8.8 Safari/537.36', '2026-05-07 06:01:28'),
(38, 'Khuyến mãi', '🎉 Khuyến mãi:\n1. GIẢM 50% TỔNG HÓA ĐƠN & FREESHIP\n', 'khuyen mai', 'api_promo', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Code/1.118.1 Chrome/142.0.7444.265 Electron/39.8.8 Safari/537.36', '2026-05-07 06:01:28'),
(39, 'Địa chỉ', '📍 Cửa hàng của chúng tôi tại: Quận 7, Thành phố Hồ Chí Minh. Hotline: 1900 6099.', 'dia chi', 'static', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/147.0.0.0 Safari/537.36 Edg/147.0.0.0', '2026-05-07 23:41:03'),
(40, 'Thực đơn có gì?', '🍕 Hiện tại chúng tôi có 10 món đang bán:\n1. Salad gà giòn tươi mát - 45,000đ\n2. Cơm trắng - 25,000đ\n3. Pepsi - 25,000đ\n4. Pizza Hải Sản - 120,000đ\n5. Súp Rong Biển - 45,000đ\n... và 5 món khác!', 'thuc don', 'api_products', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/147.0.0.0 Safari/537.36 Edg/147.0.0.0', '2026-05-07 23:41:03'),
(41, 'Giá món ăn', '🍕 Hiện tại chúng tôi có 10 món đang bán:\n1. Salad gà giòn tươi mát - 45,000đ\n2. Cơm trắng - 25,000đ\n3. Pepsi - 25,000đ\n4. Pizza Hải Sản - 120,000đ\n5. Súp Rong Biển - 45,000đ\n... và 5 món khác!', 'thuc don', 'api_products', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/147.0.0.0 Safari/537.36 Edg/147.0.0.0', '2026-05-07 23:41:03'),
(42, 'Thực đơn có gì?', '🍕 Hiện tại chúng tôi có 10 món đang bán:\n1. Salad gà giòn tươi mát - 45,000đ\n2. Cơm trắng - 25,000đ\n3. Pepsi - 25,000đ\n4. Pizza Hải Sản - 120,000đ\n5. Súp Rong Biển - 45,000đ\n... và 5 món khác!', 'thuc don', 'api_products', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/147.0.0.0 Safari/537.36 Edg/147.0.0.0', '2026-05-07 23:41:04'),
(43, 'Khuyến mãi', '🎉 Khuyến mãi:\n1. GIẢM 50% TỔNG HÓA ĐƠN & FREESHIP\n', 'khuyen mai', 'api_promo', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/147.0.0.0 Safari/537.36 Edg/147.0.0.0', '2026-05-07 23:41:04'),
(44, 'Giá món ăn', '🍕 Hiện tại chúng tôi có 10 món đang bán:\n1. Salad gà giòn tươi mát - 45,000đ\n2. Cơm trắng - 25,000đ\n3. Pepsi - 25,000đ\n4. Pizza Hải Sản - 120,000đ\n5. Súp Rong Biển - 45,000đ\n... và 5 món khác!', 'thuc don', 'api_products', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/147.0.0.0 Safari/537.36 Edg/147.0.0.0', '2026-05-07 23:41:05'),
(45, 'Thực đơn có gì?', '🍕 Hiện tại chúng tôi có 10 món đang bán:\n1. Salad gà giòn tươi mát - 45,000đ\n2. Cơm trắng - 25,000đ\n3. Pepsi - 25,000đ\n4. Pizza Hải Sản - 120,000đ\n5. Súp Rong Biển - 45,000đ\n... và 5 món khác!', 'thuc don', 'api_products', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/147.0.0.0 Safari/537.36 Edg/147.0.0.0', '2026-05-07 23:41:05'),
(46, 'Địa chỉ', '📍 Cửa hàng của chúng tôi tại: Quận 7, Thành phố Hồ Chí Minh. Hotline: 1900 6099.', 'dia chi', 'static', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/147.0.0.0 Safari/537.36 Edg/147.0.0.0', '2026-05-07 23:41:06'),
(47, 'Khuyến mãi', '🎉 Khuyến mãi:\n1. GIẢM 50% TỔNG HÓA ĐƠN & FREESHIP\n', 'khuyen mai', 'api_promo', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/147.0.0.0 Safari/537.36 Edg/147.0.0.0', '2026-05-07 23:41:06'),
(48, 'Giá món ăn', '🍕 Hiện tại chúng tôi có 10 món đang bán:\n1. Salad gà giòn tươi mát - 45,000đ\n2. Cơm trắng - 25,000đ\n3. Pepsi - 25,000đ\n4. Pizza Hải Sản - 120,000đ\n5. Súp Rong Biển - 45,000đ\n... và 5 món khác!', 'thuc don', 'api_products', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/147.0.0.0 Safari/537.36 Edg/147.0.0.0', '2026-05-07 23:41:07'),
(49, 'Thực đơn có gì?', '🍕 Hiện tại chúng tôi có 10 món đang bán:\n1. Salad gà giòn tươi mát - 45,000đ\n2. Cơm trắng - 25,000đ\n3. Pepsi - 25,000đ\n4. Pizza Hải Sản - 120,000đ\n5. Súp Rong Biển - 45,000đ\n... và 5 món khác!', 'thuc don', 'api_products', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/147.0.0.0 Safari/537.36 Edg/147.0.0.0', '2026-05-07 23:41:07'),
(50, 'Địa chỉ', '📍 Cửa hàng của chúng tôi tại: Quận 7, Thành phố Hồ Chí Minh. Hotline: 1900 6099.', 'dia chi', 'static', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/147.0.0.0 Safari/537.36 Edg/147.0.0.0', '2026-05-07 23:41:07'),
(51, 'Thực đơn có gì?', '🍕 Hiện tại chúng tôi có 10 món đang bán:\n1. Salad gà giòn tươi mát - 45,000đ\n2. Cơm trắng - 25,000đ\n3. Pepsi - 25,000đ\n4. Pizza Hải Sản - 120,000đ\n5. Súp Rong Biển - 45,000đ\n... và 5 món khác!', 'thuc don', 'api_products', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/147.0.0.0 Safari/537.36 Edg/147.0.0.0', '2026-05-09 00:25:13'),
(52, 'Khuyến mãi', '🎉 Khuyến mãi:\n1. GIẢM 50% TỔNG HÓA ĐƠN & FREESHIP\n', 'khuyen mai', 'api_promo', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/147.0.0.0 Safari/537.36 Edg/147.0.0.0', '2026-05-09 00:25:23'),
(53, 'Thực đơn có gì?', '🍕 Hiện tại chúng tôi có 10 món đang bán:\n1. Salad gà giòn tươi mát - 45,000đ\n2. Cơm trắng - 25,000đ\n3. Pepsi - 25,000đ\n4. Pizza Hải Sản - 120,000đ\n5. Súp Rong Biển - 45,000đ\n... và 5 món khác!', 'thuc don', 'api_products', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/151.0.0.0 Safari/537.36 Edg/151.0.0.0', '2026-08-08 12:54:35'),
(54, 'Khuyến mãi', '🎉 Khuyến mãi:\n1. GIẢM 50% TỔNG HÓA ĐƠN & FREESHIP\n', 'khuyen mai', 'api_promo', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/151.0.0.0 Safari/537.36 Edg/151.0.0.0', '2026-08-08 13:14:29'),
(55, 'Giá món ăn', '🍕 Hiện tại chúng tôi có 10 món đang bán:\n1. Salad gà giòn tươi mát - 45,000đ\n2. Cơm trắng - 25,000đ\n3. Pepsi - 25,000đ\n4. Pizza Hải Sản - 120,000đ\n5. Súp Rong Biển - 45,000đ\n... và 5 món khác!', 'thuc don', 'api_products', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/151.0.0.0 Safari/537.36 Edg/151.0.0.0', '2026-08-08 22:47:09'),
(56, 'món gì ngon', 'Xin lỗi, tôi chưa hiểu ý bạn lắm 😅 Bạn thử hỏi bằng tiếng Việt không dấu hoặc dùng các từ khóa như:<br>• \"Thực đơn có gì?\"<br>• \"Giá pizza bao nhiêu?\"<br>• \"Còn gà rán không?\"<br>• \"Khuyến mãi hiện tại\"<br>• \"Địa chỉ cửa hàng\"<br>• \"Giờ mở cửa\"', '', 'fallback', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/151.0.0.0 Safari/537.36 Edg/151.0.0.0', '2026-08-08 22:47:15'),
(57, 'còn câu hỏi gì khác k', 'Không tìm thấy món này.', '', 'api_stock', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/151.0.0.0 Safari/537.36 Edg/151.0.0.0', '2026-08-08 22:47:28'),
(58, 'Tôi muốn món nhẹ, rẻ, còn hàng thì nên chọn gì?', 'Không tìm thấy món này.', '', 'api_stock', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/151.0.0.0 Safari/537.36 Edg/151.0.0.0', '2026-08-09 10:45:24'),
(59, 'Tôi muốn món nhẹ, rẻ, còn hàng thì nên chọn gì?', 'Chào bạn! Dưới đây là 3 gợi ý món ăn nhẹ, giá rẻ và đang có sẵn tại FastFood dành cho bạn:\n\n1. **Gà rán giòn 5 miếng** - Giá: 25.000đ (Còn: 50 phần) - Gà rán giòn rụm, dùng kèm xốt tùy chọn.\n2. **Pepsi Lon** - Giá: 20.000đ (Còn: 50 phần)\n3. **7Up Lon** - Giá: 20.000đ (Còn: 50 phần)\n\nBạn có muốn đặt các món này không? Hãy cho tôi biết nhé!', 'gemini', 'ai_gemini', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/151.0.0.0 Safari/537.36 Edg/151.0.0.0', '2026-08-09 11:10:59'),
(60, 'tôi muốn đặt 3 phần 7Up Lon', '💵 Giá từ 120,000đ đến 45,000đ.', 'gia', 'api_price', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/151.0.0.0 Safari/537.36 Edg/151.0.0.0', '2026-08-09 11:11:20'),
(61, 'tôi muốn đặt 3 phần 7Up Lon', 'Đã thêm 3 phần 7Up Lon vào giỏ hàng.\nĐơn giá: 20.000đ | Tạm tính: 60.000đ.\nGiỏ hàng hiện có 3 món, tổng tạm tính 60.000đ. Bạn có thể mở Giỏ hàng để kiểm tra và thanh toán.', 'dat mon', 'api_cart_add', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/151.0.0.0 Safari/537.36 Edg/151.0.0.0', '2026-08-09 11:23:21'),
(62, 'còn món gì khác ngon hấp dẫn hơn không', 'Chào bạn! Tại FastFood, chúng tôi có rất nhiều món ăn hấp dẫn đang chờ bạn thưởng thức. Dưới đây là 3 gợi ý nổi bật:\n\n1. **Pizza New York Bò** - Giá: 225.000đ (Còn: 50 phần)\n2. **Salad gà giòn tươi mát** - Giá: 45.000đ (Còn: 50 phần)\n3. **Gà rán giòn 5 miếng** - Giá: 25.000đ (Còn: 50 phần)\n\nBạn có muốn xem thêm thực đơn chi tiết hoặc đặt món nào không?', 'gemini', 'ai_gemini', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/151.0.0.0 Safari/537.36 Edg/151.0.0.0', '2026-08-09 11:23:39'),
(63, 'Pizza New York Bò giá bao nhiêu', 'Pizza New York Bò có giá 225.000đ, hiện còn 50 phần.', 'gia', 'api_price', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/151.0.0.0 Safari/537.36 Edg/151.0.0.0', '2026-08-09 11:23:55'),
(64, 'cho tôi 10 phần', 'Đã thêm 10 phần Phần ăn B vào giỏ hàng.\nĐơn giá: 250.000đ | Tạm tính: 2.500.000đ.\nGiỏ hàng hiện có 13 món, tổng tạm tính 2.560.000đ. Bạn có thể mở Giỏ hàng để kiểm tra và thanh toán.', 'dat mon', 'api_cart_add', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/151.0.0.0 Safari/537.36 Edg/151.0.0.0', '2026-08-09 11:24:02'),
(65, 'món ngon nhất hôm nay là gì', 'Chào bạn! Tại FastFood, các món \"siêu hot\" và được yêu thích nhất hôm nay gồm có:\n\n1. **Salad gà giòn tươi mát** – Giá: 45.000đ (Còn 50 phần)\n2. **Cơm trắng** – Giá: 25.000đ (Còn 50 phần)\n3. **Combo Gà Rán Giòn Đặc Biệt** – Giá: 99.000đ (Còn 50 phần)\n\nBạn có muốn đặt món nào không ạ?', 'gemini', 'ai_gemini', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/151.0.0.0 Safari/537.36 Edg/151.0.0.0', '2026-08-11 03:25:56'),
(66, 'món best seller hiện tại cửa hàng là gì vậy', 'Chào bạn! Tại cửa hàng FastFood, chúng tôi có các món siêu hot rất được yêu thích:\n\n1. **Salad gà giòn tươi mát** - Giá: 45.000đ (Còn: 50 phần)\n2. **Cơm trắng** - Giá: 25.000đ (Còn: 50 phần)\n\nBạn có muốn đặt ngay một trong các món trên không ạ?', 'gemini', 'ai_gemini', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/151.0.0.0 Safari/537.36 Edg/151.0.0.0', '2026-08-11 03:26:27'),
(67, 'có món gì khác k', 'Dạ, cửa hàng FastFood của chúng tôi còn rất nhiều món hấp dẫn khác nữa nhé! Bạn có thể tham khảo một số món nổi bật sau:\n\n1. **Combo Gà Rán Giòn Đặc Biệt** - Giá: 99.000đ (Còn 50 phần)\n2. **Pizza New York Bò** - Giá: 225.000đ (Còn 50 phần)\n3. **Salad gà giòn tươi mát** - Giá: 45.000đ (Còn 50 phần)\n\nBạn muốn xem thêm thực đơn chi tiết về món nước, đồ ăn nhẹ hay pizza nào khác không ạ?', 'gemini', 'ai_gemini', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/151.0.0.0 Safari/537.36 Edg/151.0.0.0', '2026-08-11 03:26:39'),
(68, 'cho tôi 20 lon pepsi', 'Đã thêm 10 phần Pepsi vào giỏ hàng.\nĐơn giá: 25.000đ | Tạm tính: 250.000đ.\nGiỏ hàng hiện có 10 món, tổng tạm tính 250.000đ. Bạn có thể mở Giỏ hàng để kiểm tra và thanh toán.', 'dat mon', 'api_cart_add', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/151.0.0.0 Safari/537.36 Edg/151.0.0.0', '2026-08-11 03:27:00'),
(69, 'tôi đặt 20 sao cho 10', 'Tôi chưa hiểu rõ ý bạn. Bạn có thể hỏi: \"Thực đơn có gì?\", \"Giá Pepsi bao nhiêu?\", \"Còn gà rán không?\", hoặc \"Tôi muốn đặt 2 phần 7Up Lon\".', '', 'fallback', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/151.0.0.0 Safari/537.36 Edg/151.0.0.0', '2026-08-11 03:27:57'),
(70, 'nay có món gì mới nhập k vậy', 'Chào bạn! Cửa hàng FastFood hiện có rất nhiều món ngon hấp dẫn và các món siêu hot đang chờ bạn thưởng thức. Bạn có thể thử 3 món nổi bật sau:\n\n1. **🍗 Combo Gà Rán Giòn Đặc Biệt** - Giá: 99.000đ (Còn: 50 phần)\n2. **Salad gà giòn tươi mát** - Giá: 45.000đ (Còn: 50 phần)\n3. **Pizza New York Bò** - Giá: 225.000đ (Còn: 50 phần)\n\nBạn muốn đặt món nào để mình hỗ trợ đưa vào giỏ hàng nhé?', 'gemini', 'ai_gemini', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/151.0.0.0 Safari/537.36 Edg/151.0.0.0', '2026-08-11 03:45:48'),
(71, 'hôm nay có món gì mới ngon hấp dẫn', 'Chào bạn! Hôm nay FastFood có các món siêu hot và hấp dẫn sau đây để bạn thưởng thức:\n\n1. **Salad gà giòn tươi mát** - Giá: 45.000đ (Còn 50 phần): Rau xanh tươi mát kết hợp gà giòn và hạt dinh dưỡng.\n2. **Cơm trắng** - Giá: 25.000đ (Còn 50 phần): Cơm trắng nóng dẻo, dùng kèm các món chính.\n3. **Combo Gà Rán Giòn Đặc Biệt** - Giá: 99.000đ (Còn 50 phần): Gà rán giòn rụm kèm khoai tây chiên, snack và sốt chấm đậm vị.\n\nBạn muốn chọn món nào để tôi hướng dẫn đặt món luôn nhé?', 'gemini', 'ai_gemini', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/151.0.0.0 Safari/537.36 Edg/151.0.0.0', '2026-08-11 03:50:10'),
(72, 'nay có món mới k', 'Các món mới nhập đang còn hàng:\n1. Pizza Phô Mai Cà Chua - 150.000đ, còn 30 phần\n2. Pizza Bò Thượng Hạng - 129.000đ, còn 20 phần\n3. Mì Cay Bò Hàn Quốc - 75.000đ, còn 2 phần\n4. Mì Ý Sốt Bò Bằm Đặc Biệt - 89.000đ, còn 23 phần\n5. 🔥🍗 Đại Tiệc Gà Rán Siêu Giòn - 129.000đ, còn 23 phần\nBạn có thể nói \"đặt 2 phần tên món\" để mình thêm nhanh vào giỏ hàng.', 'mon moi', 'api_products', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/151.0.0.0 Safari/537.36 Edg/151.0.0.0', '2026-08-11 03:50:39'),
(73, 'cho tôi 32 dĩa Pizza Phô Mai Cà Chua', 'Pizza Phô Mai Cà Chua hiện chỉ còn 30 phần. Bạn có thể giảm số lượng hoặc chọn thêm món khác nhé.', 'ton kho', 'api_stock', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/151.0.0.0 Safari/537.36 Edg/151.0.0.0', '2026-08-11 03:50:58'),
(74, 'vạy cho 28 đi', 'Chào bạn, FastFood AI chưa hiểu rõ yêu cầu \"cho 28 đi\" của bạn. \n\nBạn có muốn đặt món ăn hay xem thực đơn không? Bạn có thể tham khảo một số món nổi bật sau:\n- 🍗 **Combo Gà Rán Giòn Đặc Biệt**: 99.000đ (Còn 50 phần)\n- 🍕 **Pizza New York Bò**: 225.000đ (Còn 50 phần)\n- 🥗 **Salad gà giòn tươi mát**: 45.000đ (Còn 50 phần)\n\nHãy cho mình biết bạn muốn dùng món gì nhé!', 'gemini', 'ai_gemini', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/151.0.0.0 Safari/537.36 Edg/151.0.0.0', '2026-08-11 03:51:07'),
(75, 'Pizza Phô Mai Cà Chua cho 28 phần', 'Xin chào! Rất tiếc hiện tại cửa hàng FastFood không có món \"Pizza Phô Mai Cà Chua\" trong thực đơn. \n\nTuy nhiên, bạn có thể tham khảo các món pizza hấp dẫn khác đang có sẵn như:\n1. **Pizza Ngập Vị Phô Mai** - Giá: 255.000đ (Còn: 50 phần)\n2. **Pizza New York Bò** - Giá: 225.000đ (Còn: 50 phần)\n3. **Pizza Hải Sản Xốt** - Giá: 255.000đ (Còn: 50 phần)\n\nBạn có muốn chọn món nào trong số này không ạ?', 'gemini', 'ai_gemini', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/151.0.0.0 Safari/537.36 Edg/151.0.0.0', '2026-08-11 03:51:29'),
(76, 'món gì mới k', 'Tôi gợi ý bạn chọn:\n1. Aquafina - 15.000đ, còn 100 phần\n2. Pepsi Lon - 20.000đ, còn 50 phần\n3. 7Up Lon - 20.000đ, còn 50 phần\nBạn có thể nói \"đặt 2 phần tên món\" để thêm nhanh vào giỏ hàng.', 'goi y', 'api_products', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/151.0.0.0 Safari/537.36 Edg/151.0.0.0', '2026-08-11 04:02:19'),
(77, 'nước gì ngon', 'Danh mục Nước uống hiện có:\n1. Pepsi - 25.000đ, còn 50 phần\n2. Aquafina - 15.000đ, còn 100 phần\n3. Trà Đào - 25.000đ, còn 50 phần\n4. Pepsi Lon - 20.000đ, còn 50 phần\n5. 7Up Lon - 20.000đ, còn 50 phần\nBạn có thể hỏi giá, tồn kho hoặc nói \"đặt 2 phần tên món\".', 'nuoc uong', 'api_products', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/151.0.0.0 Safari/537.36 Edg/151.0.0.0', '2026-08-11 04:02:30'),
(78, 'cho tôi 48 phần pepsi', 'Đã thêm 48 phần Pepsi vào giỏ hàng.\nĐơn giá: 25.000đ | Tạm tính: 1.200.000đ.\nGiỏ hàng hiện có 48 món, tổng tạm tính 1.200.000đ. Bạn có thể mở Giỏ hàng để kiểm tra và thanh toán.', 'dat mon', 'api_cart_add', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/151.0.0.0 Safari/537.36 Edg/151.0.0.0', '2026-08-11 04:02:54'),
(79, 'có tin gì vui k', 'Tôi chưa hiểu rõ ý bạn. Bạn có thể hỏi: \"Thực đơn có gì?\", \"Giá Pepsi bao nhiêu?\", \"Còn gà rán không?\", hoặc \"Tôi muốn đặt 2 phần 7Up Lon\".', '', 'fallback', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/151.0.0.0 Safari/537.36 Edg/151.0.0.0', '2026-08-11 04:04:48'),
(80, 'cơm trắng còn bao nhiêu', 'Danh mục Thức Ăn Nhẹ hiện có:\n1. Gà rán giòn 5 miếng - 25.000đ, còn 50 phần\n2. 5 - Pumcheese - 204.000đ, còn 50 phần\nBạn có thể hỏi giá, tồn kho hoặc nói \"đặt 2 phần tên món\".', 'thuc an nhe', 'api_products', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/151.0.0.0 Safari/537.36 Edg/151.0.0.0', '2026-08-11 04:05:23'),
(81, 'cơm trắng có không', 'Danh mục Thức Ăn Nhẹ hiện có:\n1. Gà rán giòn 5 miếng - 25.000đ, còn 50 phần\n2. 5 - Pumcheese - 204.000đ, còn 50 phần\nBạn có thể hỏi giá, tồn kho hoặc nói \"đặt 2 phần tên món\".', 'thuc an nhe', 'api_products', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/151.0.0.0 Safari/537.36 Edg/151.0.0.0', '2026-08-11 04:05:36'),
(82, 'Salad gà giòn tươi mát', 'Salad gà giòn tươi mát thuộc danh mục Món siêu hot.\nGiá: 45.000đ, hiện còn 50 phần.\nMô tả: Món salad gà giòn kết hợp rau xanh tươi mát, cà chua và hạt dinh dưỡng, mang đến hương vị thanh nhẹ nhưng vẫn đủ no.\nBạn có thể hỏi giá, tồn kho hoặc nói \"đặt 2 phần Salad gà giòn tươi mát\" để mình thêm vào giỏ hàng.', 'salad ga gion tuoi mat', 'api_products', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/151.0.0.0 Safari/537.36 Edg/151.0.0.0', '2026-08-11 04:16:51'),
(83, 'cho tôi 10 phần', 'Đã thêm 10 phần Salad gà giòn tươi mát vào giỏ hàng.\nĐơn giá: 45.000đ | Tạm tính: 450.000đ.\nGiỏ hàng hiện có 10 món, tổng tạm tính 450.000đ. Bạn có thể mở Giỏ hàng để kiểm tra và thanh toán.', 'dat mon', 'api_cart_add', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/151.0.0.0 Safari/537.36 Edg/151.0.0.0', '2026-08-11 04:16:58'),
(84, 'combo nay có gì', 'Danh mục Combo hiện có:\n1. 🔥🍗 Đại Tiệc Gà Rán Siêu Giòn - 129.000đ, còn 23 phần\n2. 🧀✨ Viên Phô Mai Chiên Giòn - 59.000đ, còn 45 phần\n3. 🧧🍗 Bucket Gà Rán Tài Lộc - 199.000đ, còn 30 phần\n4. 🔥🍗 Gà Rán Hàn Quốc Bùng Vị - 89.000đ, còn 30 phần\n5. 🍗 Combo Gà Rán Giòn Đặc Biệt - 99.000đ, còn 50 phần\n6. Pizza Hải Sản - 120.000đ, còn 30 phần\nBạn có thể hỏi giá, tồn kho hoặc nói \"đặt 2 phần tên món\".', 'combo', 'api_products', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/151.0.0.0 Safari/537.36 Edg/151.0.0.0', '2026-08-11 04:17:08'),
(85, 'còn gì khác k', 'Dạ, bên em còn rất nhiều món ngon hấp dẫn khác trong thực đơn như:\n\n1. **🍗 Combo Gà Rán Giòn Đặc Biệt** - Giá: 99.000đ (Còn: 50 phần)\n2. **🍕 Pizza New York Bò** - Giá: 225.000đ (Còn: 50 phần)\n3. **🥗 Salad gà giòn tươi mát** - Giá: 45.000đ (Còn: 50 phần)\n\nBạn có muốn tham khảo thêm về các món pizza, nước uống hay chương trình khuyến mãi giảm đến 50% của cửa hàng không ạ?', 'gemini', 'ai_gemini', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/151.0.0.0 Safari/537.36 Edg/151.0.0.0', '2026-08-11 04:17:31'),
(86, 'Có món Cháo không', 'Xin chào! Tôi là FastFood AI. Hôm nay bạn muốn chọn món, xem giá hay hỏi khuyến mãi nào?', 'chao', 'static', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/151.0.0.0 Safari/537.36 Edg/151.0.0.0', '2026-08-11 04:44:22'),
(87, 'cháo', 'Xin chào! Tôi là FastFood AI. Hôm nay bạn muốn chọn món, xem giá hay hỏi khuyến mãi nào?', 'chao', 'static', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/151.0.0.0 Safari/537.36 Edg/151.0.0.0', '2026-08-11 04:44:29'),
(88, 'Tôi muốn món nhẹ, rẻ, còn hàng thì nên chọn gì?', 'Đã thêm 1 phần Pizza Bò Thượng Hạng vào giỏ hàng.\nĐơn giá: 129.000đ | Tạm tính: 129.000đ.\nGiỏ hàng hiện có 1 món, tổng tạm tính 129.000đ. Bạn có thể mở Giỏ hàng để kiểm tra và thanh toán.', 'dat mon', 'api_cart_add', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/151.0.0.0 Safari/537.36 Edg/151.0.0.0', '2026-08-11 04:44:41'),
(89, 'Khuyến mãi hiện tại', '🧀✨ Viên Phô Mai Chiên Giòn thuộc danh mục Combo.\nGiá: 59.000đ, hiện còn 45 phần.\nMô tả: 🧀 Vỏ ngoài vàng giòn, bên trong béo thơm và mềm mịn. Cắn một miếng là cảm nhận ngay vị phô mai đậm đà, nóng hổi và cực kỳ cuốn! 😋\nBạn có thể hỏi giá, tồn kho hoặc nói \"đặt 2 phần 🧀✨ Viên Phô Mai Chiên Giòn\" để mình thêm vào giỏ hàng.', 'vien pho mai chien gion', 'api_products', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/151.0.0.0 Safari/537.36 Edg/151.0.0.0', '2026-08-11 04:44:51'),
(90, 'Địa chỉ cửa hàng', 'Cửa hàng tại Quận 7, Thành phố Hồ Chí Minh. Hotline: 1900 6099.', 'dia chi', 'static', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/151.0.0.0 Safari/537.36 Edg/151.0.0.0', '2026-08-11 04:44:56'),
(91, 'Thực đơn có gì?', 'Danh mục Thức Ăn Nhẹ hiện có:\n1. Gà rán giòn 5 miếng - 25.000đ, còn 50 phần\n2. 5 - Pumcheese - 204.000đ, còn 50 phần\nBạn có thể hỏi giá, tồn kho hoặc nói \"đặt 2 phần tên món\".', 'thuc an nhe', 'api_products', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/151.0.0.0 Safari/537.36 Edg/151.0.0.0', '2026-08-11 04:45:00'),
(92, '7Up', '7Up Lon thuộc danh mục Nước uống.\nGiá: 20.000đ, hiện còn 50 phần.\nMô tả: 7Up Lon\nBạn có thể hỏi giá, tồn kho hoặc nói \"đặt 2 phần 7Up Lon\" để mình thêm vào giỏ hàng.', '7up lon', 'api_products', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/151.0.0.0 Safari/537.36 Edg/151.0.0.0', '2026-08-11 04:45:15'),
(93, '7Up Lon', '7Up Lon thuộc danh mục Nước uống.\nGiá: 12.589.333đ, hiện còn 50 phần.\nMô tả: 7Up Lon\nBạn có thể hỏi giá, tồn kho hoặc nói \"đặt 2 phần 7Up Lon\" để mình thêm vào giỏ hàng.', '7up lon', 'api_products', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/151.0.0.0 Safari/537.36 Edg/151.0.0.0', '2026-08-11 04:46:25'),
(94, 'cho toi 20 phần', 'Đã thêm 20 phần 7Up Lon vào giỏ hàng.\nĐơn giá: 12.589.333đ | Tạm tính: 251.786.660đ.\nGiỏ hàng hiện có 21 món, tổng tạm tính 251.915.660đ. Bạn có thể mở Giỏ hàng để kiểm tra và thanh toán.', 'dat mon', 'api_cart_add', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/151.0.0.0 Safari/537.36 Edg/151.0.0.0', '2026-08-11 04:46:34'),
(95, 'thêm 25 phần đi', 'Đã thêm 25 phần 7Up Lon vào giỏ hàng.\nĐơn giá: 12.589.333đ | Tạm tính: 314.733.325đ.\nGiỏ hàng hiện có 46 món, tổng tạm tính 566.648.985đ. Bạn có thể mở Giỏ hàng để kiểm tra và thanh toán.', 'dat mon', 'api_cart_add', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/151.0.0.0 Safari/537.36 Edg/151.0.0.0', '2026-08-11 04:46:52'),
(96, 'Ưu Đãi Burger Đặc Biệt – Giảm Đến 50% Hôm Nay!', 'Mì Ý Sốt Bò Bằm Đặc Biệt thuộc danh mục Pizza - Mì Ý.\nGiá: 89.000đ, hiện còn 23 phần.\nMô tả: 🍝 Mì Ý sốt bò bằm đậm đà, sợi mì mềm dai hòa quyện cùng sốt cà chua thơm ngon, thịt bò bằm và rau thơm tươi 🌿🍅. Một lựa chọn hấp dẫn cho tín đồ món Ý!\nBạn có thể hỏi giá, tồn kho hoặc nói \"đặt 2 phần Mì Ý Sốt Bò Bằm Đặc Biệt\" để mình thêm vào giỏ hàng.', 'mi y sot bo bam dac biet', 'api_products', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/151.0.0.0 Safari/537.36 Edg/151.0.0.0', '2026-08-11 05:10:16'),
(97, 'Pepsi Lon', 'Pepsi Lon thuộc danh mục Nước uống.\nGiá: 20.000đ, hiện còn 50 phần.\nMô tả: Pepsi Lon\nBạn có thể hỏi giá, tồn kho hoặc nói \"đặt 2 phần Pepsi Lon\" để mình thêm vào giỏ hàng.', 'pepsi lon', 'api_products', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/151.0.0.0 Safari/537.36 Edg/151.0.0.0', '2026-08-11 05:10:40'),
(98, 'cho tôi 49 phần', 'Đã thêm 49 phần Pepsi Lon vào giỏ hàng.\nĐơn giá: 20.000đ | Tạm tính: 980.000đ.\nGiỏ hàng hiện có 49 món, tổng tạm tính 980.000đ. Bạn có thể mở Giỏ hàng để kiểm tra và thanh toán.', 'dat mon', 'api_cart_add', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/151.0.0.0 Safari/537.36 Edg/151.0.0.0', '2026-08-11 05:10:49'),
(99, 'món mới hôm nay là gì', 'Các món mới nhập đang còn hàng:\n1. Sữa Gạo Horchata Wana 250ml - 250.000đ, còn 1000 phần\n2. Gà Rán Truyền Thống - 23.500đ, còn 56 phần\n3. Gà Rán Giòn Cay - 125.300đ, còn 11 phần\n4. Bánh Tart Trứng Nướng - 36.000đ, còn 23 phần\n5. Phô Mai Viên Chiên Xù - 36.000đ, còn 25 phần\nBạn có thể nói \"đặt 2 phần tên món\" để mình thêm nhanh vào giỏ hàng.', 'mon moi', 'api_products', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/151.0.0.0 Safari/537.36 Edg/151.0.0.0', '2026-08-12 01:05:05');

-- --------------------------------------------------------

--
-- Cấu trúc bảng cho bảng `tbl_chitietdonhang`
--

CREATE TABLE `tbl_chitietdonhang` (
  `id` int(11) NOT NULL,
  `id_donhang` int(11) NOT NULL,
  `id_sanpham` int(11) DEFAULT NULL,
  `ten_sanpham` varchar(200) NOT NULL,
  `gia` decimal(10,2) NOT NULL,
  `soluong` int(11) NOT NULL,
  `thanhtien` decimal(15,2) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Đang đổ dữ liệu cho bảng `tbl_chitietdonhang`
--

INSERT INTO `tbl_chitietdonhang` (`id`, `id_donhang`, `id_sanpham`, `ten_sanpham`, `gia`, `soluong`, `thanhtien`) VALUES
(36, 19, 39, 'Salad gà giòn tươi mát', 45000.00, 1, 45000.00),
(37, 19, 37, 'Pepsi', 25000.00, 1, 25000.00),
(38, 19, 32, 'Pizza Hải Sản', 120000.00, 1, 120000.00),
(39, 20, 39, 'Salad gà giòn tươi mát', 45000.00, 1, 45000.00),
(40, 20, 37, 'Pepsi', 25000.00, 7, 175000.00),
(41, 20, 22, 'Mì Ý Gà Viên', 25000.00, 1, 25000.00),
(42, 21, 37, 'Pepsi', 25000.00, 2, 50000.00),
(43, 22, 37, 'Pepsi', 25000.00, 1, 25000.00),
(44, 23, 37, 'Pepsi', 25000.00, 1, 25000.00),
(45, 24, 39, 'Salad gà giòn tươi mát', 45000.00, 1, 45000.00),
(46, 25, 18, 'ChoCoA', 225000.00, 1, 225000.00),
(47, 26, 10, '5 Gà Miếng Nuggets', 200000.00, 1, 200000.00),
(48, 27, 8, '4 - Cherrow', 25000.00, 1, 25000.00),
(71, 40, 37, 'Pepsi', 25000.00, 48, 1200000.00),
(75, 42, 15, 'Pepsi Lon', 20000.00, 49, 980000.00),
(79, 45, 51, 'Bánh Tart Trứng Nướng', 36000.00, 1, 36000.00),
(80, 46, 51, 'Bánh Tart Trứng Nướng', 36000.00, 1, 36000.00),
(81, 47, 43, '🧧🍗 Bucket Gà Rán Tài Lộc', 199000.00, 10, 1990000.00),
(82, 47, 20, 'Gà rán giòn 5 miếng', 25000.00, 20, 500000.00),
(86, 52, 58, 'Trà Lipton Đá Mát Lạnh', 23000.00, 25, 575000.00),
(87, 52, 57, 'Nước Ép Cam Wana 330ml', 23000.00, 25, 575000.00),
(88, 53, 56, 'Nước Khoáng Vĩnh Hảo', 10000.00, 1, 10000.00);

-- --------------------------------------------------------

--
-- Cấu trúc bảng cho bảng `tbl_danhmuc`
--

CREATE TABLE `tbl_danhmuc` (
  `id_danhmuc` int(11) NOT NULL,
  `tendanhmuc` varchar(100) NOT NULL,
  `thutu` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Đang đổ dữ liệu cho bảng `tbl_danhmuc`
--

INSERT INTO `tbl_danhmuc` (`id_danhmuc`, `tendanhmuc`, `thutu`) VALUES
(1, 'Pizza - Mì Ý', 0),
(2, 'Combo', 0),
(22, 'Món siêu hot', 3),
(25, 'Gà Siêu Giòn Tan', 4),
(26, 'Nước uống', 1);

-- --------------------------------------------------------

--
-- Cấu trúc bảng cho bảng `tbl_danhmucbaiviet`
--

CREATE TABLE `tbl_danhmucbaiviet` (
  `id_baiviet` int(11) NOT NULL,
  `tendanhmucbv` varchar(200) NOT NULL,
  `thutu` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Đang đổ dữ liệu cho bảng `tbl_danhmucbaiviet`
--

INSERT INTO `tbl_danhmucbaiviet` (`id_baiviet`, `tendanhmucbv`, `thutu`) VALUES
(10, 'Tin tức khuyến mãi', 0),
(12, 'Tin tức mới ', 0),
(16, 'Tin ăn chơi', 3),
(17, 'Tin sale 100%', 4);

-- --------------------------------------------------------

--
-- Cấu trúc bảng cho bảng `tbl_donhang`
--

CREATE TABLE `tbl_donhang` (
  `id` int(11) NOT NULL,
  `madon` varchar(50) NOT NULL,
  `tenkhach` varchar(200) DEFAULT NULL,
  `sdt` varchar(20) DEFAULT NULL,
  `tongtien` decimal(15,2) DEFAULT 0.00,
  `phiship` decimal(10,2) DEFAULT 0.00,
  `ngaydat` datetime DEFAULT current_timestamp(),
  `trangthai` int(11) DEFAULT 0,
  `admin_seen` tinyint(1) NOT NULL DEFAULT 1,
  `email` varchar(100) DEFAULT NULL,
  `diachi` text DEFAULT NULL,
  `phuongthuc` varchar(50) DEFAULT NULL,
  `ghichu` text DEFAULT NULL,
  `session_id` varchar(50) DEFAULT NULL,
  `start_time` datetime DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Đang đổ dữ liệu cho bảng `tbl_donhang`
--

INSERT INTO `tbl_donhang` (`id`, `madon`, `tenkhach`, `sdt`, `tongtien`, `phiship`, `ngaydat`, `trangthai`, `admin_seen`, `email`, `diachi`, `phuongthuc`, `ghichu`, `session_id`, `start_time`) VALUES
(40, 'FF20260811060214194', 'Khách Kiosk', NULL, 2375000.00, 0.00, '2026-08-11 11:03:29', 1, 1, NULL, NULL, 'transfer', NULL, NULL, NULL),
(42, 'FF20260811070439412', 'Khách Kiosk', NULL, 980000.00, 0.00, '2026-08-11 12:10:58', 1, 1, NULL, NULL, 'transfer', NULL, NULL, NULL),
(45, 'FF20260811153247449', 'Khách Kiosk', NULL, 36000.00, 0.00, '2026-08-11 15:33:01', 1, 1, NULL, NULL, 'transfer', NULL, NULL, NULL),
(46, 'FF20260811153309972', 'Khách Kiosk', NULL, 36000.00, 0.00, '2026-08-11 15:33:17', 1, 1, NULL, NULL, 'cash', NULL, NULL, NULL),
(47, 'FF20260811154746301', 'Khách Kiosk', NULL, 2490000.00, 0.00, '2026-08-11 15:48:19', 1, 1, NULL, NULL, 'cash', NULL, NULL, NULL),
(52, 'FF20260812081013688', 'Khách Kiosk', NULL, 1150000.00, 0.00, '2026-08-12 08:13:59', 1, 1, NULL, NULL, 'transfer', NULL, NULL, NULL),
(53, 'FF20260812081437895', 'Khách Kiosk', NULL, 10000.00, 0.00, '2026-08-12 08:15:20', 1, 1, NULL, NULL, 'cash', NULL, NULL, NULL);

-- --------------------------------------------------------

--
-- Cấu trúc bảng cho bảng `tbl_gioithieu`
--

CREATE TABLE `tbl_gioithieu` (
  `id` int(11) NOT NULL,
  `noidung` text DEFAULT NULL,
  `hinhanh` varchar(255) DEFAULT NULL,
  `ngaycapnhat` datetime DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Đang đổ dữ liệu cho bảng `tbl_gioithieu`
--

INSERT INTO `tbl_gioithieu` (`id`, `noidung`, `hinhanh`, `ngaycapnhat`) VALUES
(1, 'Nhà hàng FastFood là chuỗi thức ăn nhanh hàng đầu tại Thành phố Hồ Chí Minh. Chúng tôi tự hào mang đến cho khách hàng những món ăn ngon, chất lượng với giá cả hợp lý.\r\n\r\nVới hơn 10 năm kinh nghiệm trong ngành ẩm thực, chúng tôi đã phục vụ hàng triệu khách hàng và nhận được nhiều phản hồi tích cực. Cam kết của chúng tôi là luôn đặt chất lượng món ăn và sự hài lòng của khách hàng lên hàng đầu.', 'site/20260812074815-a78b75f9-ae799bb53c2e013acc31092d7c78724a_1699919482.jpg', '2026-08-12 07:48:15');

-- --------------------------------------------------------

--
-- Cấu trúc bảng cho bảng `tbl_goi_nhanvien`
--

CREATE TABLE `tbl_goi_nhanvien` (
  `id_goi` int(11) NOT NULL,
  `ma_goi` varchar(32) NOT NULL,
  `session_id` varchar(128) NOT NULL,
  `ghi_chu` varchar(255) DEFAULT NULL,
  `ip_address` varchar(45) DEFAULT NULL,
  `trangthai` tinyint(1) NOT NULL DEFAULT 0,
  `ngaygoi` datetime NOT NULL DEFAULT current_timestamp(),
  `ngayxuly` datetime DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Đang đổ dữ liệu cho bảng `tbl_goi_nhanvien`
--

INSERT INTO `tbl_goi_nhanvien` (`id_goi`, `ma_goi`, `session_id`, `ghi_chu`, `ip_address`, `trangthai`, `ngaygoi`, `ngayxuly`) VALUES
(10, 'NV20260811150827202', 'f72esbcef8ijufcpsukutvrrr5', '', '::1', 1, '2026-08-11 15:08:27', '2026-08-11 15:09:58'),
(11, 'NV20260811154929500', 'f72esbcef8ijufcpsukutvrrr5', '', '::1', 1, '2026-08-11 15:49:29', '2026-08-12 08:14:05'),
(13, 'NV20260812081116150', 'nim9dc2ccprcmi31k5siljl30a', '', '::1', 1, '2026-08-12 08:11:16', '2026-08-12 08:14:04');

-- --------------------------------------------------------

--
-- Cấu trúc bảng cho bảng `tbl_lienhe`
--

CREATE TABLE `tbl_lienhe` (
  `id_lienhe` int(11) NOT NULL,
  `thongtinlienhe` text NOT NULL,
  `hinhanh` varchar(100) NOT NULL,
  `ngaygui` datetime DEFAULT current_timestamp(),
  `trangthai` varchar(20) DEFAULT 'chua_xem',
  `ten` varchar(100) DEFAULT NULL,
  `email` varchar(100) DEFAULT NULL,
  `sodienthoai` varchar(20) DEFAULT NULL,
  `loai` varchar(50) DEFAULT NULL,
  `noidung` text DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Đang đổ dữ liệu cho bảng `tbl_lienhe`
--

INSERT INTO `tbl_lienhe` (`id_lienhe`, `thongtinlienhe`, `hinhanh`, `ngaygui`, `trangthai`, `ten`, `email`, `sodienthoai`, `loai`, `noidung`) VALUES
(22, 'Huữ Văn Đạt | banner@gmail.com | 0231456821 | hop_tac', '', '2026-08-12 08:12:14', 'da_xem', 'Huữ Văn Đạt', 'banner@gmail.com', '0231456821', 'hop_tac', 'Dự án sắp tới sắp tuyệt chủng rồi, hoan hô @@@');

-- --------------------------------------------------------

--
-- Cấu trúc bảng cho bảng `tbl_sanpham`
--

CREATE TABLE `tbl_sanpham` (
  `id_sanpham` int(11) NOT NULL,
  `tensanpham` varchar(200) NOT NULL,
  `masp` varchar(200) NOT NULL,
  `giasp` varchar(200) NOT NULL,
  `soluong` int(11) NOT NULL,
  `tomtat` text NOT NULL,
  `noidung` text NOT NULL,
  `hinhanh` varchar(200) NOT NULL,
  `thutu` int(11) NOT NULL,
  `id_danhmuc` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Đang đổ dữ liệu cho bảng `tbl_sanpham`
--

INSERT INTO `tbl_sanpham` (`id_sanpham`, `tensanpham`, `masp`, `giasp`, `soluong`, `tomtat`, `noidung`, `hinhanh`, `thutu`, `id_danhmuc`) VALUES
(11, '5 - Pumcheese', '10', '20400', 50, '5 - Pumcheese', '', '5-Pumcheese.jpg', 0, 25),
(12, 'Phần ăn A', '11', '250000', 50, 'Phần ăn tiết kiệm cho bữa ăn nhanh đầy đủ.', 'Phần ăn A gồm nhiều món ăn nhanh được phối hợp sẵn, phù hợp khi đi theo nhóm.', '1715133992_CBO-A_HD.jpg', 0, 2),
(13, 'Phần ăn B', '12', '250000', 50, 'Phần ăn tiết kiệm cho nhóm bạn hoặc gia đình.', 'Phần ăn B gồm nhiều món ăn nhanh được phối hợp sẵn, tiện lợi cho nhóm.', 'CBO-B_HD.jpg', 0, 2),
(14, '7Up Lon', '13', '12585', 500, '7Up Lon', '', '1715134104_7UP_CAN.jpg', 0, 26),
(15, 'Pepsi Lon', '14', '20000', 93, 'Pepsi Lon', '', '1715134125_PEPSI_CAN.jpg', 0, 26),
(16, 'Pizza Ngập Vị Phô Mai', '15', '255000', 50, 'Pizza Ngập Vị Phô Mai Hảo Hạng - Cheesy Madness', '', 'CHEESY+MADNESS+NO+NEW+PC.jpg', 0, 1),
(17, 'Pizza Hải Sản Xốt', '16', '255000', 50, 'Pizza Hải Sản Xốt ', '', '1715134438_LIME+PESTO+-+ANH+SP+(2).png', 0, 1),
(18, 'ChoCoA', '17', '225000', 50, 'ChoCoA', '', '1715134560_ChoCoA.jpg', 0, 2),
(19, 'Pizza New York Bò', '18', '225000', 50, 'Pizza New York Bò ', '', '1715134612_Menu+BG+1.jpg', 0, 1),
(20, 'Gà rán giòn 5 miếng', '19', '25000', 30, 'Gà rán giòn rụm, dùng kèm xốt tùy chọn.', 'Gà rán 5 miếng, lớp vỏ giòn, thịt mềm và đậm vị.', '1715134684_5-TENDERS.jpg', 0, 25),
(21, 'Trà Đào', '20', '25000', 50, 'Trà Đào', '', '1715137276_Peach-Tea.jpg', 0, 26),
(22, 'Mì Ý Gà Viên', '21', '25000', 50, 'Mì Ý Gà Viên', '', '1715137333_MI-Y-GA-ZINGER.jpg', 0, 1),
(30, 'Aquafina', 'DRINK001', '15000', 100, 'Nước suối Aquafina tinh khiết.', 'Nước suối Aquafina tinh khiết, đóng chai tiện lợi.', '1714658200_AQUAFINA.jpg', 3, 26),
(31, 'Súp Rong Biển', 'BUR001', '45000', 50, 'Súp rong biển thanh nhẹ, dùng kèm bữa chính.', 'Súp rong biển thanh nhẹ, vị vừa ăn, phù hợp dùng kèm pizza và mì Ý.', '1777933524_Soup-Rong-Bien.jpg', 1, 1),
(32, 'Pizza Hải Sản', 'PIZ001', '120000', 30, 'Pizza hải sản tươi với phô mai béo ngậy và xốt đậm vị.', 'Pizza hải sản tươi, phủ phô mai và xốt đặc trưng, phù hợp cho bữa ăn nhóm.', '1777933507_Pizza+Extra+Topping+(4).jpg', 2, 2),
(37, 'Pepsi', '200', '25000', 200, 'Pepsi uống là suy', 'Pepsi', '1777933988_pepsi-zero.jpg', 0, 26),
(38, 'Cơm trắng', '56', '25000', 30000, 'Cơm trắng nóng dẻo, dùng kèm các món chính trong thực đơn.', 'Cơm trắng nóng dẻo, phù hợp dùng kèm gà rán, pizza và các món ăn nhanh khác.', '1777938034_Rice.jpg', 67, 22),
(39, 'Salad gà giòn tươi mát', 'SALAD001', '45000', 50, 'Món salad gà giòn kết hợp rau xanh tươi mát, cà chua và hạt dinh dưỡng, mang đến hương vị thanh nhẹ nhưng vẫn đủ no.', 'Salad gà giòn là sự kết hợp hoàn hảo giữa rau xanh tươi, cà chua, các loại hạt và những miếng gà chiên giòn hấp dẫn. Món ăn không chỉ ngon miệng mà còn cung cấp đầy đủ dinh dưỡng, phù hợp cho bữa ăn nhẹ hoặc người yêu thích phong cách ăn uống lành mạnh.\r\n\r\nPhần rau được chọn lọc kỹ lưỡng, giữ được độ tươi và giòn tự nhiên. Gà chiên có lớp vỏ giòn rụm bên ngoài, bên trong mềm và đậm vị. Khi kết hợp cùng sốt salad nhẹ, món ăn tạo nên sự cân bằng giữa vị béo, giòn và thanh mát.\r\n\r\nĐây là lựa chọn lý tưởng cho những ai muốn ăn ngon nhưng không quá ngấy, phù hợp cho cả bữa trưa nhanh hoặc bữa tối nhẹ nhàng.', '1778063836_SALAD-HAT-GA-VIEN.jpg', 69, 22),
(41, '🍗 Combo Gà Rán Giòn Đặc Biệt', 'CB-GARAN-001', '99000', 50, '🍗 Combo gà rán giòn rụm kèm khoai tây chiên, snack và sốt chấm đậm vị. Phù hợp cho 1–2 người.', '🔥 Combo Gà Rán Giòn Đặc Biệt gồm:\r\n🍗 Gà rán giòn vàng, lớp vỏ thơm và đậm vị.\r\n🍟 Khoai tây chiên giòn.\r\n🧀 Snack ăn kèm hấp dẫn.\r\n🥫 Sốt chấm đặc biệt.\r\n🚚 Hỗ trợ giao hàng tận nơi.\r\n💥 Phù hợp cho bữa ăn nhanh, ăn trưa hoặc tụ tập cuối tuần.', 'products/20260810104332-d0fd3819-4d0653aca03be80a0f63c25a34414d67.jpg', 1, 2),
(42, '🔥🍗 Gà Rán Hàn Quốc Bùng Vị', 'KFC-KR-002', '89000', 30, '🔥 Giòn rụm bên ngoài, mềm mọng bên trong, phủ lớp gia vị Hàn Quốc cay thơm cực cuốn. Một miếng là giòn, hai miếng là mê! 🇰🇷🍗', '🍗 GÀ RÁN HÀN QUỐC BÙNG VỊ – GIÒN RỤM, ĐẬM ĐÀ, ĂN LÀ GHIỀN!\r\n\r\n✨ Những miếng gà tươi được tẩm ướp đậm vị, chiên vàng nóng hổi với lớp vỏ giòn tan, bên trong vẫn giữ được độ mềm mọng, ngọt thịt.\r\n\r\n🌶️ Gia vị phong cách Hàn Quốc mang vị cay nhẹ, thơm nồng và đậm đà, càng ăn càng cuốn.\r\n\r\n🔥 Điểm hấp dẫn:\r\n🍗 Vỏ ngoài vàng giòn, cắn nghe “rộp rộp”\r\n💦 Thịt gà mềm mọng, không bị khô\r\n🌶️ Gia vị cay thơm chuẩn phong cách Hàn\r\n🥫 Cực hợp khi ăn cùng tương ớt hoặc sốt đặc biệt\r\n🍺 Hoàn hảo cho những buổi tụ tập cùng bạn bè\r\n\r\n💥 Gà vừa chiên nóng hổi – gọi ngay để thưởng thức lúc giòn ngon nhất!', 'products/20260810104504-34b2f7e9-pngtree-korean-specialty-food-beer-fried-chicken-p.png', 1, 25),
(43, '🧧🍗 Bucket Gà Rán Tài Lộc', 'BUCKET-TL-003', '199000', 20, '🧧 Gà rán vàng giòn đầy ụ, ăn vui hết cỡ – mở tiệc đầu năm thêm rộn ràng, thêm may mắn và thêm thật nhiều “tài lộc”! 🍗✨', '🎉 BUCKET GÀ RÁN TÀI LỘC – ĐẦU NĂM GIÒN RỤM, CẢ NĂM ĐẦY ẮP NIỀM VUI!\r\n\r\n🍗 Một bucket đầy những miếng gà rán vàng óng, lớp vỏ giòn rụm bên ngoài, thịt bên trong mềm mọng và đậm vị.\r\n\r\n🧧 Lấy cảm hứng từ không khí Tết rực rỡ, món ăn cực hợp để chia sẻ cùng gia đình và bạn bè trong những buổi tụ họp đầu năm.\r\n\r\n✨ Điểm hấp dẫn:\r\n🍗 Gà rán nóng giòn, thơm nức\r\n👨‍👩‍👧‍👦 Phần lớn, phù hợp ăn nhóm\r\n🍟 Kết hợp cực ngon cùng khoai tây chiên\r\n🥫 Thêm sốt chấm là càng cuốn\r\n🧧 Không khí Tết vui mắt, lên bàn là thấy “có lộc”\r\n🎊 Phù hợp cho tiệc nhỏ, tụ họp bạn bè hoặc gia đình\r\n\r\n💥 Gọi một bucket – chia nhau vị ngon, rước thêm tài lộc!', 'products/20260810110003-9c385456-poster-ga-ran-6.jpg', 3, 25),
(44, '🧀✨ Viên Phô Mai Chiên Giòn', 'CHEESE-BALL-004', '59000', 45, '🧀 Vỏ ngoài vàng giòn, bên trong béo thơm và mềm mịn. Cắn một miếng là cảm nhận ngay vị phô mai đậm đà, nóng hổi và cực kỳ cuốn! 😋', '🧀 VIÊN PHÔ MAI CHIÊN GIÒN – NHỎ XINH NHƯNG CỰC KỲ GÂY NGHIỆN!\r\n\r\n✨ Những viên phô mai được phủ lớp bột giòn vàng hấp dẫn, chiên nóng vừa tới để tạo nên phần vỏ giòn rụm nhưng phần nhân bên trong vẫn mềm béo, thơm ngậy.\r\n\r\n🔥 Điểm hấp dẫn:\r\n🧀 Nhân phô mai béo thơm, đậm vị\r\n✨ Lớp vỏ vàng giòn, ăn cực vui miệng\r\n😋 Thưởng thức ngon nhất khi còn nóng\r\n🥫 Kết hợp hoàn hảo cùng tương ớt, tương cà hoặc sốt mayonnaise\r\n🍝 Có thể dùng kèm mì Ý, khoai tây hoặc các món chính\r\n👫 Phù hợp ăn vặt, ăn nhẹ hoặc gọi chung cùng bạn bè\r\n\r\n💥 Giòn bên ngoài – béo mềm bên trong – càng ăn càng mê!', 'products/20260810111351-c635a0c0-17ba6747ba0793002bf51abd2605837f.jpg', 3, 2),
(45, '🔥🍗 Đại Tiệc Gà Rán Siêu Giòn', 'FRIED-CHICKEN-005', '129000', 23, '🍗 Gà rán nóng hổi với lớp vỏ vàng giòn rụm, thịt bên trong mềm mọng và đậm vị. Một phần đầy đặn, cực hợp cho những lúc thèm món ngon “ăn là mê”! 😋🔥', '🔥 ĐẠI TIỆC GÀ RÁN SIÊU GIÒN – CÀNG ĂN CÀNG CUỐN!\r\n\r\n🍗 Những miếng gà được tẩm ướp đậm đà, chiên vàng nóng hổi để tạo nên lớp vỏ giòn rụm hấp dẫn, trong khi phần thịt bên trong vẫn giữ được độ mềm, mọng và thơm ngon.\r\n\r\n✨ Điểm hấp dẫn:\r\n🍗 Nhiều miếng gà đầy đặn, ăn cực đã\r\n🔥 Lớp vỏ vàng giòn, thơm nức\r\n💦 Thịt gà mềm mọng, không bị khô\r\n🌶️ Gia vị đậm đà, càng ăn càng cuốn\r\n🥫 Ngon hơn khi dùng cùng tương ớt, tương cà hoặc sốt đặc biệt\r\n👨‍👩‍👧‍👦 Phù hợp ăn cùng bạn bè, gia đình hoặc gọi cho những buổi tụ tập\r\n\r\n💥 Gà nóng vừa ra lò – giòn tan từng miếng, ngon đến miếng cuối cùng!', 'products/20260810113418-831d809e-7eaad9869aa8e5079685205ab087fdbb.jpg', 4, 25),
(46, 'Mì Ý Sốt Bò Bằm Đặc Biệt', 'PASTA001', '89000', 23, '🍝 Mì Ý sốt bò bằm đậm đà, sợi mì mềm dai hòa quyện cùng sốt cà chua thơm ngon, thịt bò bằm và rau thơm tươi 🌿🍅. Một lựa chọn hấp dẫn cho tín đồ món Ý!', '🍝 Mì Ý Sốt Bò Bằm Đặc Biệt được chế biến từ sợi mì Ý mềm dai, phủ bên trên là lớp sốt bò bằm cà chua thơm ngon và đậm vị. 🍅🥩 Sốt được kết hợp cùng các loại gia vị và rau thơm 🌿, tạo nên hương vị hài hòa, hấp dẫn. ✨ Món ăn phù hợp cho bữa trưa, bữa tối hoặc những lúc bạn muốn thưởng thức một món Ý vừa ngon vừa no bụng. 😋❤️', 'products/20260811020700-250f629b-16fd0b163638817-63e9a303c2f6a.jpg', 3, 1),
(47, 'Mì Cay Bò Hàn Quốc', 'MCB001', '75000', 14, '🌶️🍜 Mì cay bò Hàn Quốc nóng hổi với nước dùng cay đậm đà 🔥, thịt bò mềm thơm 🥩, rau xanh 🥬 và sợi mì dai ngon. Càng ăn càng cuốn, cực hợp cho tín đồ mê cay!', '🌶️🔥 MÌ CAY BÒ HÀN QUỐC – THỎA SỨC ĐAM MÊ VỊ CAY! 🇰🇷🍜 \\n\\nMột tô mì nóng hổi với nước dùng cay đậm đà, hòa quyện cùng sợi mì vàng dai ngon 🍜. Phần topping hấp dẫn với thịt bò mềm thơm 🥩, rau xanh tươi 🥬 và các loại rau củ 🍅, mang đến hương vị vừa cay nồng vừa thơm ngon khó cưỡng. 😋\\n\\n🔥 Điểm nổi bật: Nước dùng cay đậm vị • Thịt bò hấp dẫn 🥩 • Mì dai ngon 🍜 • Topping đầy đặn 🥬 • Ăn nóng cực cuốn ❤️\\n\\n🌶️ Một tô mì cay nóng hổi đang chờ bạn – gọi món và thưởng thức ngay! 🛒🔥', 'products/20260811052126-92d021d0-7fed2aa5b71e323f9ed2cbad621d40c2.jpg', 34, 1),
(48, 'Pizza Bò Thượng Hạng', 'PZB001', '129000', 19, '🍕🥩 Pizza Bò Thượng Hạng với đế bánh vàng giòn, phủ phô mai béo ngậy 🧀, thịt bò thơm ngon cùng ớt chuông 🫑, nấm 🍄 và ô liu. Một chiếc pizza đầy đặn topping, thơm ngon khó cưỡng! 😋🔥', '🍕🔥 PIZZA BÒ THƯỢNG HẠNG – NGẬP TRÀN TOPPING! 🥩🧀\r\n\r\nThưởng thức chiếc pizza nóng hổi với đế bánh vàng giòn, bên trên phủ lớp phô mai béo ngậy 🧀 kết hợp cùng thịt bò thơm ngon 🥩 và nhiều loại topping hấp dẫn.\r\n\r\nSự kết hợp giữa ớt chuông 🫑, nấm 🍄, ô liu 🫒, cà chua 🍅 và phô mai mang đến hương vị đậm đà, béo thơm và cực kỳ cuốn. 😍\r\n\r\n🔥 Điểm nổi bật: Đế bánh vàng giòn • Phô mai béo ngậy 🧀 • Topping bò hấp dẫn 🥩 • Rau củ tươi ngon 🫑🍄 • Phù hợp để ăn cùng bạn bè và gia đình 👨‍👩‍👧‍👦\r\n\r\n❤️ Pizza nóng hổi vừa ra lò – gọi ngay và thưởng thức thôi! 🍕🔥', 'products/20260811052316-8ce99610-a825f5c570b3f195cdbcf0486b99064c.jpg', 3, 1),
(49, 'Pizza Phô Mai Cà Chua', 'PZPM002', '150000', 30, '🍕🧀 Pizza Phô Mai Cà Chua với lớp phô mai béo ngậy, cà chua tươi mọng 🍅 và lá húng quế thơm dịu 🌿. Đế bánh vàng giòn kết hợp cùng topping đơn giản nhưng cực kỳ hấp dẫn! 😋🔥', '🍕✨ PIZZA PHÔ MAI CÀ CHUA – ĐƠN GIẢN MÀ CUỐN! 🧀🍅\r\n\r\nMột chiếc pizza nóng hổi với đế bánh nướng vàng giòn 🔥, phủ bên trên lớp phô mai tan chảy béo thơm 🧀, kết hợp cùng những lát cà chua tươi 🍅 và lá húng quế 🌿.\r\n\r\nHương vị béo nhẹ của phô mai hòa quyện cùng vị tươi mát của cà chua tạo nên một món pizza dễ ăn nhưng vẫn vô cùng hấp dẫn. 😍\r\n\r\n🔥 Điểm nổi bật: Đế bánh vàng giòn • Phô mai tan chảy 🧀 • Cà chua tươi 🍅 • Húng quế thơm 🌿 • Thưởng thức nóng cực ngon ❤️\r\n\r\n👨‍👩‍👧‍👦 Phù hợp để ăn cùng bạn bè, gia đình hoặc dùng trong những buổi tụ họp.\r\n\r\n❤️ Pizza nóng hổi vừa ra lò – gọi ngay và thưởng thức thôi! 🍕🔥', 'products/20260811052458-160c6f11-1949ce1c5897ce21beb740ae9f5440b2.jpg', 5, 1),
(50, 'Phô Mai Viên Chiên Xù', 'PMV001', '36000', 25, '🧀🔥 Phô Mai Viên Chiên Xù với lớp vỏ ngoài vàng giòn, bên trong là phô mai mềm mịn, béo thơm và tan chảy hấp dẫn. Món ăn vặt cực cuốn dành cho tín đồ mê phô mai! 😋💛', '🧀🔥 PHÔ MAI VIÊN CHIÊN XÙ – GIÒN RỤM BÊN NGOÀI, BÉO NGẬY BÊN TRONG!\r\n\r\nNhững viên phô mai được bao phủ bởi lớp vỏ chiên xù vàng óng, giòn rụm ✨, bên trong là phần phô mai mềm mịn và béo thơm 🧀.\r\n\r\nCắn một miếng là cảm nhận ngay sự kết hợp giữa lớp vỏ giòn tan và nhân phô mai mềm béo, hấp dẫn. 😍\r\n\r\n🔥 Điểm nổi bật: Vỏ ngoài giòn rụm • Nhân phô mai béo thơm 🧀 • Màu vàng bắt mắt ✨ • Ăn nóng cực ngon • Hoàn hảo cho những buổi ăn vặt cùng bạn bè ❤️\r\n\r\n🍟 Có thể thưởng thức cùng tương ớt 🌶️ hoặc tương cà 🍅 để tăng thêm hương vị.\r\n\r\n❤️ Một miếng giòn tan – bên trong ngập vị phô mai! Thử ngay thôi! 🧀🔥', 'products/20260811065024-2b679fdd-4-chewy-cheese.jpg', 4, 25),
(51, 'Bánh Tart Trứng Nướng', 'BTT001', '36000', 23, '🥧💛 Bánh Tart Trứng Nướng với lớp vỏ ngàn lớp vàng giòn, kết hợp cùng nhân trứng mềm mịn, béo thơm và ngọt dịu. Nhỏ xinh nhưng cực kỳ hấp dẫn, hoàn hảo cho một bữa ăn nhẹ! 😋✨', '🥧✨ BÁNH TART TRỨNG NƯỚNG – GIÒN THƠM, BÉO MỊN! 💛\r\n\r\nChiếc bánh tart trứng nhỏ xinh với lớp vỏ bánh được nướng vàng óng, giòn xốp, kết hợp cùng phần nhân trứng mềm mịn 🥚, béo thơm và ngọt dịu.\r\n\r\n🔥 Khi thưởng thức, lớp vỏ giòn nhẹ hòa quyện cùng phần nhân trứng mềm béo bên trong, tạo nên hương vị thơm ngon và cực kỳ dễ ăn. 😍\r\n\r\n✨ Điểm nổi bật: Vỏ bánh vàng giòn 🥧 • Nhân trứng mềm mịn 🥚 • Béo thơm vừa phải 💛 • Kích thước nhỏ gọn • Phù hợp làm món ăn nhẹ hoặc tráng miệng.\r\n\r\n☕ Đặc biệt ngon khi dùng cùng cà phê, trà hoặc các loại nước uống.\r\n\r\n❤️ Một chiếc chưa chắc đã đủ – thưởng thức ngay khi bánh còn thơm ngon! 🥧😋', 'products/20260811065405-c2f2c07c-1-eggtart.jpg', 4, 25),
(52, 'Gà Rán Giòn Cay', 'GRC001', '125300', 11, '🍗🔥 Gà Rán Giòn Cay với lớp vỏ vàng ươm giòn rụm, thịt gà bên trong mềm mọng và đậm vị. Vị cay nhẹ kích thích vị giác, càng ăn càng cuốn! 🌶️😋', '🍗🔥 GÀ RÁN GIÒN CAY – GIÒN RỤM, ĐẬM VỊ! 🌶️\r\n\r\nNhững miếng gà được chiên vàng ươm, nóng giòn, phủ bên ngoài là lớp bột giòn rụm hấp dẫn ✨, bên trong vẫn giữ được phần thịt gà mềm mọng và thơm ngon. 🍗❤️\r\n\r\nKết hợp cùng chút cay nhẹ 🌶️ và gia vị đậm đà, món ăn mang đến cảm giác giòn tan ngay từ miếng đầu tiên. 😍\r\n\r\n🔥 Điểm nổi bật: Vỏ ngoài vàng giòn • Thịt gà mềm mọng 🍗 • Gia vị đậm đà • Cay nhẹ hấp dẫn 🌶️ • Ăn nóng cực ngon 😋\r\n\r\n🍟 Thưởng thức cùng khoai tây chiên, tương ớt 🌶️ hoặc tương cà 🍅 sẽ càng hấp dẫn hơn!\r\n\r\n❤️ Cắn một miếng giòn tan – thưởng thức trọn vị gà rán nóng hổi! 🍗🔥', 'products/20260811070310-82de04c9-7eaad9869aa8e5079685205ab087fdbb.jpg', 12, 25),
(53, 'Gà Rán Truyền Thống', 'GRT001', '23500', 56, '🍗✨ Gà Rán Truyền Thống với lớp vỏ vàng giòn hấp dẫn, thịt gà bên trong mềm mọng và thơm ngon. Hương vị đậm đà, dễ ăn và cực kỳ phù hợp cho những bữa ăn nhanh cùng bạn bè! 😋🔥', '🍗✨ GÀ RÁN TRUYỀN THỐNG – VÀNG GIÒN, THƠM NGON KHÓ CƯỠNG! 🔥\r\n\r\nTừng miếng gà được phủ lớp bột giòn và chiên đến khi vàng ươm, giòn rụm ✨. Bên trong là phần thịt gà mềm mọng 🍗, giữ được độ thơm ngon và hòa quyện cùng gia vị đậm đà.\r\n\r\nKhác với phiên bản gà cay 🌶️, Gà Rán Truyền Thống có hương vị dễ ăn, phù hợp với nhiều khẩu vị và đặc biệt ngon khi thưởng thức lúc còn nóng. 😍\r\n\r\n🔥 Điểm nổi bật: Vỏ ngoài giòn rụm • Thịt gà mềm mọng 🍗 • Gia vị thơm ngon • Không quá cay • Phù hợp cho cả gia đình 👨‍👩‍👧‍👦\r\n\r\n🍟 Ăn kèm khoai tây chiên, tương cà 🍅 hoặc tương ớt 🌶️ để bữa ăn thêm trọn vị!\r\n\r\n❤️ Giòn ngay miếng đầu tiên – ngon đến miếng cuối cùng! 🍗😋', 'products/20260811070431-864606da-4d65833db51e6c10030f79ca7ee52fb7.jpg', 7, 25),
(54, 'Sữa Gạo Horchata Wana 250ml', 'NU001', '250000', 1000, '🥛🌾 Sữa Gạo Horchata Wana với hương vị dịu nhẹ, thơm ngon và dễ uống. Lon nhỏ gọn 250ml, dùng lạnh cực kỳ sảng khoái và thích hợp để thưởng thức cùng các món ăn! ❄️😋', '🥛🌾 SỮA GẠO HORCHATA WANA – THƠM NGON, MÁT LẠNH! ❄️✨\r\n\r\nThưởng thức Wana Horchata Rice Milk 250ml với hương vị từ gạo 🌾 đặc trưng, thơm dịu và dễ uống. Một lựa chọn thú vị để giải khát hoặc dùng kèm trong bữa ăn. 😍\r\n\r\n🧊 Ngon hơn khi uống lạnh, đặc biệt phù hợp khi thưởng thức cùng pizza 🍕, gà rán 🍗, mì Ý 🍝 hoặc các món ăn nhẹ.\r\n\r\n✨ Điểm nổi bật: Hương vị gạo thơm nhẹ 🌾 • Dễ uống 🥛 • Lon 250ml tiện lợi • Uống lạnh ngon hơn ❄️ • Phù hợp dùng kèm nhiều món ăn 🍽️\r\n\r\n💙 Mát lạnh từng ngụm – thêm trọn vị cho bữa ăn! 🥛❄️', 'products/20260812080448-9d2f701e-sua-horchata.jpg', 4, 26),
(55, 'Trà Sữa Trân Châu Đường Nâu Wana 250ml', 'NU002', '35000', 25, '🧋🤎 Trà Sữa Trân Châu Đường Nâu Wana với vị trà sữa thơm béo, hòa quyện cùng hương đường nâu ngọt dịu và trân châu hấp dẫn. Lon 250ml tiện lợi, uống lạnh càng ngon! ❄️😋', '🧋🤎 TRÀ SỮA TRÂN CHÂU ĐƯỜNG NÂU WANA – BÉO THƠM, NGỌT NGÀO! ✨\r\n\r\nThưởng thức Wana Bubble Milk Tea 250ml với hương vị trà sữa thơm béo 🥛, kết hợp cùng đường nâu 🤎 tạo nên vị ngọt dịu và hấp dẫn.\r\n\r\nĐiểm nhấn đặc biệt là trân châu đen ⚫ quen thuộc của trà sữa, giúp mỗi ngụm thêm thú vị và cực kỳ cuốn. 😍🧋\r\n\r\n❄️ Ngon hơn khi uống lạnh, phù hợp để giải khát hoặc thưởng thức cùng pizza 🍕, gà rán 🍗 và các món ăn nhẹ 🍟.\r\n\r\n✨ Điểm nổi bật: Trà sữa thơm béo 🥛 • Hương đường nâu hấp dẫn 🤎 • Trân châu thú vị ⚫ • Lon 250ml tiện lợi • Uống lạnh cực ngon ❄️\r\n\r\n💖 Béo thơm từng ngụm – nhâm nhi trân châu, vui trọn bữa ăn! 🧋😋', 'products/20260812080620-b24fd068-sua-tran-chau.jpg', 9, 26),
(56, 'Nước Khoáng Vĩnh Hảo', 'NU003', '10000', 35, '💧❄️ Nước Khoáng Vĩnh Hảo thanh mát, tiện lợi và thích hợp dùng kèm mọi bữa ăn. Thưởng thức lạnh giúp giải khát tức thì và mang lại cảm giác sảng khoái! 🧊✨', '💧❄️ NƯỚC KHOÁNG VĨNH HẢO – THANH MÁT, SẢNG KHOÁI! 🧊\r\n\r\nNước Khoáng Vĩnh Hảo là lựa chọn đơn giản và tiện lợi để giải khát trong mọi bữa ăn. 💦 Hương vị tự nhiên, dễ uống và đặc biệt sảng khoái hơn khi dùng lạnh. ❄️😋\r\n\r\n🍽️ Thích hợp dùng kèm với nhiều món như pizza 🍕, mì Ý 🍝, gà rán 🍗, đồ ăn nhẹ 🍟 hoặc các combo của cửa hàng.\r\n\r\n✨ Điểm nổi bật: Thanh mát 💧 • Dễ uống • Tiện lợi 🧊 • Phù hợp với mọi bữa ăn 🍽️ • Uống lạnh cực sảng khoái ❄️\r\n\r\n💙 Mát lạnh từng ngụm – giải khát tức thì! 💧❄️', 'products/20260812080715-74b1d129-nuoc-khoang-vinh-hao-500ml.png', 6, 26),
(57, 'Nước Ép Cam Wana 330ml', 'NU004', '23000', 15, '🍊🧃 Nước Ép Cam Wana Premium với hương vị cam thơm ngon, chua ngọt hài hòa và cực kỳ dễ uống. Lon 330ml tiện lợi, dùng lạnh càng thêm mát lạnh và sảng khoái! ❄️😋', '🍊✨ NƯỚC ÉP CAM WANA – TƯƠI MÁT, SẢNG KHOÁI! 🧃❄️\r\n\r\nThưởng thức Wana Orange Premium Juice 330ml với hương vị cam 🍊 thơm ngon, mang đến vị chua ngọt hài hòa và cảm giác sảng khoái trong từng ngụm.\r\n\r\n🧊 Ngon hơn khi uống lạnh, đặc biệt thích hợp để giải khát hoặc dùng kèm với các món ăn như pizza 🍕, mì Ý 🍝, gà rán 🍗 và đồ ăn nhẹ 🍟.\r\n\r\n✨ Điểm nổi bật: Hương cam thơm ngon 🍊 • Chua ngọt dễ uống 🧃 • Lon 330ml tiện lợi • Giải khát cực đã 💦 • Uống lạnh càng ngon ❄️\r\n\r\n🧡 Bật nắp – uống ngay – tận hưởng vị cam mát lạnh! 🍊❄️', 'products/20260812080819-2be10a43-nuoc-ep-cam-330ml.jpg', 21, 26),
(58, 'Trà Lipton Đá Mát Lạnh', 'NU005', '23000', 31, '🍋🧊 Trà Lipton Đá thơm dịu, vị trà thanh mát kết hợp cùng đá lạnh sảng khoái ❄️. Một lựa chọn cực hợp để giải khát và thưởng thức cùng pizza, gà rán hay các món ăn nhẹ! 😋🥤', '🍋🧊 TRÀ LIPTON ĐÁ – MÁT LẠNH, GIẢI KHÁT CỰC ĐÃ! ❄️🥤\r\n\r\nThưởng thức một ly Trà Lipton Đá với hương trà thơm dịu 🍃, vị thanh mát kết hợp cùng những viên đá lạnh 🧊 mang đến cảm giác sảng khoái trong từng ngụm.\r\n\r\nĐặc biệt thích hợp để thưởng thức cùng các món gà rán 🍗, pizza 🍕, mì Ý 🍝 hay đồ ăn nhẹ 🍟, giúp bữa ăn thêm trọn vị.\r\n\r\n✨ Điểm nổi bật: Hương trà thơm dịu 🍃 • Thanh mát dễ uống 🥤 • Đá lạnh sảng khoái 🧊 • Giải khát cực đã ❄️ • Phù hợp dùng kèm nhiều món ăn 🍽️\r\n\r\n💛 Một ngụm mát lạnh – đánh thức sự sảng khoái! 🧊🍋', 'products/20260812080914-25ad7e3d-lipton-j.jpg', 4, 26),
(59, 'Nước Trái Cây Đỏ Chanh Tươi', 'NU006', '35000', 65, '🍹🍋 Nước Trái Cây Đỏ Chanh Tươi với hương vị chua ngọt hài hòa, kết hợp cùng lát chanh tươi thơm mát. Màu sắc bắt mắt, mát lạnh và cực kỳ sảng khoái! 🧊🌿', '🍹🍋 NƯỚC TRÁI CÂY ĐỎ CHANH TƯƠI – MÁT LẠNH, SẢNG KHOÁI! ❄️✨\r\n\r\nMột ly nước trái cây đỏ nổi bật với sắc màu bắt mắt ❤️, kết hợp cùng lát chanh tươi 🍋 tạo nên hương vị chua ngọt hài hòa và thơm mát.\r\n\r\n🧊 Thưởng thức khi lạnh giúp thức uống thêm tươi mát và sảng khoái, rất thích hợp để giải khát hoặc dùng kèm các món ăn tại cửa hàng. 😋\r\n\r\n✨ Điểm nổi bật: Hương vị chua ngọt 🍹 • Chanh tươi thơm mát 🍋 • Màu sắc hấp dẫn ❤️ • Mát lạnh sảng khoái 🧊 • Phù hợp dùng cùng nhiều món ăn 🍽️\r\n\r\n🌿🍹 Tươi mát từng ngụm – đánh thức vị giác và xua tan cơn khát! ❄️💦', 'products/20260812081009-057dafd7-da67585d9c3faa61198016a11605f91e_1696306851.jpg', 51, 26);

-- --------------------------------------------------------

--
-- Cấu trúc bảng cho bảng `tbl_site_asset`
--

CREATE TABLE `tbl_site_asset` (
  `asset_key` varchar(100) NOT NULL,
  `label` varchar(200) NOT NULL,
  `image_path` varchar(255) NOT NULL,
  `image_source` enum('asset','upload') NOT NULL DEFAULT 'asset',
  `updated_at` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Đang đổ dữ liệu cho bảng `tbl_site_asset`
--

INSERT INTO `tbl_site_asset` (`asset_key`, `label`, `image_path`, `image_source`, `updated_at`) VALUES
('admin_logo', 'Logo khu vực quản trị', 'site/admin-logo-20260812072924-65bdbb0d-logo.jpg', 'upload', '2026-08-12 00:29:24'),
('site_favicon', 'Biểu tượng tab trình duyệt', 'site/site-favicon-20260812072928-878df237-logo.jpg', 'upload', '2026-08-12 00:29:28'),
('site_logo', 'Logo website', 'site/site-logo-20260812072919-d71f672b-logo.jpg', 'upload', '2026-08-12 00:29:19');

--
-- Chỉ mục cho các bảng đã đổ
--

--
-- Chỉ mục cho bảng `tbl_admin`
--
ALTER TABLE `tbl_admin`
  ADD PRIMARY KEY (`id_admin`);

--
-- Chỉ mục cho bảng `tbl_baiviet`
--
ALTER TABLE `tbl_baiviet`
  ADD PRIMARY KEY (`id_bv`);

--
-- Chỉ mục cho bảng `tbl_banner`
--
ALTER TABLE `tbl_banner`
  ADD PRIMARY KEY (`id_banner`);

--
-- Chỉ mục cho bảng `tbl_banner_setting`
--
ALTER TABLE `tbl_banner_setting`
  ADD PRIMARY KEY (`id`);

--
-- Chỉ mục cho bảng `tbl_chatbot_history`
--
ALTER TABLE `tbl_chatbot_history`
  ADD PRIMARY KEY (`id`);

--
-- Chỉ mục cho bảng `tbl_chitietdonhang`
--
ALTER TABLE `tbl_chitietdonhang`
  ADD PRIMARY KEY (`id`),
  ADD KEY `id_donhang` (`id_donhang`);

--
-- Chỉ mục cho bảng `tbl_danhmuc`
--
ALTER TABLE `tbl_danhmuc`
  ADD PRIMARY KEY (`id_danhmuc`);

--
-- Chỉ mục cho bảng `tbl_danhmucbaiviet`
--
ALTER TABLE `tbl_danhmucbaiviet`
  ADD PRIMARY KEY (`id_baiviet`);

--
-- Chỉ mục cho bảng `tbl_donhang`
--
ALTER TABLE `tbl_donhang`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `madon` (`madon`);

--
-- Chỉ mục cho bảng `tbl_gioithieu`
--
ALTER TABLE `tbl_gioithieu`
  ADD PRIMARY KEY (`id`);

--
-- Chỉ mục cho bảng `tbl_goi_nhanvien`
--
ALTER TABLE `tbl_goi_nhanvien`
  ADD PRIMARY KEY (`id_goi`),
  ADD UNIQUE KEY `ma_goi` (`ma_goi`),
  ADD KEY `idx_goi_trangthai_ngaygoi` (`trangthai`,`ngaygoi`),
  ADD KEY `idx_goi_session_trangthai` (`session_id`,`trangthai`);

--
-- Chỉ mục cho bảng `tbl_lienhe`
--
ALTER TABLE `tbl_lienhe`
  ADD PRIMARY KEY (`id_lienhe`);

--
-- Chỉ mục cho bảng `tbl_sanpham`
--
ALTER TABLE `tbl_sanpham`
  ADD PRIMARY KEY (`id_sanpham`);

--
-- Chỉ mục cho bảng `tbl_site_asset`
--
ALTER TABLE `tbl_site_asset`
  ADD PRIMARY KEY (`asset_key`);

--
-- AUTO_INCREMENT cho các bảng đã đổ
--

--
-- AUTO_INCREMENT cho bảng `tbl_admin`
--
ALTER TABLE `tbl_admin`
  MODIFY `id_admin` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- AUTO_INCREMENT cho bảng `tbl_baiviet`
--
ALTER TABLE `tbl_baiviet`
  MODIFY `id_bv` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=30;

--
-- AUTO_INCREMENT cho bảng `tbl_banner`
--
ALTER TABLE `tbl_banner`
  MODIFY `id_banner` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=17;

--
-- AUTO_INCREMENT cho bảng `tbl_chatbot_history`
--
ALTER TABLE `tbl_chatbot_history`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=100;

--
-- AUTO_INCREMENT cho bảng `tbl_chitietdonhang`
--
ALTER TABLE `tbl_chitietdonhang`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=89;

--
-- AUTO_INCREMENT cho bảng `tbl_danhmuc`
--
ALTER TABLE `tbl_danhmuc`
  MODIFY `id_danhmuc` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=27;

--
-- AUTO_INCREMENT cho bảng `tbl_danhmucbaiviet`
--
ALTER TABLE `tbl_danhmucbaiviet`
  MODIFY `id_baiviet` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=19;

--
-- AUTO_INCREMENT cho bảng `tbl_donhang`
--
ALTER TABLE `tbl_donhang`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=54;

--
-- AUTO_INCREMENT cho bảng `tbl_gioithieu`
--
ALTER TABLE `tbl_gioithieu`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT cho bảng `tbl_goi_nhanvien`
--
ALTER TABLE `tbl_goi_nhanvien`
  MODIFY `id_goi` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=14;

--
-- AUTO_INCREMENT cho bảng `tbl_lienhe`
--
ALTER TABLE `tbl_lienhe`
  MODIFY `id_lienhe` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=23;

--
-- AUTO_INCREMENT cho bảng `tbl_sanpham`
--
ALTER TABLE `tbl_sanpham`
  MODIFY `id_sanpham` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=60;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
