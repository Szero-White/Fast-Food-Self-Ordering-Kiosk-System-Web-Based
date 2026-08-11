window.FastFoodChatbotResponseService = (() => {
    const staticResponses = {
        chao: 'Xin chào! Tôi là FastFood AI. Hôm nay bạn muốn chọn món, xem giá hay hỏi khuyến mãi nào?',
        'xin chao': 'Xin chào! Tôi là FastFood AI. Hôm nay bạn muốn chọn món, xem giá hay hỏi khuyến mãi nào?',
        hello: 'Hello! FastFood AI sẵn sàng hỗ trợ bạn chọn món.',
        hi: 'Hi! Bạn muốn ăn nhẹ, ăn no hay xem món đang khuyến mãi?',
        ten: 'Tôi là FastFood AI, trợ lý đặt món của FastFood Kiosk.',
        'la ai': 'Tôi là FastFood AI, trợ lý đặt món của FastFood Kiosk.',
        'cam on': 'Không có gì! Chúc bạn chọn được món thật ngon.',
        thanks: 'Không có gì! Chúc bạn chọn được món thật ngon.',
        'tam biet': 'Tạm biệt! Hẹn gặp lại bạn ở FastFood.',
        bye: 'Tạm biệt! Hẹn gặp lại bạn ở FastFood.',
        hoi: 'Bạn có thể hỏi tôi về thực đơn, giá món, tồn kho, khuyến mãi, giờ mở cửa hoặc nói thẳng: "Tôi muốn đặt 2 phần Pepsi".',
        giup: 'Bạn có thể hỏi tôi về thực đơn, giá món, tồn kho, khuyến mãi, giờ mở cửa hoặc nói thẳng: "Tôi muốn đặt 2 phần Pepsi".',
        'dia chi': 'Cửa hàng tại Quận 7, Thành phố Hồ Chí Minh. Hotline: 1900 6099.',
        'o dau': 'Cửa hàng tại Quận 7, Thành phố Hồ Chí Minh. Hotline: 1900 6099.',
        'lien he': 'Hotline: 1900 6099 | Địa chỉ: Quận 7, TP.HCM | Email: congtoan2k4@gmail.com',
        sdt: 'Hotline: 1900 6099 | Địa chỉ: Quận 7, TP.HCM | Email: congtoan2k4@gmail.com',
        'gio mo cua': 'Cửa hàng mở cửa từ 08:00 đến 22:00 mỗi ngày.',
        'mo cua': 'Cửa hàng mở cửa từ 08:00 đến 22:00 mỗi ngày.',
        'dong cua': 'Cửa hàng đóng cửa lúc 22:00. Bạn ghé trước giờ đó để chọn món nhé.',
        ship: 'Hiện tại kiosk phục vụ đặt món tại cửa hàng. Bạn có thể chọn món, thanh toán rồi nhận món tại quầy.',
        'giao hang': 'Hiện tại kiosk phục vụ đặt món tại cửa hàng. Bạn có thể chọn món, thanh toán rồi nhận món tại quầy.',
        'mang ve': 'Bạn có thể đặt món tại kiosk và mang về. Hệ thống sẽ lưu đơn để quầy chuẩn bị món.'
    };

    let productCache = null;
    const lastProductStorageKey = 'fastfood-chatbot-last-product';
    const conversationContextStorageKey = 'fastfood-chatbot-context';

    const normalizeText = (value) => {
        return String(value || '')
            .normalize('NFD')
            .replace(/[\u0300-\u036f]/g, '')
            .replace(/đ/g, 'd')
            .replace(/Đ/g, 'D')
            .toLowerCase()
            .replace(/[^\p{L}\p{N}\s]/gu, ' ')
            .replace(/\s+/g, ' ')
            .trim();
    };

    const escapeRegExp = (value) => value.replace(/[.*+?^${}()|[\]\\]/g, '\\$&');

    const formatMoney = (value) => Number(value || 0).toLocaleString('vi-VN') + 'đ';

    const textTokens = (normalizedText) => {
        return new Set(String(normalizedText || '').split(' ').filter(Boolean));
    };

    const ignoredProductTokens = new Set([
        'mon',
        'phan',
        'suat',
        'lon',
        'ly',
        'cai',
        'dia',
        'an',
        'to',
        'hop',
        'cho',
        'toi',
        'minh',
        'ban',
        'co',
        'khong',
        'con',
        'bao',
        'nhieu',
        'gia',
        'dat',
        'mua',
        'them',
        'lay',
        'vay',
        'di',
        'nay',
        'do'
    ]);

    const ignoredCategoryTokens = new Set([
        'mon',
        'danh',
        'muc',
        'an',
        'co',
        'khong',
        'con',
        'bao',
        'nhieu',
        'gia',
        'dat',
        'mua',
        'lay',
        'vay',
        'di',
        'nay',
        'do'
    ]);

    const editDistance = (firstValue, secondValue) => {
        const first = String(firstValue || '');
        const second = String(secondValue || '');

        if (first === second) {
            return 0;
        }

        if (!first || !second) {
            return Math.max(first.length, second.length);
        }

        const rows = Array.from({ length: first.length + 1 }, (_, rowIndex) => [rowIndex]);
        for (let columnIndex = 1; columnIndex <= second.length; columnIndex += 1) {
            rows[0][columnIndex] = columnIndex;
        }

        for (let rowIndex = 1; rowIndex <= first.length; rowIndex += 1) {
            for (let columnIndex = 1; columnIndex <= second.length; columnIndex += 1) {
                const cost = first[rowIndex - 1] === second[columnIndex - 1] ? 0 : 1;
                rows[rowIndex][columnIndex] = Math.min(
                    rows[rowIndex - 1][columnIndex] + 1,
                    rows[rowIndex][columnIndex - 1] + 1,
                    rows[rowIndex - 1][columnIndex - 1] + cost
                );
            }
        }

        return rows[first.length][second.length];
    };

    const tokenMatches = (sourceToken, candidateToken) => {
        if (sourceToken === candidateToken) {
            return true;
        }

        if (sourceToken.length < 4 || candidateToken.length < 4) {
            return false;
        }

        return editDistance(sourceToken, candidateToken) <= 1;
    };

    const countMatchedTokens = (sourceTokens, candidateTokens) => {
        return sourceTokens.filter((sourceToken) => {
            return candidateTokens.some((candidateToken) => tokenMatches(sourceToken, candidateToken));
        });
    };

    const hasKeyword = (normalizedMessage, keywords) => {
        return keywords.some((keyword) => {
            const normalizedKeyword = normalizeText(keyword);
            const pattern = new RegExp('(?:^|\\s)' + escapeRegExp(normalizedKeyword) + '(?:$|\\s)');
            return pattern.test(normalizedMessage);
        });
    };

    const requestJson = async (url, options = {}) => {
        const response = await fetch(url, options);
        if (!response.ok) {
            throw new Error('API responded with status ' + response.status);
        }

        return response.json();
    };

    const getProducts = async (apiBase) => {
        if (productCache) {
            return productCache;
        }

        const data = await requestJson(apiBase + '?action=get_products');
        productCache = data.success && Array.isArray(data.data) ? data.data : [];
        return productCache;
    };

    const parseQuantity = (normalizedMessage) => {
        const match = normalizedMessage.match(/(?:^|\s)(\d{1,6})(?:\s+phan|\s+suat|\s+mon|\s+ly|\s+lon|\s+cai|\s|$)/);
        if (!match) {
            return 1;
        }

        return Math.max(1, Number(match[1]));
    };

    const productScore = (product, normalizedMessage) => {
        const normalizedName = normalizeText(product.tensanpham);
        if (!normalizedName) {
            return 0;
        }

        if (normalizedMessage.includes(normalizedName)) {
            return 100 + normalizedName.length;
        }

        const messageTokens = [...textTokens(normalizedMessage)]
            .filter((token) => token.length >= 2 && !ignoredProductTokens.has(token));
        const productTokens = normalizedName
            .split(' ')
            .filter((token) => token.length >= 2 && !ignoredProductTokens.has(token));
        const matchedTokens = countMatchedTokens(productTokens, messageTokens);

        if (matchedTokens.length === 0) {
            return 0;
        }

        const coverage = matchedTokens.length / productTokens.length;
        return matchedTokens.length * 10 + matchedTokens.join('').length + Math.round(coverage * 20);
    };

    const findBestProduct = (products, message) => {
        const normalizedMessage = normalizeText(message);
        return products
            .map((product) => ({ product, score: productScore(product, normalizedMessage) }))
            .filter((entry) => entry.score > 0)
            .sort((a, b) => b.score - a.score)[0]?.product || null;
    };

    const categoryScore = (categoryName, normalizedMessage) => {
        const normalizedCategory = normalizeText(categoryName);
        if (!normalizedCategory) {
            return 0;
        }

        if (normalizedMessage.includes(normalizedCategory)) {
            return 100 + normalizedCategory.length;
        }

        const messageTokens = [...textTokens(normalizedMessage)]
            .filter((token) => token.length >= 2 && !ignoredCategoryTokens.has(token));
        const categoryTokens = normalizedCategory
            .split(' ')
            .filter((token) => token.length >= 2 && !ignoredCategoryTokens.has(token));
        const matchedTokens = countMatchedTokens(categoryTokens, messageTokens);

        if (matchedTokens.length === 0) {
            return 0;
        }

        const coverage = matchedTokens.length / categoryTokens.length;
        return matchedTokens.length * 10 + matchedTokens.join('').length + Math.round(coverage * 10);
    };

    const findBestCategory = (products, message) => {
        const normalizedMessage = normalizeText(message);
        const categories = [...new Set(products.map((product) => product.tendanhmuc).filter(Boolean))];

        return categories
            .map((category) => ({ category, score: categoryScore(category, normalizedMessage) }))
            .filter((entry) => entry.score > 0)
            .sort((a, b) => b.score - a.score)[0]?.category || null;
    };

    const loadConversationContext = () => {
        try {
            return JSON.parse(localStorage.getItem(conversationContextStorageKey) || '{}');
        } catch (error) {
            return {};
        }
    };

    const saveConversationContext = (contextPatch) => {
        try {
            localStorage.setItem(conversationContextStorageKey, JSON.stringify({
                ...loadConversationContext(),
                ...contextPatch
            }));
        } catch (error) {
            // Không để lỗi localStorage làm gián đoạn chatbot.
        }
    };

    const rememberProduct = (product) => {
        if (!product || !product.id_sanpham) {
            return;
        }

        try {
            localStorage.setItem(lastProductStorageKey, JSON.stringify({
                id_sanpham: Number(product.id_sanpham),
                tensanpham: product.tensanpham
            }));
            saveConversationContext({
                lastProductId: Number(product.id_sanpham),
                lastProductName: product.tensanpham,
                lastCategoryName: product.tendanhmuc || '',
                lastIntent: 'product'
            });
        } catch (error) {
            // Không để lỗi localStorage làm gián đoạn luồng đặt món.
        }
    };

    const getRememberedProduct = (products) => {
        try {
            const context = loadConversationContext();
            const storedProduct = JSON.parse(localStorage.getItem(lastProductStorageKey) || '{}');
            const productId = Number(context.lastProductId || storedProduct.id_sanpham || 0);

            if (productId <= 0) {
                return null;
            }

            return products.find((product) => Number(product.id_sanpham) === productId) || null;
        } catch (error) {
            return null;
        }
    };

    const rememberCategory = (categoryName, intent = 'category') => {
        if (!categoryName) {
            return;
        }

        saveConversationContext({
            lastCategoryName: categoryName,
            lastIntent: intent
        });
    };

    const getRememberedCategory = () => {
        const context = loadConversationContext();
        return typeof context.lastCategoryName === 'string' ? context.lastCategoryName : '';
    };

    const isContextReference = (normalizedMessage) => {
        return hasKeyword(normalizedMessage, [
            'mon do',
            'mon nay',
            'cai do',
            'cai nay',
            'no',
            'do',
            'vay',
            'mon vua roi',
            'muc do',
            'danh muc do'
        ]);
    };

    const resolveProduct = (products, message) => {
        const normalizedMessage = normalizeText(message);
        return findBestProduct(products, message)
            || (isContextReference(normalizedMessage) || isFollowUpOrderRequest(normalizedMessage)
                ? getRememberedProduct(products)
                : null);
    };

    const resolveCategory = (products, message) => {
        const normalizedMessage = normalizeText(message);
        return findBestCategory(products, message)
            || (isContextReference(normalizedMessage) ? getRememberedCategory() : '');
    };

    const isFollowUpOrderRequest = (normalizedMessage) => {
        return parseQuantity(normalizedMessage) > 1
            && hasKeyword(normalizedMessage, ['vay', 'cho', 'lay', 'them', 'di']);
    };

    const isOrderRequest = (normalizedMessage) => {
        return hasKeyword(normalizedMessage, [
            'dat',
            'dat mon',
            'mua',
            'them',
            'them vao gio',
            'lay',
            'cho',
            'cho toi',
            'cho minh',
            'toi muon',
            'minh muon'
        ]);
    };

    const isRecommendationRequest = (normalizedMessage) => {
        return hasKeyword(normalizedMessage, [
            'tu van',
            'goi y',
            'nen chon',
            'nen an',
            'mon nao',
            'mon gi',
            'mon ngon',
            'an gi',
            'nhe',
            're',
            'duoi',
            'it tien',
            'hoc sinh',
            'sinh vien',
            'no',
            'phu hop'
        ]);
    };

    const isNewArrivalRequest = (normalizedMessage) => {
        return hasKeyword(normalizedMessage, [
            'mon moi',
            'moi nhap',
            'moi them',
            'hom nay co gi moi',
            'co gi moi',
            'hang moi',
            'san pham moi',
            'mon an moi'
        ]);
    };

    const isGenericStockQuestion = (normalizedMessage) => {
        return hasKeyword(normalizedMessage, ['con hang', 'het hang', 'ton kho'])
            && !hasKeyword(normalizedMessage, ['com', 'ga', 'pizza', 'pepsi', '7up', 'salad', 'burger', 'mi', 'nuoc', 'tra']);
    };

    const isStockRequest = (normalizedMessage) => {
        return hasKeyword(normalizedMessage, [
            'con',
            'con khong',
            'co khong',
            'con bao nhieu',
            'ton kho',
            'het',
            'het hang'
        ]);
    };

    const isPriceRequest = (normalizedMessage) => {
        return hasKeyword(normalizedMessage, [
            'gia',
            'gia ban',
            'bao nhieu tien',
            'may tien',
            'tien',
            're'
        ]);
    };

    const getCartAddResponse = async (apiBase, message) => {
        const normalizedMessage = normalizeText(message);
        if (!isOrderRequest(normalizedMessage)) {
            return null;
        }

        const products = await getProducts(apiBase);
        const product = resolveProduct(products, message);
        if (!product) {
            return null;
        }

        rememberProduct(product);

        const quantity = parseQuantity(normalizedMessage);
        const stock = Number(product.soluong || 0);
        if (stock <= 0) {
            return {
                response: product.tensanpham + ' hiện đã hết hàng. Bạn muốn tôi gợi ý món khác cùng danh mục không?',
                keyword: 'het hang',
                type: 'api_stock'
            };
        }

        if (quantity > stock) {
            return {
                response: product.tensanpham + ' hiện chỉ còn ' + stock + ' phần. Bạn có thể giảm số lượng hoặc chọn thêm món khác nhé.',
                keyword: 'ton kho',
                type: 'api_stock'
            };
        }

        const addResult = await requestJson(apiBase + '?action=add_to_cart', {
            method: 'POST',
            headers: { 'Content-Type': 'application/json' },
            body: JSON.stringify({
                product_id: Number(product.id_sanpham),
                quantity
            })
        });

        if (!addResult.success) {
            return null;
        }

        const unitPrice = Number(product.giasp || 0);
        const lineTotal = unitPrice * quantity;
        const cartTotal = Number(addResult.data?.cart_total || lineTotal);
        const cartQuantity = Number(addResult.data?.cart_quantity || quantity);

        return {
            response:
                'Đã thêm ' + quantity + ' phần ' + product.tensanpham + ' vào giỏ hàng.\n' +
                'Đơn giá: ' + formatMoney(unitPrice) + ' | Tạm tính: ' + formatMoney(lineTotal) + '.\n' +
                'Giỏ hàng hiện có ' + cartQuantity + ' món, tổng tạm tính ' + formatMoney(cartTotal) + '. Bạn có thể mở Giỏ hàng để kiểm tra và thanh toán.',
            keyword: 'dat mon',
            type: 'api_cart_add',
            cartQuantity,
            cartTotal
        };
    };

    const getProductsResponse = async (apiBase) => {
        const products = await getProducts(apiBase);

        if (products.length === 0) {
            return { response: 'Hiện tại chưa có món nào đang bán trong thực đơn.', keyword: 'thuc don', type: 'api_products' };
        }

        let reply = 'Thực đơn hiện có ' + products.length + ' món đang bán:\n';
        products.slice(0, 6).forEach((product, index) => {
            reply += (index + 1) + '. ' + product.tensanpham + ' - ' + formatMoney(product.giasp) + ', còn ' + product.soluong + ' phần\n';
        });

        if (products.length > 6) {
            reply += 'Bạn có thể hỏi tên món cụ thể để xem giá hoặc nói "đặt 2 phần tên món".';
        }

        return { response: reply, keyword: 'thuc don', type: 'api_products' };
    };

    const getNewProductsResponse = async (apiBase) => {
        const products = await getProducts(apiBase);
        const newProducts = products
            .filter((product) => Number(product.soluong || 0) > 0)
            .slice(0, 5);

        if (newProducts.length === 0) {
            return {
                response: 'Hiện tại chưa có món mới đang còn hàng. Bạn có thể xem thực đơn để chọn món khác nhé.',
                keyword: 'mon moi',
                type: 'api_products'
            };
        }

        let reply = 'Các món mới nhập đang còn hàng:\n';
        newProducts.forEach((product, index) => {
            reply += (index + 1) + '. ' + product.tensanpham + ' - ' + formatMoney(product.giasp) + ', còn ' + product.soluong + ' phần\n';
        });
        reply += 'Bạn có thể nói "đặt 2 phần tên món" để mình thêm nhanh vào giỏ hàng.';

        return {
            response: reply,
            keyword: 'mon moi',
            type: 'api_products'
        };
    };

    const getCategoryProductsResponse = async (apiBase, message) => {
        const products = await getProducts(apiBase);
        const normalizedMessage = normalizeText(message);
        const explicitCategoryName = findBestCategory(products, message);
        const wantsCategoryList = hasKeyword(normalizedMessage, [
            'mon nao',
            'mon gi',
            'con gi',
            'co gi',
            'mon khac',
            'danh muc',
            'xem them'
        ]);
        const categoryName = explicitCategoryName
            || (wantsCategoryList && isContextReference(normalizedMessage) ? getRememberedCategory() : '');

        if (!categoryName) {
            return null;
        }

        rememberCategory(categoryName, 'category_products');

        const categoryProducts = products
            .filter((product) => normalizeText(product.tendanhmuc) === normalizeText(categoryName))
            .filter((product) => Number(product.soluong || 0) > 0)
            .slice(0, 6);

        if (categoryProducts.length === 0) {
            return {
                response: 'Danh mục ' + categoryName + ' hiện chưa có món còn hàng. Bạn muốn xem danh mục khác không?',
                keyword: normalizeText(categoryName),
                type: 'api_products'
            };
        }

        let reply = 'Danh mục ' + categoryName + ' hiện có:\n';
        categoryProducts.forEach((product, index) => {
            reply += (index + 1) + '. ' + product.tensanpham + ' - ' + formatMoney(product.giasp) + ', còn ' + product.soluong + ' phần\n';
        });
        reply += 'Bạn có thể hỏi giá, tồn kho hoặc nói "đặt 2 phần tên món".';

        return {
            response: reply,
            keyword: normalizeText(categoryName),
            type: 'api_products'
        };
    };

    const getPriceResponse = async (apiBase, message) => {
        const products = await getProducts(apiBase);
        const product = resolveProduct(products, message);

        if (product) {
            rememberProduct(product);
            return {
                response: product.tensanpham + ' có giá ' + formatMoney(product.giasp) + ', hiện còn ' + product.soluong + ' phần.',
                keyword: 'gia',
                type: 'api_price'
            };
        }

        const data = await requestJson(apiBase + '?action=get_price_range');
        if (!data.success) {
            return { response: 'Chưa lấy được khoảng giá lúc này. Bạn thử lại sau nhé.', keyword: '', type: 'error' };
        }

        return {
            response: 'Các món đang bán có giá từ ' + formatMoney(data.data.min_price) + ' đến ' + formatMoney(data.data.max_price) + '.',
            keyword: 'gia',
            type: 'api_price'
        };
    };

    const getPromotionsResponse = async (apiBase) => {
        const data = await requestJson(apiBase + '?action=get_promotions');

        if (!data.success || data.data.length === 0) {
            return { response: 'Hiện tại chưa có khuyến mãi mới. Bạn có thể xem thực đơn để chọn món đang bán nhé.', keyword: 'khuyen mai', type: 'api_promo' };
        }

        let reply = 'Khuyến mãi hiện có:\n';
        data.data.forEach((post, index) => {
            reply += (index + 1) + '. ' + post.tenbaiviet + '\n';
        });

        return { response: reply, keyword: 'khuyen mai', type: 'api_promo' };
    };

    const getRecommendationFallbackResponse = async (apiBase, normalizedMessage) => {
        const products = await getProducts(apiBase);

        if (products.length === 0) {
            return null;
        }

        const wantsCheap = hasKeyword(normalizedMessage, ['re', 'duoi', 'it tien', 'hoc sinh', 'sinh vien']);
        const wantsLight = hasKeyword(normalizedMessage, ['nhe', 'an nhe', 'mon nhe']);
        const maxPrice = normalizedMessage.match(/duoi\s+(\d+)/)?.[1];
        const priceLimit = maxPrice ? Number(maxPrice) * (Number(maxPrice) < 1000 ? 1000 : 1) : (wantsCheap ? 50000 : Infinity);

        let suggestions = products
            .filter((product) => Number(product.soluong || 0) > 0)
            .map((product) => ({
                ...product,
                giasp: Number(product.giasp || 0),
                soluong: Number(product.soluong || 0),
                normalizedName: normalizeText(product.tensanpham || ''),
                normalizedSummary: normalizeText(product.tomtat || '')
            }));

        if (Number.isFinite(priceLimit)) {
            suggestions = suggestions.filter((product) => product.giasp <= priceLimit);
        }

        if (wantsLight) {
            const lightProducts = suggestions.filter((product) => {
                return ['salad', 'nuoc', 'tra', 'pepsi', '7up', 'aquafina', 'snack', 'sup', 'nhe'].some((keyword) => {
                    return product.normalizedName.includes(keyword) || product.normalizedSummary.includes(keyword);
                });
            });

            if (lightProducts.length > 0) {
                suggestions = lightProducts;
            }
        }

        suggestions.sort((first, second) => first.giasp - second.giasp || second.soluong - first.soluong);

        if (suggestions.length === 0) {
            return {
                response: 'Tôi chưa thấy món nào khớp điều kiện đó. Bạn thử nới khoảng giá hoặc hỏi theo danh mục món nhé.',
                keyword: 'goi y',
                type: 'api_products'
            };
        }

        let reply = 'Tôi gợi ý bạn chọn:\n';
        suggestions.slice(0, 3).forEach((product, index) => {
            reply += (index + 1) + '. ' + product.tensanpham + ' - ' + formatMoney(product.giasp) + ', còn ' + product.soluong + ' phần\n';
        });
        reply += 'Bạn có thể nói "đặt 2 phần tên món" để thêm nhanh vào giỏ hàng.';

        return { response: reply, keyword: 'goi y', type: 'api_products' };
    };

    const getStockResponse = async (apiBase, message) => {
        const products = await getProducts(apiBase);
        const product = resolveProduct(products, message);
        if (!product) {
            return null;
        }

        rememberProduct(product);

        if (Number(product.soluong || 0) > 0) {
            return { response: product.tensanpham + ' còn ' + product.soluong + ' phần.', keyword: 'con hang', type: 'api_stock' };
        }

        return { response: product.tensanpham + ' đã hết hàng.', keyword: 'het hang', type: 'api_stock' };
    };

    const getProductInfoResponse = async (apiBase, message) => {
        const products = await getProducts(apiBase);
        const product = resolveProduct(products, message);

        if (!product) {
            return null;
        }

        rememberProduct(product);

        const stock = Number(product.soluong || 0);
        const summary = String(product.tomtat || '').trim();
        const availability = stock > 0
            ? 'hiện còn ' + stock + ' phần'
            : 'hiện đã hết hàng';
        const detail = summary !== ''
            ? '\nMô tả: ' + summary
            : '';

        return {
            response:
                product.tensanpham + ' thuộc danh mục ' + (product.tendanhmuc || 'thực đơn') + '.\n' +
                'Giá: ' + formatMoney(product.giasp) + ', ' + availability + '.' +
                detail + '\n' +
                'Bạn có thể hỏi giá, tồn kho hoặc nói "đặt 2 phần ' + product.tensanpham + '" để mình thêm vào giỏ hàng.',
            keyword: normalizeText(product.tensanpham),
            type: 'api_products'
        };
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

    const getAiResponse = async (apiBase, message) => {
        const data = await fetch(apiBase + '?action=ai_chat', {
            method: 'POST',
            headers: { 'Content-Type': 'application/json' },
            body: JSON.stringify({ message })
        }).then((response) => response.json());

        if (!data.success || !data.data?.response) {
            return null;
        }

        return {
            response: data.data.response,
            keyword: data.data.matched_keyword || 'gemini',
            type: data.data.response_type || 'ai_gemini'
        };
    };

    const getBotResponse = async (message, apiBase) => {
        try {
            const normalizedMessage = normalizeText(message);

            const cartResponse = await getCartAddResponse(apiBase, message);
            if (cartResponse) {
                return cartResponse;
            }

            const staticResponse = getStaticResponse(normalizedMessage);
            if (staticResponse) {
                return staticResponse;
            }

            if (isNewArrivalRequest(normalizedMessage)) {
                return await getNewProductsResponse(apiBase);
            }

            if (!isGenericStockQuestion(normalizedMessage) && isStockRequest(normalizedMessage)) {
                const stockResponse = await getStockResponse(apiBase, message);
                if (stockResponse) {
                    return stockResponse;
                }
            }

            if (isPriceRequest(normalizedMessage)) {
                return await getPriceResponse(apiBase, message);
            }

            const categoryResponse = await getCategoryProductsResponse(apiBase, message);
            if (categoryResponse) {
                return categoryResponse;
            }

            const productInfoResponse = await getProductInfoResponse(apiBase, message);
            if (productInfoResponse) {
                return productInfoResponse;
            }

            if (isRecommendationRequest(normalizedMessage)) {
                const fallbackRecommendation = await getRecommendationFallbackResponse(apiBase, normalizedMessage);
                if (fallbackRecommendation) {
                    return fallbackRecommendation;
                }

                const aiResponse = await getAiResponse(apiBase, message);
                if (aiResponse) {
                    return aiResponse;
                }
            }

            if (hasKeyword(normalizedMessage, ['thuc don', 'menu', 'mon an', 'co gi', 'danh sach'])) {
                return await getProductsResponse(apiBase);
            }

            if (hasKeyword(normalizedMessage, ['khuyen mai', 'giam gia', 'uu dai', 'sale'])) {
                return await getPromotionsResponse(apiBase);
            }

            const aiResponse = await getAiResponse(apiBase, message);
            if (aiResponse) {
                return aiResponse;
            }

            return {
                response: 'Tôi chưa hiểu rõ ý bạn. Bạn có thể hỏi: "Thực đơn có gì?", "Giá Pepsi bao nhiêu?", "Còn gà rán không?", hoặc "Tôi muốn đặt 2 phần 7Up Lon".',
                keyword: '',
                type: 'fallback'
            };
        } catch (error) {
            return { response: 'Không thể kết nối tới server. Bạn thử lại sau nhé.', keyword: '', type: 'error' };
        }
    };

    return {
        getBotResponse
    };
})();
