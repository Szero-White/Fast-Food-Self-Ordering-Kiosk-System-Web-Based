<?php
declare(strict_types=1);

function chatbot_ai_config(): array
{
    $localSecret = __DIR__ . '/chatbot_ai_secret.php';
    $localApiKey = '';

    if (is_file($localSecret)) {
        $secretConfig = require $localSecret;
        if (is_array($secretConfig)) {
            $localApiKey = (string)($secretConfig['GEMINI_API_KEY'] ?? '');
        }
    }

    return [
        'enabled' => true,
        'provider' => 'gemini',

        // Production: prefer setting GEMINI_API_KEY in hosting environment variables.
        // Local fallback: create config/chatbot_ai_secret.php from the example file.
        'api_key' => getenv('GEMINI_API_KEY') ?: $localApiKey,

        'model' => getenv('GEMINI_MODEL') ?: 'gemini-3.5-flash-lite',
        'fallback_models' => [
            'gemini-3.6-flash',
            'gemini-3.5-flash',
            'gemini-2.5-flash',
        ],
        'endpoint' => 'https://generativelanguage.googleapis.com/v1beta/models',
        'timeout_seconds' => 15,
        'temperature' => 0.35,
        'max_output_tokens' => 700,
        'max_products' => 14,
        'max_promotions' => 6,
    ];
}

function chatbot_ai_is_configured(array $config): bool
{
    return !empty($config['enabled'])
        && ($config['provider'] ?? '') === 'gemini'
        && trim((string)($config['api_key'] ?? '')) !== '';
}
