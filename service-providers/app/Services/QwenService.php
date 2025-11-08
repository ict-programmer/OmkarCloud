<?php

namespace App\Services;

use App\Data\Qwen\Request\QwenChatbotData;
use App\Data\Qwen\Request\QwenCodeGenerationData;
use App\Data\Qwen\Request\QwenNLPData;
use App\Data\Qwen\Request\QwenTextSummarizationData;
use App\Http\Exceptions\Forbidden;
use App\Http\Resources\Qwen\QwenNLPResource;
use App\Traits\QwenTrait;
use ErrorException;
use Exception;
use Illuminate\Http\Client\ConnectionException;
use Illuminate\Http\Client\Response;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;
use stdClass;

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
    try {
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
    } catch (ConnectionException | Exception | ErrorException $e) {
      Log::error('Qwen request error: ' . json_encode($e->getMessage()));
      throw new Forbidden('Qwen request error: ' . $e->getMessage());
    }

    $result = $this->handleResponse($completion);

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
    try {
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
    } catch (ConnectionException | Exception | ErrorException $e) {
      Log::error('Qwen request error: ' . json_encode($e->getMessage()));
      throw new Forbidden('Qwen request error: ' . $e->getMessage());
    }

    $result = $this->handleResponse($completion);

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
    try {
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
    } catch (ConnectionException | Exception | ErrorException $e) {
      Log::error('Qwen request error: ' . json_encode($e->getMessage()));
      throw new Forbidden('Qwen request error: ' . $e->getMessage());
    }

    $result = $this->handleResponse($completion);

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
    try {
      $req = [
        'model' => $data->model,
        'system' => config('QwenAI.system_prompts.chatbot'),
        'max_tokens' => $data->max_tokens,
        'temperature' => $data->temperature,
        'messages' => $data->conversation_history,
      ];

      $completion = $this->client->post('/chat/completions', $req);
    } catch (ConnectionException | Exception | ErrorException $e) {
      Log::error('Qwen request error: ' . json_encode($e->getMessage()));
      throw new Forbidden('Qwen request error: ' . $e->getMessage());
    }

    $result = $this->handleResponse($completion);

    return QwenNLPResource::make($result);
  }

  /**
   * Handle response
   *
   * @param Response $response
   * @return array
   */
  public function handleResponse(Response $response): stdClass
  {
    $result = new stdClass;
    $result->status = false;
    $result->message = '';
    $result->error = null;
    $result->usage = [];

    if ($response->failed()) {
      $errorData = $response->json();
      $result->error = $errorData['error']['message'] ?? $response->body() ?? 'Unknown Qwen API error';
      Log::error('Qwen API Error: ' . $response->body());
      return $result;
    }

    $responseData = $response->json();

    $firstCandidate = $responseData['choices'][0] ?? null;
    $message = $firstCandidate['message']['content'] ?? null;


    if (empty($message)) {
      // Check if the response was stopped due to an error or content filter
      $finishReason = $firstCandidate['finish_reason'] ?? null;

      if ($finishReason === 'error' || $finishReason === 'content_filter') {
        $result->error = 'Qwen API incurred an error: ' . $response->body();
        return $result;
      }

      $result->error = 'Missing or empty response content from Qwen' . json_encode($responseData);
      return $result;
    }

    $result->status = true;
    $result->message = $message;

    // Remove thinking tokens from the response if present
    $result->message = preg_replace('/<think>.*?<\/think>/is', '', $result->message);
    $result->message = ltrim($result->message, "\n\n");


    if (preg_match('/\\b(error|sorry|unable)\\b/i', $result->message)) {
      $result->status = false;
      $result->error = $result->message;
      $result->message = '';
      Log::warning('Qwen-generated error detected: ' . $result->error);
    }

    $result->usage = $responseData['usage'];

    return $result;
  }
}
