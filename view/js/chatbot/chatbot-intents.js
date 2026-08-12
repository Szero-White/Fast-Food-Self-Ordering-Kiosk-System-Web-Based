window.FastFoodChatbotIntents = (() => {
    'use strict';

    const {
        hasKeyword,
        parseQuantity
    } = window.FastFoodChatbotUtils;

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

    const isFollowUpOrderRequest = (normalizedMessage) => {
        return parseQuantity(normalizedMessage) > 1
            && hasKeyword(normalizedMessage, ['vay', 'cho', 'lay', 'them', 'di']);
    };

    const isInformationalRequest = (normalizedMessage) => {
        return hasKeyword(normalizedMessage, [
            'goi y',
            'tu van',
            'cho toi biet',
            'cho minh biet',
            'cho toi hoi',
            'cho minh hoi',
            'cho toi xem',
            'cho minh xem',
            'nen chon',
            'nen an',
            'mon nao',
            'mon gi',
            'an gi',
            'gia',
            'bao nhieu',
            'con khong',
            'co khong',
            'thuc don',
            'menu',
            'khuyen mai',
            'giam gia',
            'uu dai',
            'xem them'
        ]);
    };

    const isOrderRequest = (normalizedMessage) => {
        if (isInformationalRequest(normalizedMessage)) {
            return false;
        }

        if (hasKeyword(normalizedMessage, [
            'dat mon',
            'mua',
            'them vao gio',
            'toi muon',
            'minh muon',
            'cho toi',
            'cho minh',
            'toi dat',
            'minh dat',
            'muon dat',
            'toi lay',
            'minh lay'
        ])) {
            return true;
        }

        // Hỗ trợ câu mệnh lệnh ngắn: "Đặt Pepsi", "Thêm 2 Pepsi", "Lấy 1 Burger".
        // Không dùng từ khóa đơn "cho" vì dễ bắt nhầm các câu hỏi tự nhiên.
        return /^(dat|them|lay)\s+/.test(normalizedMessage)
            && !/^dat\s+khong(?:\s|$)/.test(normalizedMessage);
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
            'an nhe',
            'mon nhe',
            'an no',
            'no lau',
            're',
            'duoi',
            'it tien',
            'hoc sinh',
            'sinh vien',
            'ngon',
            'hap dan',
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

    const isMenuRequest = (normalizedMessage) => {
        return hasKeyword(normalizedMessage, [
            'thuc don',
            'menu',
            'mon an',
            'co gi',
            'danh sach'
        ]);
    };

    const isPromotionRequest = (normalizedMessage) => {
        return hasKeyword(normalizedMessage, [
            'khuyen mai',
            'giam gia',
            'uu dai',
            'sale'
        ]);
    };

    return Object.freeze({
        isContextReference,
        isFollowUpOrderRequest,
        isInformationalRequest,
        isOrderRequest,
        isRecommendationRequest,
        isNewArrivalRequest,
        isStockRequest,
        isPriceRequest,
        isMenuRequest,
        isPromotionRequest
    });
})();
