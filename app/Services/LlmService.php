<?php
namespace App\Services;

use Illuminate\Support\Facades\Http;
use App\Models\AdminSetting;

class LlmService
{
    protected string $baseUrl;
    protected string $apiKey;
    protected string $model;

    public function __construct()
    {
        $this->baseUrl = rtrim(AdminSetting::get('parser_base_url') ?: config('parser.base_url', 'https://generativelanguage.googleapis.com/v1beta/openai'), '/');
        $this->apiKey  = AdminSetting::get('parser_api_key')  ?: config('parser.api_key', '');
        $this->model   = AdminSetting::get('parser_model')    ?: config('parser.model', 'gemini-2.0-flash');
    }

    protected function buildRequest(int $timeout = 60): \Illuminate\Http\Client\PendingRequest
    {
        $client = Http::timeout($timeout);
        // Azure OpenAI uses api-key header; standard OpenAI uses Bearer
        if (str_contains($this->baseUrl, 'azure.com')) {
            return $client->withHeaders(['api-key' => $this->apiKey]);
        }
        return $client->withToken($this->apiKey);
    }

    public function chat(array $messages, array $options = []): ?string
    {
        $timeout = (int) ($options['timeout'] ?? 60);
        unset($options['timeout']);

        $response = $this->buildRequest($timeout)
            ->post($this->baseUrl . '/chat/completions', array_merge([
                'model'    => $this->model,
                'messages' => $messages,
            ], $options));

        if (!$response->successful()) {
            \Log::error('LLM API error: ' . $response->status() . ' ' . $response->body());
            return null;
        }

        return $response->json('choices.0.message.content');
    }

    public function chatJson(array $messages, array $options = []): ?array
    {
        $content = $this->chat($messages, array_merge($options, [
            'response_format' => ['type' => 'json_object'],
        ]));

        if (!$content) return null;
        $decoded = json_decode($content, true);
        return is_array($decoded) ? $decoded : null;
    }
}
