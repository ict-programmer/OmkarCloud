<?php

namespace Database\Seeders;

use App\Models\ServiceProvider;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Log;
use MongoDB\Client;

class ServiceProviderSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        try {
            $this->command->info('Starting to seed service providers...');

            // Manually parsed credential data

            $providers = [
                [
                    'client_id' => 'client-5744-2640',
                    'project_id' => 'project-335',
                    'auth_uri' => 'https://auth.example.com/oauth/43',
                    'token_uri' => 'https://token.example.com/oauth/86',
                    'auth_provider_x509_cert_url' => 'https://certs.example.com/x509/31',
                    'client_secret' => 'secret-5ocn8gvndco',
                    'redirect_uris' => 'https://redirect.example.com/callback/7',
                    'interface' => 'list',
                    'sheet_id' => '12wZ5VsQPPNUsFieDf_RNuuaQ30Mjy2sOJWUCOm1uHKA',
                    'sheet_name' => 'Sheet1',
                ],
                [
                    'client_id' => 'client-7249-8582',
                    'project_id' => 'project-550',
                    'auth_uri' => 'https://auth.example.com/oauth/89',
                    'token_uri' => 'https://token.example.com/oauth/39',
                    'auth_provider_x509_cert_url' => 'https://certs.example.com/x509/85',
                    'client_secret' => 'secret-tlzcnski8mp',
                    'redirect_uris' => 'https://redirect.example.com/callback/7',
                    'interface' => 'list',
                    'sheet_id' => '12wZ5VsQPPNUsFieDf_RNuuaQ30Mjy2sOJWUCOm1uHKA',
                    'sheet_name' => 'Sheet1',
                ],
                [
                    'client_id' => 'client-7120-8283',
                    'project_id' => 'project-951',
                    'auth_uri' => 'https://auth.example.com/oauth/31',
                    'token_uri' => 'https://token.example.com/oauth/78',
                    'auth_provider_x509_cert_url' => 'https://certs.example.com/x509/13',
                    'client_secret' => 'secret-ll2mrdi99ek',
                    'redirect_uris' => 'https://redirect.example.com/callback/4',
                    'interface' => 'list',
                    'sheet_id' => '12wZ5VsQPPNUsFieDf_RNuuaQ30Mjy2sOJWUCOm1uHKA',
                    'sheet_name' => 'Sheet1',
                ],
                [
                    'client_id' => 'client-4462-4485',
                    'project_id' => 'project-82',
                    'auth_uri' => 'https://auth.example.com/oauth/46',
                    'token_uri' => 'https://token.example.com/oauth/46',
                    'auth_provider_x509_cert_url' => 'https://certs.example.com/x509/33',
                    'client_secret' => 'secret-mdw8y7xyzp',
                    'redirect_uris' => 'https://redirect.example.com/callback/0',
                    'interface' => 'list',
                    'sheet_id' => '12wZ5VsQPPNUsFieDf_RNuuaQ30Mjy2sOJWUCOm1uHKA',
                    'sheet_name' => 'Sheet1',
                ],
                [
                    'client_id' => 'client-8725-5072',
                    'project_id' => 'project-630',
                    'auth_uri' => 'https://auth.example.com/oauth/16',
                    'token_uri' => 'https://token.example.com/oauth/60',
                    'auth_provider_x509_cert_url' => 'https://certs.example.com/x509/30',
                    'client_secret' => 'secret-0znvoy4yoils',
                    'redirect_uris' => 'https://redirect.example.com/callback/6',
                    'interface' => 'list',
                    'sheet_id' => '12wZ5VsQPPNUsFieDf_RNuuaQ30Mjy2sOJWUCOm1uHKA',
                    'sheet_name' => 'Sheet1',
                ],
                [
                    'client_id' => 'client-5417-3670',
                    'project_id' => 'project-869',
                    'auth_uri' => 'https://auth.example.com/oauth/19',
                    'token_uri' => 'https://token.example.com/oauth/29',
                    'auth_provider_x509_cert_url' => 'https://certs.example.com/x509/60',
                    'client_secret' => 'secret-phunxgv5vcj',
                    'redirect_uris' => 'https://redirect.example.com/callback/9',
                    'interface' => 'list',
                    'sheet_id' => '12wZ5VsQPPNUsFieDf_RNuuaQ30Mjy2sOJWUCOm1uHKA',
                    'sheet_name' => 'Sheet1',
                ],
                [
                    'client_id' => 'client-646-6540',
                    'project_id' => 'project-372',
                    'auth_uri' => 'https://auth.example.com/oauth/72',
                    'token_uri' => 'https://token.example.com/oauth/96',
                    'auth_provider_x509_cert_url' => 'https://certs.example.com/x509/93',
                    'client_secret' => 'secret-tv0kxxvobhm',
                    'redirect_uris' => 'https://redirect.example.com/callback/7',
                    'interface' => 'list',
                    'sheet_id' => '12wZ5VsQPPNUsFieDf_RNuuaQ30Mjy2sOJWUCOm1uHKA',
                    'sheet_name' => 'Sheet1',
                ],
                [
                    'client_id' => 'client-2848-9801',
                    'project_id' => 'project-619',
                    'auth_uri' => 'https://auth.example.com/oauth/81',
                    'token_uri' => 'https://token.example.com/oauth/19',
                    'auth_provider_x509_cert_url' => 'https://certs.example.com/x509/83',
                    'client_secret' => 'secret-x6s5njffmz',
                    'redirect_uris' => 'https://redirect.example.com/callback/0',
                    'interface' => 'list',
                    'sheet_id' => '12wZ5VsQPPNUsFieDf_RNuuaQ30Mjy2sOJWUCOm1uHKA',
                    'sheet_name' => 'Sheet1',
                ],
                [
                    'client_id' => 'client-7726-2020',
                    'project_id' => 'project-67',
                    'auth_uri' => 'https://auth.example.com/oauth/70',
                    'token_uri' => 'https://token.example.com/oauth/16',
                    'auth_provider_x509_cert_url' => 'https://certs.example.com/x509/61',
                    'client_secret' => 'secret-htrbmty0fsb',
                    'redirect_uris' => 'https://redirect.example.com/callback/3',
                    'interface' => 'list',
                    'sheet_id' => '12wZ5VsQPPNUsFieDf_RNuuaQ30Mjy2sOJWUCOm1uHKA',
                    'sheet_name' => 'Sheet1',
                ],
                [
                    'client_id' => 'client-9347-3311',
                    'project_id' => 'project-390',
                    'auth_uri' => 'https://auth.example.com/oauth/48',
                    'token_uri' => 'https://token.example.com/oauth/33',
                    'auth_provider_x509_cert_url' => 'https://certs.example.com/x509/48',
                    'client_secret' => 'secret-zv21qmx5j9e',
                    'redirect_uris' => 'https://redirect.example.com/callback/5',
                    'interface' => 'list',
                    'sheet_id' => '12wZ5VsQPPNUsFieDf_RNuuaQ30Mjy2sOJWUCOm1uHKA',
                    'sheet_name' => 'Sheet1',
                ],
            ];

