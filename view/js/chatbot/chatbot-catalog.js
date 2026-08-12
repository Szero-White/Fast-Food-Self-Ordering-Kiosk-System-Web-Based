window.FastFoodChatbotCatalog = (() => {
    'use strict';

    const {
        normalizeText,
        textTokens,
        countMatchedTokens
    } = window.FastFoodChatbotUtils;

    const {
        getRememberedProduct
    } = window.FastFoodChatbotContext;

    const {
        isContextReference,
        isFollowUpOrderRequest
    } = window.FastFoodChatbotIntents;

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

    const productScore = (product, normalizedMessage) => {
        const normalizedName = normalizeText(product.tensanpham);

        if (!normalizedName) {
            return 0;
        }

        if (normalizedMessage.includes(normalizedName)) {
            return 100 + normalizedName.length;
        }

        const messageTokens = [...textTokens(normalizedMessage)]
            .filter((token) => {
                return token.length >= 2 && !ignoredProductTokens.has(token);
            });

        const productTokens = normalizedName
            .split(' ')
            .filter((token) => {
                return token.length >= 2 && !ignoredProductTokens.has(token);
            });

        const matchedTokens = countMatchedTokens(productTokens, messageTokens);

        if (matchedTokens.length === 0) {
            return 0;
        }

        const coverage = matchedTokens.length / productTokens.length;

        return matchedTokens.length * 10
            + matchedTokens.join('').length
            + Math.round(coverage * 20);
    };

    const findBestProduct = (products, message) => {
        const normalizedMessage = normalizeText(message);

        return products
            .map((product) => ({
                product,
                score: productScore(product, normalizedMessage)
            }))
            .filter((entry) => entry.score > 0)
            .sort((first, second) => second.score - first.score)[0]?.product || null;
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
            .filter((token) => {
                return token.length >= 2 && !ignoredCategoryTokens.has(token);
            });

        const categoryTokens = normalizedCategory
            .split(' ')
            .filter((token) => {
                return token.length >= 2 && !ignoredCategoryTokens.has(token);
            });

        const matchedTokens = countMatchedTokens(categoryTokens, messageTokens);

        if (matchedTokens.length === 0) {
            return 0;
        }

        const coverage = matchedTokens.length / categoryTokens.length;

        return matchedTokens.length * 10
            + matchedTokens.join('').length
            + Math.round(coverage * 10);
    };

    const findBestCategory = (products, message) => {
        const normalizedMessage = normalizeText(message);
        const categories = [
            ...new Set(products.map((product) => product.tendanhmuc).filter(Boolean))
        ];

        return categories
            .map((category) => ({
                category,
                score: categoryScore(category, normalizedMessage)
            }))
            .filter((entry) => entry.score > 0)
            .sort((first, second) => second.score - first.score)[0]?.category || null;
    };

    const resolveProduct = (products, message) => {
        const normalizedMessage = normalizeText(message);

        return findBestProduct(products, message)
            || (
                isContextReference(normalizedMessage)
                || isFollowUpOrderRequest(normalizedMessage)
                    ? getRememberedProduct(products)
                    : null
            );
    };

    return Object.freeze({
        findBestProduct,
        findBestCategory,
        resolveProduct
    });
})();
