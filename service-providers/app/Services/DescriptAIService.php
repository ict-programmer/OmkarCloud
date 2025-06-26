<?php

namespace App\Services;

use App\Data\Request\DescriptAI\GenerateAsyncData;
use App\Enums\common\ServiceProviderEnum;
use App\Http\Exceptions\Forbidden;
use App\Http\Exceptions\NotFound;
use App\Http\Resources\DescriptAI\GenerateAsyncResource;
use App\Http\Resources\DescriptAI\GetGenerateAsyncResource;
use App\Http\Resources\DescriptAI\GetVoicesResource;
use App\Models\ServiceProvider;
use Exception;
use Illuminate\Http\Client\ConnectionException;
use Illuminate\Http\Client\PendingRequest;
use Illuminate\Http\Client\Response;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;
use stdClass;

class DescriptAIService
{
  /**
   * Descript AI API model
   */
  protected string $DESCRIPT_AI_API_MODEL = 'descript-ai-3-5-haiku-20241022';

  protected string $DESCRIPT_AI_API_VERSION_HEADER = '2023-06-01';

  protected string $apiUrl;

  protected string $apiKey;

  protected PendingRequest $client;


  /**
   * This file contains the DescriptAIService class, which is responsible for
   * interacting with the external Descript AI  service. The service provides
   * methods to handle API requests and responses for integration purposes.
   */
  protected function initializeService(): void
  {
    $provider = ServiceProvider::where('type', ServiceProviderEnum::DESCRIPT_AI->value)->first();

    if (
      !$provider ||
      !isset($provider->parameter['base_url'], $provider->parameter['version'])
    ) {
      throw new NotFound('Descript AI service provider not found.');
    }

    $apiKey = config('services.descriptai.api_key');

    throw_if(empty($apiKey), new NotFound('Descript AI key not configured.'));

    $this->apiUrl = "{$provider->parameter['base_url']}/overdub";

    $this->client = Http::withToken($apiKey)
      ->withHeader('Authorization', "Bearer {$apiKey}")
      ->withHeader('Content-Type', 'application/json')
      ->timeout(0)
      ->connectTimeout(15);
  }

  /**
   * Generate text using Descript AI 
   *
   * @throws Forbidden
   */
  public function generateAsync(GenerateAsyncData $data): GenerateAsyncResource
  {
    $this->initializeService();

    try {
      $response = $this->client->post($this->apiUrl."/generate_async", [
        'text' => $data->text,
        'voice_id' => $data->voice_id,
        'voice_style_id' => $data->voice_style_id,
        'prefix_text' => $data->prefix_text,
        'prefix_audio_url' => $data->prefix_audio_url,
        'suffix_text' => $data->suffix_text,
        'suffix_audio_url' => $data->suffix_audio_url,
        'callback_url' => $data->callback_url,
      ]);
    } catch (ConnectionException | Exception $e) {
      Log::error('Descript AI request error: ' . json_encode($e->getMessage()));
      throw new Forbidden('Descript AI request failed');
    }

    $result = $this->handleResponse($response);

    return GenerateAsyncResource::make($result);
  }

  public function getGenerateAsync(string $id): GetGenerateAsyncResource
  {
    $this->initializeService();

    try {
      $response = $this->client->get($this->apiUrl . "/generate_async/{$id}");
    } catch (ConnectionException | Exception $e) {
      Log::error('Descript AI API request error: ' . json_encode($e->getMessage()));
      throw new Forbidden('Descript AI API request failed');
    }

    $result = $this->handleResponse($response);
    
    return GetGenerateAsyncResource::make($result);
  }

  public function getVoices(): GetVoicesResource
  {
    $this->initializeService();

    try {
      $response = $this->client->get($this->apiUrl . "/voices");
    } catch (ConnectionException | Exception $e) {
      Log::error('Descript AI API request error: ' . json_encode($e->getMessage()));
      throw new Forbidden('Descript AI API request failed');
    }

    $result = $this->handleResponse($response);

    return GetVoicesResource::make($result);
  }

  /**
   * Handle and process Descript AI  response
   * 
   * @param Response $response The response from Descript AI 
   * @return stdClass Object containing status, message, and potentially error
   * @throws HttpException When API request fails
   */
  protected function handleResponse(Response $response)
  {
    if ($response->failed()) {
      Log::error('Descript AI request error: ' . json_encode($response->json()));
      if ($response->json() && array_key_exists('error', $response->json())) {
        throw new Forbidden('Descript AI request failed: ' . $response->json()['error']);
      }

      throw new Forbidden('Descript AI request failed');
    }

    $result = new stdClass;
    $result->data = $response->json();

    return $result;
  }
}
