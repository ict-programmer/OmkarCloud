<?php

namespace Database\Seeders;

use App\Models\ServiceProvider;
use App\Http\Controllers\BillionMailController;
use App\Http\Requests\BillionMail\SendEmailRequest;
use App\Http\Requests\BillionMail\SendBatchEmailRequest;
use App\Traits\ServiceProviderSeederTrait;
use Illuminate\Database\Seeder;

class BillionMailServiceProviderSeeder extends Seeder
{
    use ServiceProviderSeederTrait;

    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // 1. Create or update service provider
        $serviceProvider = ServiceProvider::updateOrCreate(
            ['type' => 'BillionMail'],
            [
                'parameters' => [
                    'api_url' => 'http://billionmail.local/api/batch_mail/api',
                    'api_key' => 'b98020dae4c501b9e61be56d7312d6bb99595e9b190079e88308d732a6519ec3',
                ],
                'is_active' => true,
                'controller_name' => BillionMailController::class, // if you plan to add a controller later
            ]
        );

        // 2. Define related service types
        $serviceTypes = [
            [
                'name' => 'SendEmail',
                'input_parameters' => [
                    'to' => [
                        'type' => 'string',
                        'required' => true,
                        'userinput_rqd' => true,
                        'format' => 'email',
                        'description' => 'Recipient email address',
                    ],
                    'subject' => [
                        'type' => 'string',
                        'required' => true,
                        'userinput_rqd' => true,
                        'description' => 'Email subject line',
                    ],
                    'body' => [
                        'type' => 'string',
                        'required' => true,
                        'userinput_rqd' => true,
                        'description' => 'HTML/text body of the email',
                    ],
                ],
                'response' => [
                    'message' => 'Email sent successfully',
                    'status' => 'success',
                ],
                'response_path' => [
                    'final_result' => '$',
                ],
                'request_class_name' => SendEmailRequest::class, // you can bind a Request later
                'function_name' => 'sendEmail',
                'parameters' => [
                    'interface' => 'send',
                    'method' => 'POST',
                ],
            ],
            [
                'name' => 'SendBatchEmail',
                'input_parameters' => [
                    'recipients' => [
                        'type' => 'array',
                        'required' => true,
                        'userinput_rqd' => true,
                        'description' => 'List of recipient email addresses',
                    ],
                    'subject' => [
                        'type' => 'string',
                        'required' => true,
                        'userinput_rqd' => true,
                        'description' => 'Email subject line',
                    ],
                    'body' => [
                        'type' => 'string',
                        'required' => true,
                        'userinput_rqd' => true,
                        'description' => 'HTML/text body of the email',
                    ],
                ],
                'response' => [
                    'message' => 'Batch email sent successfully',
                    'status' => 'success',
                    'sent_count' => 0,
                ],
                'response_path' => [
                    'final_result' => '$',
                ],
                'request_class_name' => SendBatchEmailRequest::class,
                'function_name' => 'sendBatchEmail',
                'parameters' => [
                    'interface' => 'batch_send',
                    'method' => 'POST',
                ],
            ],
        ];

        // 3. Sync service types
        $keptServiceTypeIds = $this->processServiceTypes($serviceProvider, $serviceTypes, 'BillionMail');

        // 4. Cleanup obsolete types
        $deletedProviderTypeCount = $this->cleanupObsoleteServiceTypes($serviceProvider, $keptServiceTypeIds);

        $this->command->info('Cleanup completed:');
        $this->command->info("- Deleted {$deletedProviderTypeCount} obsolete service provider types");
        $this->command->info('- Kept ' . count($keptServiceTypeIds) . ' service types for BillionMail');
    }
}
