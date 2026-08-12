window.FastFoodChatbotContext = (() => {
    'use strict';

    const LAST_PRODUCT_STORAGE_KEY = 'fastfood-chatbot-last-product';
    const CONVERSATION_CONTEXT_STORAGE_KEY = 'fastfood-chatbot-context';

    const loadConversationContext = () => {
        try {
            return JSON.parse(
                localStorage.getItem(CONVERSATION_CONTEXT_STORAGE_KEY) || '{}'
            );
        } catch (error) {
            return {};
        }
    };

    const saveConversationContext = (contextPatch) => {
        try {
            localStorage.setItem(
                CONVERSATION_CONTEXT_STORAGE_KEY,
                JSON.stringify({
                    ...loadConversationContext(),
                    ...contextPatch
                })
            );
        } catch (error) {
            // localStorage không được phép làm gián đoạn luồng chatbot.
        }
    };

    const rememberProduct = (product) => {
        if (!product || !product.id_sanpham) {
            return;
        }

        const productId = Number(product.id_sanpham);

        try {
            localStorage.setItem(
                LAST_PRODUCT_STORAGE_KEY,
                JSON.stringify({
                    id_sanpham: productId,
                    tensanpham: product.tensanpham
                })
            );

            saveConversationContext({
                lastProductId: productId,
                lastProductName: product.tensanpham,
                lastCategoryName: product.tendanhmuc || '',
                lastIntent: 'product'
            });
        } catch (error) {
            // localStorage không được phép làm gián đoạn luồng đặt món.
        }
    };

    const getRememberedProduct = (products) => {
        try {
            const context = loadConversationContext();
            const storedProduct = JSON.parse(
                localStorage.getItem(LAST_PRODUCT_STORAGE_KEY) || '{}'
            );
            const productId = Number(
                context.lastProductId || storedProduct.id_sanpham || 0
            );

            if (productId <= 0) {
                return null;
            }

            return products.find((product) => {
                return Number(product.id_sanpham) === productId;
            }) || null;
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

        return typeof context.lastCategoryName === 'string'
            ? context.lastCategoryName
            : '';
    };

    return Object.freeze({
        loadConversationContext,
        saveConversationContext,
        rememberProduct,
        getRememberedProduct,
        rememberCategory,
        getRememberedCategory
    });
})();
