<?php

namespace App\Services;

use App\Data\Qwen\Request\QwenChatbotData;
use App\Data\Qwen\Request\QwenCodeGenerationData;
use App\Data\Qwen\Request\QwenNLPData;
use App\Data\Qwen\Request\QwenTextSummarizationData;
use App\Http\Resources\Qwen\QwenNLPResource;
use App\Traits\QwenTrait;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Http;

class QwenService
{
  use QwenTrait;

  protected $client;

  public function __construct()
  {
    $token = config('services.qwen.api_key');

    if (empty($token)) {
      throw new \Exception('Qwen API key not configured.');
    }

    $this->client = Http::withHeaders([
      'Content-Type' => 'application/json',
      'Authorization' => 'Bearer ' . $token,
    ])
      ->timeout(0)
      ->connectTimeout(15)
      ->baseUrl(config('services.qwen.base_url'));
  }

  /**
   * Qwen NLP
   *
   * @param QwenNLPData $data
   * @return QwenNLPResource
   */
  public function nlp(QwenNLPData $data): QwenNLPResource
  {
    $req =  [
      'model' => $data->model,
      'system' => config('QwenAI.system_prompts.nlp'),
      'max_tokens' => $data->max_tokens,
      'temperature' => $data->temperature,
      'messages' => [
        [
          'role' => 'user',
          'content' => $data->prompt,
        ],
      ]
    ];

    $completion = $this->client->post('/chat/completions', $req);

    $result = $completion->json();

    return QwenNLPResource::make($result);
  }

  /**
   * Qwen Code Generation
   *
   * @param QwenCodeGenerationData $data
   * @return QwenNLPResource
   */
  public function codeGeneration(QwenCodeGenerationData $data): QwenNLPResource
  {
    $messages = [
      [
        'role' => 'user',
        'content' => [],
      ]
    ];

    $messages[0]['content'][] = [
      'type' => 'text',
      'text' => $data->prompt
    ];

    if (!empty($data->attachments)) {
      foreach ($data->attachments as $attachment) {
        $messages[0]['content'][] = $this->prepareAttachment($attachment);
      }
    }

    $req = [
      'model' => $data->model,
      'system' => config('QwenAI.system_prompts.code_generation'),
      'max_tokens' => $data->max_tokens,
      'temperature' => $data->temperature,
      'messages' => $messages,
    ];

    $completion = $this->client->post('/chat/completions', $req);

    $result = $completion->json();

    return QwenNLPResource::make($result);
  }

  /**
   * Qwen Text Summarization
   *
   * @param QwenTextSummarizationData $data
   * @return QwenNLPResource
   */
  public function textSummarization(QwenTextSummarizationData $data): QwenNLPResource
  {
    $req = [
      'model' => $data->model,
      'system' => config('QwenAI.system_prompts.text_summarization'),
      'max_tokens' => $data->max_tokens,
      'temperature' => $data->temperature,
      'messages' => [
        [
          'role' => 'user',
          'content' => $data->text,
        ],
      ],
      'n' => $data->text_length,
    ];

    $completion = $this->client->post('/chat/completions', $req);

    $result = $completion->json();

    return QwenNLPResource::make($result);
  }

  /**
   * Qwen Chatbot
   *
   * @param QwenChatbotData $data
   * @return QwenNLPResource
   */
  public function chatbot(QwenChatbotData $data): QwenNLPResource
  {
    $req = [
      'model' => $data->model,
      'system' => config('QwenAI.system_prompts.chatbot'),
      'max_tokens' => $data->max_tokens,
      'temperature' => $data->temperature,
      'messages' => $data->conversation_history,
    ];

    $completion = $this->client->post('/chat/completions', $req);

    $result = $completion->json();

    return QwenNLPResource::make($result);
  }
}
