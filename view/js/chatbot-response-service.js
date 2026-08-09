window.FastFoodChatbotResponseService = (() => {
    const staticResponses = {
        chao: 'Xin chào! 👋 Tôi là trợ lý AI của FastFood. Hôm nay bạn muốn tìm hiểu gì nào?',
        'xin chao': 'Xin chào! 👋 Tôi là trợ lý AI của FastFood. Hôm nay bạn muốn tìm hiểu gì nào?',
        hello: 'Hello! 👋 Tôi là trợ lý AI của FastFood. Bạn cần giúp gì không?',
        hi: 'Hi! 👋 FastFood AI đây. Bạn muốn hỏi gì?',
        hey: 'Hey! 👋 FastFood AI đây. Mình giúp gì được cho bạn?',
        ten: 'Tôi là FastFood AI 🤖 - trợ lý ảo của nhà hàng FastFood.',
        'la ai': 'Tôi là FastFood AI 🤖 - trợ lý ảo của nhà hàng FastFood.',
        'cam on': 'Không có gì! 😊 Rất vui được giúp bạn. Nếu cần hỗ trợ thêm, cứ hỏi nhé!',
        thanks: 'Không có gì! 😊 Rất vui được giúp bạn. Nếu cần hỗ trợ thêm, cứ hỏi nhé!',
        'tam biet': 'Tạm biệt! 👋 Chúc bạn có một bữa ăn ngon miệng. Hẹn gặp lại!',
        bye: 'Tạm biệt! 👋 Chúc bạn có một bữa ăn ngon miệng. Hẹn gặp lại!',
        'hen gap lai': 'Hẹn gặp lại bạn! 👋 Chúc bạn ngon miệng!',
        hoi: 'Bạn có thể hỏi tôi về: thực đơn, giá cả, khuyến mãi, địa chỉ, giờ mở cửa, đặt hàng hoặc giao hàng.',
        giup: 'Bạn có thể hỏi tôi về: thực đơn, giá cả, khuyến mãi, địa chỉ, giờ mở cửa, đặt hàng hoặc giao hàng.',
        'giup do': 'Bạn có thể hỏi tôi về: thực đơn, giá cả, khuyến mãi, địa chỉ, giờ mở cửa, đặt hàng hoặc giao hàng.',
        'tu van': 'Bạn có thể hỏi tôi về: thực đơn, giá cả, khuyến mãi, địa chỉ, giờ mở cửa, đặt hàng hoặc giao hàng.',
        'dia chi': '📍 Cửa hàng của chúng tôi tại: Quận 7, Thành phố Hồ Chí Minh. Hotline: 1900 6099.',
        'o dau': '📍 Cửa hàng của chúng tôi tại: Quận 7, Thành phố Hồ Chí Minh. Hotline: 1900 6099.',
        'lien he': '📞 Hotline: 1900 6099 | 📍 Địa chỉ: Quận 7, TP.HCM | 📧 Email: congtoan2k4@gmail.com',
        sdt: '📞 Hotline: 1900 6099 | 📍 Địa chỉ: Quận 7, TP.HCM | 📧 Email: congtoan2k4@gmail.com',
        'gio mo cua': '⏰ Cửa hàng mở cửa từ 9:00 sáng đến 22:00 tối, cả tuần kể cả ngày lễ. Đến sớm để chọn món ngon nhé!',
        'mo cua': '⏰ Cửa hàng mở cửa từ 9:00 sáng đến 22:00 tối, cả tuần kể cả ngày lễ.',
        'dong cua': '⏰ Cửa hàng đóng cửa lúc 22:00 tối. Hôm nay còn mở nếu bạn đến trước 22:00 nhé!',
        'dat hang': '📞 Bạn có thể gọi hotline 1900 6099 hoặc đến trực tiếp cửa hàng để đặt món. Chúng tôi phục vụ tận nơi tại quán!',
        'dat mon': '📞 Bạn có thể gọi hotline 1900 6099 hoặc đến trực tiếp cửa hàng để đặt món. Chúng tôi phục vụ tận nơi tại quán!',
        mua: '📞 Bạn có thể gọi hotline 1900 6099 hoặc đến trực tiếp cửa hàng để đặt món.',
        ship: '🛵 Hiện tại chúng tôi chỉ phục vụ tại cửa hàng. Bạn đến trực tiếp để thưởng thức món ngon nóng hổi nhé!',
        'giao hang': '🛵 Hiện tại chúng tôi chỉ phục vụ tại cửa hàng. Bạn đến trực tiếp để thưởng thức món ngon nóng hổi nhé!',
        'mang ve': '🛍️ Bạn có thể đến cửa hàng đặt món và mang về. Chúng tôi hỗ trợ đóng gói cẩn thận!'
    };

    const normalizeText = (value) => {
        return value
            .replace(/[àáạảãâầấậẩẫăằắặẳẵÀÁẠẢÃÂẦẤẬẨẪĂẰẮẶẲẴ]/g, 'a')
            .replace(/[èéẹẻẽêềếệểễÈÉẸẺẼÊỀẾỆỂỄ]/g, 'e')
            .replace(/[ìíịỉĩÌÍỊỈĨ]/g, 'i')
            .replace(/[òóọỏõôồốộổỗơờớợởỡÒÓỌỎÕÔỒỐỘỔỖƠỜỚỢỞỠ]/g, 'o')
            .replace(/[ùúụủũưừứựửữÙÚỤỦŨƯỪỨỰỬỮ]/g, 'u')
            .replace(/[ỳýỵỷỹỲÝỴỶỸ]/g, 'y')
            .replace(/[đĐ]/g, 'd')
            .toLowerCase();
    };

    const escapeRegExp = (value) => value.replace(/[.*+?^${}()|[\]\\]/g, '\\$&');

    const hasKeyword = (normalizedMessage, keywords) => {
        return keywords.some((keyword) => {
            const normalizedKeyword = normalizeText(keyword);
            const pattern = new RegExp('(?:^|\\s)' + escapeRegExp(normalizedKeyword) + '(?:$|\\s)');
            return pattern.test(normalizedMessage);
        });
    };

    const requestJson = async (url) => {
        const response = await fetch(url);
        if (!response.ok) {
            throw new Error('API responded with status ' + response.status);
        }

        return response.json();
    };

    const getProductsResponse = async (apiBase) => {
        const data = await requestJson(apiBase + '?action=get_products');

        if (!data.success || data.count <= 0) {
            return { response: '🍽️ Hiện tại không có món nào trong thực đơn.', keyword: 'thuc don', type: 'api_products' };
        }

        let reply = '🍕 Hiện tại chúng tôi có ' + data.count + ' món đang bán:\n';
        data.data.slice(0, 5).forEach((product, index) => {
            reply += (index + 1) + '. ' + product.tensanpham + ' - ' + parseInt(product.giasp).toLocaleString() + 'đ\n';
        });

        if (data.count > 5) {
            reply += '... và ' + (data.count - 5) + ' món khác!';
        }

        return { response: reply, keyword: 'thuc don', type: 'api_products' };
    };

    const getPriceResponse = async (apiBase, message) => {
        const productMatch = message.match(/giá?\s+(.+?)(?:\s+bao|\s+nhieu|$)/i);

        if (productMatch) {
            const data = await requestJson(apiBase + '?action=search_product&keyword=' + encodeURIComponent(productMatch[1]));

            if (data.success && data.data.length > 0) {
                const product = data.data[0];
                return {
                    response: '💰 ' + product.tensanpham + ' có giá ' + parseInt(product.giasp).toLocaleString() + 'đ. Còn ' + product.soluong + ' phần!',
                    keyword: 'gia',
                    type: 'api_price'
                };
            }

            return {
                response: 'Không tìm thấy món này. Giá trung bình khoảng ' + (data.success ? parseInt(data.data.avg_price || 0).toLocaleString() + 'đ' : 'không rõ') + '.',
                keyword: 'gia',
                type: 'api_price'
            };
        }

        const data = await requestJson(apiBase + '?action=get_price_range');
        if (!data.success) {
            return { response: '❌ API trả về lỗi: ' + (data.message || 'không rõ'), keyword: '', type: 'error' };
        }

        return {
            response: '💵 Giá từ ' + parseInt(data.data.min_price).toLocaleString() + 'đ đến ' + parseInt(data.data.max_price).toLocaleString() + 'đ.',
            keyword: 'gia',
            type: 'api_price'
        };
    };

    const getPromotionsResponse = async (apiBase) => {
        const data = await requestJson(apiBase + '?action=get_promotions');

        if (!data.success || data.data.length === 0) {
            return { response: '🎊 Hiện tại không có khuyến mãi nào.', keyword: 'khuyen mai', type: 'api_promo' };
        }

        let reply = '🎉 Khuyến mãi:\n';
        data.data.forEach((post, index) => {
            reply += (index + 1) + '. ' + post.tenbaiviet + '\n';
        });

        return { response: reply, keyword: 'khuyen mai', type: 'api_promo' };
    };

    const getStockResponse = async (apiBase, message) => {
        const productMatch = message.match(/(?:còn|het|hết)\s+(.+?)(?:\s+không|$)/i) || message.match(/(.+?)\s+(?:còn|het|hết)/i);
        if (!productMatch) {
            return null;
        }

        const data = await requestJson(apiBase + '?action=check_stock&product=' + encodeURIComponent(productMatch[1]));
        if (!data.success) {
            return { response: 'Không tìm thấy món này.', keyword: '', type: 'api_stock' };
        }

        if (data.data.soluong > 0) {
            return { response: '✅ Còn ' + data.data.soluong + ' phần ' + data.data.tensanpham + '!', keyword: 'con hang', type: 'api_stock' };
        }

        return { response: '❌ ' + data.data.tensanpham + ' đã hết hàng.', keyword: 'het hang', type: 'api_stock' };
    };

    const getStaticResponse = (normalizedMessage) => {
        for (const key in staticResponses) {
            const normalizedKey = normalizeText(key);
            const pattern = new RegExp('(?:^|\\s)' + escapeRegExp(normalizedKey) + '(?:$|\\s)');

            if (pattern.test(normalizedMessage)) {
                return { response: staticResponses[key], keyword: key, type: 'static' };
            }
        }

        return null;
    };

    const getBotResponse = async (message, apiBase) => {
        try {
            const normalizedMessage = normalizeText(message);

            if (hasKeyword(normalizedMessage, ['thực đơn', 'thuc don', 'menu', 'món ăn', 'mon an', 'có gì', 'co gi', 'danh sách', 'danh sach'])) {
                return await getProductsResponse(apiBase);
            }

            if (hasKeyword(normalizedMessage, ['giá', 'gia', 'bao nhiêu', 'bao nhieu', 'tiền', 'tien', 'đắt', 'dat', 'rẻ', 're'])) {
                return await getPriceResponse(apiBase, message);
            }

            if (hasKeyword(normalizedMessage, ['khuyến mãi', 'khuyen mai', 'giảm giá', 'giam gia', 'ưu đãi', 'uu dai', 'sale'])) {
                return await getPromotionsResponse(apiBase);
            }

            if (hasKeyword(normalizedMessage, ['còn', 'con', 'hết', 'het', 'tồn kho', 'ton kho', 'có không'])) {
                const stockResponse = await getStockResponse(apiBase, message);
                if (stockResponse) {
                    return stockResponse;
                }
            }

            return getStaticResponse(normalizedMessage) || {
                response: 'Xin lỗi, tôi chưa hiểu ý bạn lắm 😅 Bạn thử hỏi bằng tiếng Việt không dấu hoặc dùng các từ khóa như:<br>• "Thực đơn có gì?"<br>• "Giá pizza bao nhiêu?"<br>• "Còn gà rán không?"<br>• "Khuyến mãi hiện tại"<br>• "Địa chỉ cửa hàng"<br>• "Giờ mở cửa"',
                keyword: '',
                type: 'fallback'
            };
        } catch (error) {
            return { response: '❌ Không thể kết nối tới server. Vui lòng thử lại sau!', keyword: '', type: 'error' };
        }
    };

    return {
        getBotResponse
    };
})();
