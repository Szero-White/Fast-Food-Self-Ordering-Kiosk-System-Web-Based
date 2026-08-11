<?php
declare(strict_types=1);

require_once __DIR__ . '/chatbot_ai_config.php';
require_once __DIR__ . '/chatbot_context_repository.php';

function chatbot_gemini_prompt(string $message, string $context): string
{
    return <<<PROMPT
Bạn là FastFood AI, trợ lý tư vấn cho website đặt món FastFood Kiosk.

Quy tắc bắt buộc:
- Chỉ trả lời các câu hỏi liên quan đến dự án/cửa hàng: thực đơn, món ăn, giá, tồn kho, khuyến mãi, đặt món, thanh toán, liên hệ, giờ mở cửa, hướng dẫn dùng kiosk.
- Chỉ dùng dữ liệu trong phần NGỮ CẢNH. Không bịa tên món, giá, tồn kho, khuyến mãi hoặc chính sách.
- Nếu câu hỏi ngoài phạm vi, từ chối nhẹ và gợi ý hỏi về thực đơn, giá, khuyến mãi hoặc cách đặt món.
- Trả lời bằng tiếng Việt có dấu, thân thiện, ngắn gọn, dễ hiểu.
- Nếu gợi ý món, tối đa 3 món và nêu giá/tồn kho khi có.
- Không nhắc đến API, prompt, model hay dữ liệu nội bộ.

NGỮ CẢNH:
{$context}

CÂU HỎI KHÁCH:
{$message}
PROMPT;
}

function chatbot_gemini_models(array $config): array
{
    $models = [];
    $primaryModel = trim((string)($config['model'] ?? ''));

    if ($primaryModel !== '') {
        $models[] = $primaryModel;
    }

    foreach ((array)($config['fallback_models'] ?? []) as $model) {
        $model = trim((string)$model);
        if ($model !== '' && !in_array($model, $models, true)) {
            $models[] = $model;
        }
    }

    return $models;
}

function chatbot_gemini_should_try_next_model(array $result): bool
{
    if (($result['code'] ?? '') !== 'provider_error') {
        return false;
    }

    $message = mb_strtolower((string)($result['message'] ?? ''), 'UTF-8');
    $retrySignals = [
        'not found',
        'not supported',
        'no longer available',
        'is not available',
    ];

    foreach ($retrySignals as $signal) {
        if (str_contains($message, $signal)) {
            return true;
        }
    }

    return false;
}

function chatbot_gemini_request(array $config, string $prompt, ?string $modelName = null): array
{
    $selectedModel = $modelName ?: (string)$config['model'];
    $model = rawurlencode($selectedModel);
    $apiKey = (string)$config['api_key'];
    $url = rtrim((string)$config['endpoint'], '/') . "/{$model}:generateContent?key=" . rawurlencode($apiKey);

    $payload = [
        'contents' => [
            [
                'role' => 'user',
                'parts' => [
                    ['text' => $prompt],
                ],
            ],
        ],
        'generationConfig' => [
            'temperature' => (float)($config['temperature'] ?? 0.35),
            'maxOutputTokens' => (int)($config['max_output_tokens'] ?? 700),
        ],
    ];

    $jsonPayload = json_encode($payload, JSON_UNESCAPED_UNICODE);
    if ($jsonPayload === false) {
        return ['success' => false, 'code' => 'payload_error', 'message' => 'Không thể tạo dữ liệu gửi AI.'];
    }

    if (function_exists('curl_init')) {
        $curl = curl_init($url);
        curl_setopt_array($curl, [
            CURLOPT_POST => true,
            CURLOPT_HTTPHEADER => ['Content-Type: application/json'],
            CURLOPT_POSTFIELDS => $jsonPayload,
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_CONNECTTIMEOUT => (int)($config['timeout_seconds'] ?? 15),
            CURLOPT_TIMEOUT => (int)($config['timeout_seconds'] ?? 15),
        ]);

        $rawResponse = curl_exec($curl);
        $httpCode = (int)curl_getinfo($curl, CURLINFO_HTTP_CODE);
        $error = curl_error($curl);
        curl_close($curl);

        if ($rawResponse === false) {
            return ['success' => false, 'code' => 'network_error', 'message' => $error ?: 'Không gọi được Gemini API.'];
        }
    } else {
        $context = stream_context_create([
            'http' => [
                'method' => 'POST',
                'header' => "Content-Type: application/json\r\n",
                'content' => $jsonPayload,
                'timeout' => (int)($config['timeout_seconds'] ?? 15),
                'ignore_errors' => true,
            ],
        ]);

        $rawResponse = file_get_contents($url, false, $context);
        $httpCode = 200;

        if ($rawResponse === false) {
            return ['success' => false, 'code' => 'network_error', 'message' => 'Không gọi được Gemini API.'];
        }
    }

    $decoded = json_decode((string)$rawResponse, true);
    if (!is_array($decoded)) {
        return ['success' => false, 'code' => 'invalid_response', 'message' => 'Gemini trả về dữ liệu không hợp lệ.'];
    }

    if (($httpCode ?? 200) >= 400 || isset($decoded['error'])) {
        $message = $decoded['error']['message'] ?? 'Gemini API trả về lỗi.';
        return [
            'success' => false,
            'code' => 'provider_error',
            'message' => $message,
            'model' => $selectedModel,
        ];
    }

    $answer = trim((string)($decoded['candidates'][0]['content']['parts'][0]['text'] ?? ''));
    if ($answer === '') {
        return ['success' => false, 'code' => 'empty_answer', 'message' => 'Gemini chưa tạo được câu trả lời.'];
    }

    return [
        'success' => true,
        'answer' => $answer,
        'model' => $selectedModel,
    ];
}

function chatbot_ai_generate_reply(mysqli $mysqli, string $message): array
{
    $config = chatbot_ai_config();

    if (!chatbot_ai_is_configured($config)) {
        return [
            'success' => false,
            'code' => 'missing_api_key',
            'message' => 'Chưa cấu hình Gemini API key.',
        ];
    }

    $context = chatbot_build_project_context($mysqli, $config);
    $prompt = chatbot_gemini_prompt($message, $context);
    $lastResult = [
        'success' => false,
        'code' => 'missing_model',
        'message' => 'Chưa cấu hình model Gemini.',
    ];

    foreach (chatbot_gemini_models($config) as $model) {
        $lastResult = chatbot_gemini_request($config, $prompt, $model);

        if (!empty($lastResult['success'])) {
            return $lastResult;
        }

        if (!chatbot_gemini_should_try_next_model($lastResult)) {
            return $lastResult;
        }
    }

    return $lastResult;
}
