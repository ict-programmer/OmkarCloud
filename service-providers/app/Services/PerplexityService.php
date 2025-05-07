<?php

namespace App\Services;

use App\Data\Request\Perplexity\AcademicResearchData;
use App\Data\Request\Perplexity\AiSearchData;
use App\Data\Request\Perplexity\CodeAssistantData;
use App\Traits\OpenAIChatTrait;
use OpenAI;

class PerplexityService
{
    use OpenAIChatTrait;

    public OpenAI\Client $client;

    public function __construct()
    {
        $this->client = OpenAI::factory()
            ->withApiKey(config('services.perplexity.api_key'))
            ->withBaseUri('https://api.perplexity.ai')
            ->make();
    }

    public function aiSearch(AiSearchData $data): OpenAI\Responses\Chat\CreateResponse
    {

        $predefinedPayload = config('services.perplexity.search')[$data->search_type] ?? config('services.perplexity.search.web');

        $systemMessage = match ($data->search_type) {
            'news' => 'You are a precise news research assistant. Provide factual, up-to-date information citing only trusted news sources in search_domain_filter. Present key information first, followed by relevant context and multiple perspectives. Include source URLs for fact verification. Avoid opinions, speculation, and outdated information.',
            default => "You are a comprehensive web research assistant searching the entire internet. Provide the most accurate, up-to-date information from diverse, reliable sources. Present key findings first with supporting details. Include source URLs for verification. Prioritize authoritative sources but consider varied perspectives. Be thorough yet concise, focusing on answering the user's specific query."
        };

        $messages = [
            [
                'role' => 'system',
                'content' => $systemMessage,
            ],
            [
                'role' => 'user',
                'content' => $data->query,
            ],
        ];

        return $this->client->chat()->create(array_merge($predefinedPayload, [
            'model' => $data->model,
            'messages' => $messages,
            'top_k' => $data->max_results,
            'temperature' => $data->temperature,
        ]));
    }

    public function academicResearch(AcademicResearchData $data): OpenAI\Responses\Chat\CreateResponse
    {
        $predefinedPayload = config('services.perplexity.academic');

        $systemMessage = 'You are a scholarly research assistant with access to academic databases. Provide comprehensive, evidence-based information citing peer-reviewed sources. Organize responses with key findings first, followed by detailed analysis and supporting evidence. Include citation details and source URLs. Maintain academic rigor while making complex concepts accessible. Present multiple scholarly perspectives when relevant.';

        $messages = [
            [
                'role' => 'system',
                'content' => $systemMessage,
            ],
            [
                'role' => 'user',
                'content' => $data->query,
            ],
        ];

        return $this->client->chat()->create(array_merge($predefinedPayload, [
            'model' => $data->model,
            'messages' => $messages,
            'top_k' => $data->max_results,
        ]));
    }

    public function codeAssistant(CodeAssistantData $data): OpenAI\Responses\Chat\CreateResponse|array
    {
        $languagePart = $data->programming_language ? "{$data->programming_language} " : '';
        $lengthPart = $data->code_length ? "{$data->code_length} " : '';

        $systemPrompt = 'You are a professional code assistant. Always return clean, well-formatted, and commented ' .
            ($data->programming_language ?? 'code') . '. ' .
            'If applicable, include a brief explanation after the code block.';

        $userPrompt = "Write a {$lengthPart}{$languagePart}code snippet for: {$data->query}";

        return $this->client->chat()->create([
            'model' => $data->model,
            'messages' => [
                ['role' => 'system', 'content' => $systemPrompt],
                ['role' => 'user', 'content' => $userPrompt],
            ],
        ]);
    }
}
