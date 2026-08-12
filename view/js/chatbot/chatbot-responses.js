window.FastFoodChatbotResponses = (() => {
    'use strict';

    const {
        normalizeText,
        formatMoney,
        hasKeyword,
        parseQuantity
    } = window.FastFoodChatbotUtils;

    const {
        rememberProduct,
        rememberCategory,
        getRememberedCategory
    } = window.FastFoodChatbotContext;

    const {
        isContextReference,
        isOrderRequest
    } = window.FastFoodChatbotIntents;

    const {
        findBestCategory,
        resolveProduct
    } = window.FastFoodChatbotCatalog;

    const {
        getProducts,
        addToCart,
        getPriceRange,
        getPromotions,
        askGemini
    } = window.FastFoodChatbotApi;

    const staticResponses = [
        {
            keywords: ['chao', 'xin chao', 'hello', 'hi'],
            response: 'Xin chào! Tôi là FastFood AI. Hôm nay bạn muốn chọn món, xem giá hay hỏi khuyến mãi nào?'
        },
        {
            keywords: ['ban ten gi', 'ten cua ban', 'ban la ai', 'la ai'],
            response: 'Tôi là FastFood AI, trợ lý đặt món của FastFood Kiosk.'
        },
        {
            keywords: ['cam on', 'thanks'],
            response: 'Không có gì! Chúc bạn chọn được món thật ngon.'
        },
        {
            keywords: ['tam biet', 'bye'],
            response: 'Tạm biệt! Hẹn gặp lại bạn ở FastFood.'
        },
        {
            keywords: ['tro giup', 'huong dan', 'giup toi', 'giup minh'],
            response: 'Bạn có thể hỏi tôi về thực đơn, giá món, tồn kho, khuyến mãi, giờ mở cửa hoặc nói thẳng: "Tôi muốn đặt 2 phần Pepsi".'
        },
        {
            keywords: ['dia chi', 'o dau'],
            response: 'Cửa hàng tại Quận 7, Thành phố Hồ Chí Minh. Hotline: 1900 6099.'
        },
        {
            keywords: ['lien he', 'sdt', 'so dien thoai', 'hotline'],
            response: 'Hotline: 1900 6099 | Địa chỉ: Quận 7, TP.HCM | Email: congtoan2k4@gmail.com'
        },
        {
            keywords: ['gio mo cua', 'mo cua'],
            response: 'Cửa hàng mở cửa từ 09:00 đến 22:00 mỗi ngày.'
        },
        {
            keywords: ['dong cua'],
            response: 'Cửa hàng đóng cửa lúc 22:00. Bạn ghé trước giờ đó để chọn món nhé.'
        },
        {
            keywords: ['ship', 'giao hang'],
            response: 'Hiện tại kiosk phục vụ đặt món tại cửa hàng. Bạn có thể chọn món, thanh toán rồi nhận món tại quầy.'
        },
        {
            keywords: ['mang ve'],
            response: 'Bạn có thể đặt món tại kiosk và mang về. Hệ thống sẽ lưu đơn để quầy chuẩn bị món.'
        }
    ];

    const getStaticResponse = (normalizedMessage) => {
        for (const item of staticResponses) {
            const matchedKeyword = item.keywords.find((keyword) => {
                return hasKeyword(normalizedMessage, [keyword]);
            });

            if (matchedKeyword) {
                return {
                    response: item.response,
                    keyword: matchedKeyword,
                    type: 'static'
                };
            }
        }

        return null;
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

        const addResult = await addToCart(
            apiBase,
            Number(product.id_sanpham),
            quantity
        );

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
            return {
                response: 'Hiện tại chưa có món nào đang bán trong thực đơn.',
                keyword: 'thuc don',
                type: 'api_products'
            };
        }

        let reply = 'Thực đơn hiện có ' + products.length + ' món đang bán:\n';

        products.slice(0, 6).forEach((product, index) => {
            reply += (index + 1) + '. ' + product.tensanpham
                + ' - ' + formatMoney(product.giasp)
                + ', còn ' + product.soluong + ' phần\n';
        });

        if (products.length > 6) {
            reply += 'Bạn có thể hỏi tên món cụ thể để xem giá hoặc nói "đặt 2 phần tên món".';
        }

        return {
            response: reply,
            keyword: 'thuc don',
            type: 'api_products'
        };
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

        let reply = 'Các món được cập nhật gần đây và đang còn hàng:\n';

        newProducts.forEach((product, index) => {
            reply += (index + 1) + '. ' + product.tensanpham
                + ' - ' + formatMoney(product.giasp)
                + ', còn ' + product.soluong + ' phần\n';
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
            || (
                wantsCategoryList && isContextReference(normalizedMessage)
                    ? getRememberedCategory()
                    : ''
            );

        if (!categoryName) {
            return null;
        }

        rememberCategory(categoryName, 'category_products');

        const categoryProducts = products
            .filter((product) => {
                return normalizeText(product.tendanhmuc) === normalizeText(categoryName);
            })
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
            reply += (index + 1) + '. ' + product.tensanpham
                + ' - ' + formatMoney(product.giasp)
                + ', còn ' + product.soluong + ' phần\n';
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

        const data = await getPriceRange(apiBase);

        if (!data.success) {
            return {
                response: 'Chưa lấy được khoảng giá lúc này. Bạn thử lại sau nhé.',
                keyword: '',
                type: 'error'
            };
        }

        return {
            response: 'Các món đang bán có giá từ '
                + formatMoney(data.data.min_price)
                + ' đến '
                + formatMoney(data.data.max_price)
                + '.',
            keyword: 'gia',
            type: 'api_price'
        };
    };

    const getPromotionsResponse = async (apiBase) => {
        const data = await getPromotions(apiBase);

        if (!data.success || !Array.isArray(data.data) || data.data.length === 0) {
            return {
                response: 'Hiện tại chưa có khuyến mãi mới. Bạn có thể xem thực đơn để chọn món đang bán nhé.',
                keyword: 'khuyen mai',
                type: 'api_promo'
            };
        }

        let reply = 'Khuyến mãi hiện có:\n';

        data.data.forEach((post, index) => {
            reply += (index + 1) + '. ' + post.tenbaiviet + '\n';
        });

        return {
            response: reply,
            keyword: 'khuyen mai',
            type: 'api_promo'
        };
    };

    const getRecommendationFallbackResponse = async (apiBase, normalizedMessage) => {
        const products = await getProducts(apiBase);

        if (products.length === 0) {
            return null;
        }

        const wantsCheap = hasKeyword(normalizedMessage, [
            're',
            'duoi',
            'it tien',
            'hoc sinh',
            'sinh vien'
        ]);
        const wantsLight = hasKeyword(normalizedMessage, [
            'nhe',
            'an nhe',
            'mon nhe'
        ]);
        const maxPrice = normalizedMessage.match(/duoi\s+(\d+)/)?.[1];
        const priceLimit = maxPrice
            ? Number(maxPrice) * (Number(maxPrice) < 1000 ? 1000 : 1)
            : (wantsCheap ? 50000 : Infinity);

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
            suggestions = suggestions.filter((product) => {
                return product.giasp <= priceLimit;
            });
        }

        if (wantsLight) {
            const lightProducts = suggestions.filter((product) => {
                return [
                    'salad',
                    'nuoc',
                    'tra',
                    'pepsi',
                    '7up',
                    'aquafina',
                    'snack',
                    'sup',
                    'nhe'
                ].some((keyword) => {
                    return product.normalizedName.includes(keyword)
                        || product.normalizedSummary.includes(keyword);
                });
            });

            if (lightProducts.length > 0) {
                suggestions = lightProducts;
            }
        }

        suggestions.sort((first, second) => {
            return first.giasp - second.giasp || second.soluong - first.soluong;
        });

        if (suggestions.length === 0) {
            return {
                response: 'Tôi chưa thấy món nào khớp điều kiện đó. Bạn thử nới khoảng giá hoặc hỏi theo danh mục món nhé.',
                keyword: 'goi y',
                type: 'api_products'
            };
        }

        let reply = 'Tôi gợi ý bạn chọn:\n';

        suggestions.slice(0, 3).forEach((product, index) => {
            reply += (index + 1) + '. ' + product.tensanpham
                + ' - ' + formatMoney(product.giasp)
                + ', còn ' + product.soluong + ' phần\n';
        });

        reply += 'Bạn có thể nói "đặt 2 phần tên món" để thêm nhanh vào giỏ hàng.';

        return {
            response: reply,
            keyword: 'goi y',
            type: 'api_products'
        };
    };

    const getStockResponse = async (apiBase, message) => {
        const products = await getProducts(apiBase);
        const product = resolveProduct(products, message);

        if (!product) {
            return null;
        }

        rememberProduct(product);

        if (Number(product.soluong || 0) > 0) {
            return {
                response: product.tensanpham + ' còn ' + product.soluong + ' phần.',
                keyword: 'con hang',
                type: 'api_stock'
            };
        }

        return {
            response: product.tensanpham + ' đã hết hàng.',
            keyword: 'het hang',
            type: 'api_stock'
        };
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

    const getAiResponse = async (apiBase, message) => {
        const data = await askGemini(apiBase, message);

        if (!data) {
            return null;
        }

        return {
            response: data.response,
            keyword: data.matched_keyword || 'gemini',
            type: data.response_type || 'ai_gemini'
        };
    };

    return Object.freeze({
        getStaticResponse,
        getCartAddResponse,
        getProductsResponse,
        getNewProductsResponse,
        getCategoryProductsResponse,
        getPriceResponse,
        getPromotionsResponse,
        getRecommendationFallbackResponse,
        getStockResponse,
        getProductInfoResponse,
        getAiResponse
    });
})();
