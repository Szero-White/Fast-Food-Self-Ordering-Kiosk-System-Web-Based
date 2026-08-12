window.FastFoodChatbotApi = (() => {
    'use strict';

    let productCache = null;

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

    const addToCart = async (apiBase, productId, quantity) => {
        return requestJson(apiBase + '?action=add_to_cart', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json'
            },
            body: JSON.stringify({
                product_id: Number(productId),
                quantity: Number(quantity)
            })
        });
    };

    const getPriceRange = async (apiBase) => {
        return requestJson(apiBase + '?action=get_price_range');
    };

    const getPromotions = async (apiBase) => {
        return requestJson(apiBase + '?action=get_promotions');
    };

    const askGemini = async (apiBase, message) => {
        try {
            const data = await requestJson(apiBase + '?action=ai_chat', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json'
                },
                body: JSON.stringify({ message })
            });

            if (!data.success || !data.data?.response) {
                return null;
            }

            return data.data;
        } catch (error) {
            // AI là lớp tư vấn. Nếu provider/network lỗi, router sẽ dùng fallback database.
            console.warn('Gemini request failed:', error);
            return null;
        }
    };

    const clearProductCache = () => {
        productCache = null;
    };

    return Object.freeze({
        getProducts,
        addToCart,
        getPriceRange,
        getPromotions,
        askGemini,
        clearProductCache
    });
})();
