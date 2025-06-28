<?php

namespace App\Services;

use App\Data\Request\Midjourney\ImageGenerationData;
use App\Data\Request\Midjourney\ImageVariationData;
use App\Data\Request\Midjourney\GetTaskData;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

class MidjourneyService
{
    public function imageGeneration(ImageGenerationData $data): array
    {
        $requestBody = [
            'model' => 'midjourney',
            'task_type' => 'imagine',
            'input' => [
                'prompt' => $data->prompt,
                'aspect_ratio' => $data->aspect_ratio ?? '1:1',
            ]
        ];

        $initialResponse = $this->callMidjourneyAPI($requestBody);

        $taskId = $initialResponse['data']['task_id'] ?? null;
        
        if (!$taskId) {
            return $initialResponse;
        }

        return $this->pollTaskUntilCompleted($taskId);
    }

    public function imageVariation(ImageVariationData $data): array
    {
        $requestBody = [
            'model' => 'midjourney',
            'task_type' => 'variation',
            'input' => [
                'origin_task_id' => $data->origin_task_id,
                'index' => $data->index,
                'prompt' => $data->prompt,
            ]
        ];

        $initialResponse = $this->callMidjourneyAPI($requestBody);

        $taskId = $initialResponse['data']['task_id'] ?? null;
        
        if (!$taskId) {
            return $initialResponse;
        }

        return $this->pollTaskUntilCompleted($taskId);
    }

    public function getTask(GetTaskData $data): array
    {
        $url = config('midjourney.base_url') . '/task/' . $data->task_id;
        
        $headers = [
            'x-api-key' => config('midjourney.api_key'),
            'Accept' => 'application/json',
        ];

        $response = Http::withHeaders($headers)
            ->timeout(30)
            ->get($url);
        
        if ($response->failed()) {
            $statusCode = $response->status();
            $errorMessage = $response->json('message') ?? 'Request failed';
            
            // Check for API-level errors (PiAPI/Midjourney specific)
            if ($response->json('error')) {
                abort(response()->json([
                    'error' => 'Midjourney API Error',
                    'details' => $response->json('error')
                ], $statusCode));
            }
            
            // HTTP-level errors
            abort(response()->json([
                'error' => "HTTP {$statusCode}: {$errorMessage}"
            ], $statusCode));
        }

        return $response->json();
    }

    private function callMidjourneyAPI(array $requestBody): array
    {
        $url = config('midjourney.base_url') . '/task';
        
        $headers = [
            'x-api-key' => config('midjourney.api_key'),
            'Content-Type' => 'application/json',
            'Accept' => 'application/json',
        ];

        $response = Http::withHeaders($headers)
            ->timeout(30)
            ->post($url, $requestBody);

//        th($response->json());
        if ($response->failed()) {
            $statusCode = $response->status();
            $errorMessage = $response->json('message') ?? 'Request failed';
            
            // Check for API-level errors (PiAPI/Midjourney specific)
            if ($response->json('error')) {
                abort(response()->json([
                    'error' => 'Midjourney API Error',
                    'details' => $response->json('error')
                ], $statusCode));
            }
            
            // HTTP-level errors
            abort(response()->json([
                'error' => "HTTP {$statusCode}: {$errorMessage}"
            ], $statusCode));
        }

        return $response->json();
    }

    /**
     * Poll task status until completed
     *
     * @param string $taskId
     * @return array
     */
    private function pollTaskUntilCompleted(string $taskId): array
    {
        $maxAttempts = config('midjourney.polling.max_attempts', 60);
        $pollInterval = config('midjourney.polling.interval_seconds', 5);
        $attempts = 0;

        while ($attempts < $maxAttempts) {
            $attempts++;
            
            $taskResult = $this->getTaskById($taskId);

            Log::info("Polling attempt {$attempts} for task ID: {$taskId}", [
                'task_result' => $taskResult
            ]);
            if (!$taskResult) {
                throw new \Exception('Failed to get task status');
            }

            $status = $taskResult['data']['data']['status'] ?? $taskResult['data']['status'] ?? '';
            
            if (strtolower($status) === 'completed' || strtolower($status) === 'complete') {
                return [
                    'task_id' => $taskId,
                    'status' => 'completed',
                    'output' => $taskResult['data']['data']['output'] ?? $taskResult['data']['output'] ?? [],
                    'meta' => $taskResult['data']['data']['meta'] ?? $taskResult['data']['meta'] ?? [],
                    'full_response' => $taskResult
                ];
            }
            
            if (strtolower($status) === 'failed') {
                return [
                    'task_id' => $taskId,
                    'status' => 'failed',
                    'error' => $taskResult['data']['data']['error'] ?? $taskResult['data']['error'] ?? 'Task failed',
                    'full_response' => $taskResult
                ];
            }
            
            if ($attempts < $maxAttempts) {
                sleep($pollInterval);
            }
        }

        return [
            'task_id' => $taskId,
            'status' => 'timeout',
            'error' => 'Maximum polling attempts reached. Task may still be processing.',
            'attempts' => $attempts
        ];
    }

    /**
     * Get task by ID (internal method for polling)
     *
     * @param string $taskId
     * @return array|null
     */
    private function getTaskById(string $taskId): ?array
    {
        try {
            $url = config('midjourney.base_url') . '/task/' . $taskId;
            
            $headers = [
                'x-api-key' => config('midjourney.api_key'),
                'Accept' => 'application/json',
            ];

            $response = Http::withHeaders($headers)
                ->timeout(30)
                ->get($url);
            
            if ($response->successful()) {
                return $response->json();
            }
            
            return null;
        } catch (\Exception $e) {
            return null;
        }
    }
} 