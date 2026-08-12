window.FastFoodChatbotResponseService = (() => {
    'use strict';

    const {
        normalizeText
    } = window.FastFoodChatbotUtils;

    const {
        isRecommendationRequest,
        isNewArrivalRequest,
        isStockRequest,
        isPriceRequest,
        isMenuRequest,
        isPromotionRequest
    } = window.FastFoodChatbotIntents;

    const {
        getCartAddResponse,
        getStaticResponse,
        getRecommendationFallbackResponse,
        getNewProductsResponse,
        getStockResponse,
        getPriceResponse,
        getCategoryProductsResponse,
        getProductInfoResponse,
        getProductsResponse,
        getPromotionsResponse,
        getAiResponse
    } = window.FastFoodChatbotResponses;

    const fallbackResponse = () => ({
        response: 'Tôi chưa hiểu rõ ý bạn. Bạn có thể hỏi: "Thực đơn có gì?", "Giá Pepsi bao nhiêu?", "Còn gà rán không?", hoặc "Tôi muốn đặt 2 phần 7Up Lon".',
        keyword: '',
        type: 'fallback'
    });

    const connectionErrorResponse = () => ({
        response: 'Không thể kết nối tới server. Bạn thử lại sau nhé.',
        keyword: '',
        type: 'error'
    });

    const getBotResponse = async (message, apiBase) => {
        try {
            const normalizedMessage = normalizeText(message);

            // 1. Hành động đặt món rõ ràng luôn ưu tiên rule/database.
            const cartResponse = await getCartAddResponse(apiBase, message);
            if (cartResponse) {
                return cartResponse;
            }

            // 2. Các câu thông tin cố định: chào hỏi, liên hệ, giờ mở cửa...
            const staticResponse = getStaticResponse(normalizedMessage);
            if (staticResponse) {
                return staticResponse;
            }

            // 3. Câu tư vấn/gợi ý tự nhiên: Gemini trước, database chỉ fallback.
            // Đây là điểm quan trọng để tránh trường hợp rule "ăn" mất câu hỏi AI.
            if (isRecommendationRequest(normalizedMessage)) {
                const aiRecommendation = await getAiResponse(apiBase, message);

                if (aiRecommendation) {
                    return aiRecommendation;
                }

                const fallbackRecommendation =
                    await getRecommendationFallbackResponse(
                        apiBase,
                        normalizedMessage
                    );

                if (fallbackRecommendation) {
                    return fallbackRecommendation;
                }
            }

            // 4. Các câu truy vấn dữ liệu cụ thể dùng database để trả lời chính xác.
            if (isNewArrivalRequest(normalizedMessage)) {
                return getNewProductsResponse(apiBase);
            }

            if (isStockRequest(normalizedMessage)) {
                const stockResponse = await getStockResponse(apiBase, message);

                if (stockResponse) {
                    return stockResponse;
                }
            }

            if (isPriceRequest(normalizedMessage)) {
                return getPriceResponse(apiBase, message);
            }

            const categoryResponse = await getCategoryProductsResponse(
                apiBase,
                message
            );

            if (categoryResponse) {
                return categoryResponse;
            }

            const productInfoResponse = await getProductInfoResponse(
                apiBase,
                message
            );

            if (productInfoResponse) {
                return productInfoResponse;
            }

            if (isMenuRequest(normalizedMessage)) {
                return getProductsResponse(apiBase);
            }

            if (isPromotionRequest(normalizedMessage)) {
                return getPromotionsResponse(apiBase);
            }

            // 5. Câu mở ngoài các rule trên vẫn cho Gemini cơ hội xử lý.
            const aiResponse = await getAiResponse(apiBase, message);

            if (aiResponse) {
                return aiResponse;
            }

            return fallbackResponse();
        } catch (error) {
            console.error('Chatbot response error:', error);
            return connectionErrorResponse();
        }
    };

    return Object.freeze({
        getBotResponse
    });
})();
