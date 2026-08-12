window.FastFoodChatbotUtils = (() => {
    'use strict';

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

    const escapeRegExp = (value) => {
        return String(value || '').replace(/[.*+?^${}()|[\]\\]/g, '\\$&');
    };

    const formatMoney = (value) => {
        return Number(value || 0).toLocaleString('vi-VN') + 'đ';
    };

    const textTokens = (normalizedText) => {
        return new Set(String(normalizedText || '').split(' ').filter(Boolean));
    };

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
            return candidateTokens.some((candidateToken) => {
                return tokenMatches(sourceToken, candidateToken);
            });
        });
    };

    const hasKeyword = (normalizedMessage, keywords) => {
        return keywords.some((keyword) => {
            const normalizedKeyword = normalizeText(keyword);

            if (!normalizedKeyword) {
                return false;
            }

            const pattern = new RegExp(
                '(?:^|\\s)' + escapeRegExp(normalizedKeyword) + '(?:$|\\s)'
            );

            return pattern.test(normalizedMessage);
        });
    };

    const parseQuantity = (normalizedMessage) => {
        const match = String(normalizedMessage || '').match(
            /(?:^|\s)(\d{1,6})(?:\s+phan|\s+suat|\s+mon|\s+ly|\s+lon|\s+cai|\s|$)/
        );

        if (!match) {
            return 1;
        }

        return Math.max(1, Number(match[1]));
    };

    return Object.freeze({
        normalizeText,
        formatMoney,
        textTokens,
        countMatchedTokens,
        hasKeyword,
        parseQuantity
    });
})();
