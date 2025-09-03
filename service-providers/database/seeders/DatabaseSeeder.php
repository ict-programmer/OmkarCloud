<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        $this->call([
            ClaudeAPIServiceProviderSeeder::class,
            QwenServiceProviderSeeder::class,
            GeminiServiceProviderSeeder::class,
            DeepSeekServiceProviderSeeder::class,
            RunwaymlServiceProviderSeeder::class,
            CanvaServiceProviderSeeder::class,
            GoogleSheetsAPIServiceProviderSeeder::class,
            PexelsServiceProviderSeeder::class,
            PerplexityServiceProviderSeeder::class,
            FreepikServiceProviderSeeder::class,
            CaptionsServiceProviderSeeder::class,
            GettyImageServiceProviderSeeder::class,
            BillionMailServiceProviderSeeder::class,
        ]);
    }
}