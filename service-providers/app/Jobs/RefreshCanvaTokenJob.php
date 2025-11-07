<?php

namespace App\Jobs;

use App\Services\CanvaService;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Queue\Queueable;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Log;

class RefreshCanvaTokenJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    /**
     * Create a new job instance.
     */
    public function __construct()
    {
        //
    }

    /**
     * Execute the job.
     */
    public function handle(): void
    {
        try {
            $canvaService = new CanvaService();
            $refreshed = $canvaService->refreshAccessToken();

            if ($refreshed) {
                Log::info('Canva token refreshed successfully.');
            } else {
                Log::warning('Canva token refresh failed.');
            }
        } catch (\Exception $e) {
            Log::error('Canva token refresh job failed: ' . $e->getMessage());
        }
    }
}