            $this->command->info('Found ' . count($providers) . ' service providers to seed.');

            // First, try to use the Eloquent model approach
            try {
                $this->seedWithEloquent($providers);
            } catch (\Exception $e) {
                $this->command->error('Error using Eloquent: ' . $e->getMessage());
                $this->command->info('Falling back to direct MongoDB connection...');

                // Fallback to direct MongoDB connection
                $this->seedWithDirectMongoDB($providers);
            }
        } catch (\Exception $e) {
            Log::error('Service Provider Seeding Error: ' . $e->getMessage());
            $this->command->error('Error: ' . $e->getMessage());
            $this->command->line('Stack trace:');
            $this->command->line($e->getTraceAsString());
        }
    }

    /**
     * Seed using Eloquent models.
     */
    private function seedWithEloquent(array $providers): void
    {
        // Clear existing providers first
        $this->command->info('Clearing existing service providers...');
        ServiceProvider::query()->delete();

        $count = 0;
        foreach ($providers as $index => $providerData) {
            $this->command->info('Seeding provider ' . ($index + 1) . '/' . count($providers));

            // Define service provider type
            $providerType = 'Google spreadsheet';

            // Format redirect_uris as array
            $redirectUris = [$providerData['redirect_uris']];

            // Create the service provider
            $provider = new ServiceProvider();
            $provider->type = $providerType;
            $provider->client_id = $providerData['client_id'];
            $provider->project_id = $providerData['project_id'];
            $provider->auth_uri = $providerData['auth_uri'];
            $provider->token_uri = $providerData['token_uri'];
            $provider->auth_provider_x509_cert_url = $providerData['auth_provider_x509_cert_url'];
            $provider->client_secret = $providerData['client_secret'];
            $provider->redirect_uris = $redirectUris;
            $provider->interface = $providerData['interface'];
            $provider->sheet_id = $providerData['sheet_id'];
            $provider->sheet_name = $providerData['sheet_name'];
            $provider->save();

            $count++;
        }

        $this->command->info("Successfully seeded $count service providers using Eloquent.");
    }

    /**
     * Seed using direct MongoDB connection.
     */
    private function seedWithDirectMongoDB(array $providers): void
    {
        // Connect to MongoDB directly
        $uri = env('MONGO_URI');
        $database = env('MONGO_DATABASE');

        $this->command->info("Connecting to MongoDB at: $uri");

        // Connect to MongoDB with explicit timeout settings
        $client = new Client($uri, [
            'serverSelectionTimeoutMS' => 5000,
            'connectTimeoutMS' => 10000,
        ]);

        $collection = $client->selectDatabase($database)->selectCollection('service_providers');

        // Clear existing providers first
        $this->command->info('Clearing existing service providers...');
        $collection->deleteMany([]);

        $count = 0;
        foreach ($providers as $index => $providerData) {
            $this->command->info('Seeding provider ' . ($index + 1) . '/' . count($providers));

            // Define service provider type
            $providerType = 'Google spreadsheet';

            // Format redirect_uris as array
            $redirectUris = [$providerData['redirect_uris']];

            // Create document with flattened structure
            $document = [
                'type' => $providerType,
                'client_id' => $providerData['client_id'],
                'project_id' => $providerData['project_id'],
                'auth_uri' => $providerData['auth_uri'],
                'token_uri' => $providerData['token_uri'],
                'auth_provider_x509_cert_url' => $providerData['auth_provider_x509_cert_url'],
                'client_secret' => $providerData['client_secret'],
                'redirect_uris' => $redirectUris,
                'interface' => $providerData['interface'],
                'created_at' => new \MongoDB\BSON\UTCDateTime(),
                'updated_at' => new \MongoDB\BSON\UTCDateTime(),
            ];

            // Insert document
            $result = $collection->insertOne($document);

            if ($result->getInsertedCount() > 0) {
                $count++;
            }
        }

        $this->command->info("Successfully seeded $count service providers using direct MongoDB connection.");
    }
}
