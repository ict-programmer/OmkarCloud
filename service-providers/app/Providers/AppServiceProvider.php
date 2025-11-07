<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\Http;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        //
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        if (app()->environment('local') && env('OMKAR_MAPS_FAKE', true)) {
            Http::fake([
                // adjust paths to match your Omkar service calls
                'api.omkar.cloud/maps/search/query'    => Http::response([
                    'status'  => 'success',
                    'results' => [
                        ['name' => 'Sample Cafe', 'place_id' => 'abc123', 'rating' => 4.5],
                    ],
                ], 200),

                'api.omkar.cloud/maps/search/links'    => Http::response(['status' => 'queued', 'task_id' => 't_1'], 200),
                'api.omkar.cloud/maps/reviews/fetch'   => Http::response(['status' => 'success', 'reviews' => []], 200),
                'api.omkar.cloud/maps/results/status*' => Http::response(['status' => 'done', 'task_id' => 't_1'], 200),
                'api.omkar.cloud/maps/results/output*' => Http::response(['status' => 'success', 'results' => []], 200),
                'api.omkar.cloud/maps/results/filter'  => Http::response(['status' => 'success', 'results' => []], 200),
                'api.omkar.cloud/maps/results/sort'    => Http::response(['status' => 'success', 'results' => []], 200),
                'api.omkar.cloud/maps/export'          => Http::response(['status' => 'success', 'file' => ['url' => 'https://example.com/export.csv']], 200),
                'api.omkar.cloud/maps/tasks/manage'    => Http::response(['status' => 'queued', 'task_id' => 't_1'], 200),
            ]);
        }
    }
}
