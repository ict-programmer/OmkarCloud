<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;
use App\Http\Requests\BillionMail\SendEmailRequest;
use App\Http\Requests\BillionMail\SendBatchEmailRequest;

class BillionMailController extends Controller
{
    protected string $apiUrl;
    protected string $apiKey;

    public function __construct()
    {
        $this->apiUrl = config('services.billionmail.api_url');
        $this->apiKey = config('services.billionmail.api_key');
    }

    /**
     * Send a single email
     */
    public function sendEmail(SendEmailRequest $request)
    {
        $validated = $request->validated();

        try {
            if (config('app.env') === 'local') {
                // In local environment, skip SSL verification
                $response = Http::withHeaders([
                    'X-API-Key' => $this->apiKey
                ])->withoutVerifying()->post("{$this->apiUrl}/send", [
                    'recipient' => $validated['recipient'],
                    'addresser' => $validated['addresser'],
                    'attribs'   => $validated['attribs'],
                ]);
            } else {
                // In staging/production, send normally
                $response = Http::withHeaders([
                    'X-API-Key' => $this->apiKey
                ])->post("{$this->apiUrl}/send", [
                    'recipient' => $validated['recipient'],
                    'addresser' => $validated['addresser'],
                    'attribs'   => $validated['attribs'],
                ]);
            }


            return response()->json([
                'success' => $response->successful(),
                'status'  => $response->status(),
                'data'    => $response->json(),
            ]);

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'status'  => 500,
                'message' => $e->getMessage(),
            ], 500);
        }
    }

    /**
     * Send batch emails
     */
    public function sendBatchEmail(SendBatchEmailRequest $request)
    {
        $validated = $request->validated();

        try {
            if (config('app.env') === 'local') {
                // In local environment, skip SSL verification
                $response = Http::withHeaders([
                    'X-API-Key' => $this->apiKey
                ])->withoutVerifying()->post("{$this->apiUrl}/batch_send", [
                    'recipients' => $validated['recipients'],
                    'addresser'  => $validated['addresser'],
                    'attribs'    => $validated['attribs'],
                ]);
            } else {
                // In staging/production, send normally
                $response = Http::withHeaders([
                    'X-API-Key' => $this->apiKey
                ])->post("{$this->apiUrl}/batch_send", [
                    'recipients' => $validated['recipients'],
                    'addresser'  => $validated['addresser'],
                    'attribs'    => $validated['attribs'],
                ]);
            }

            return response()->json([
                'success' => $response->successful(),
                'status'  => $response->status(),
                'data'    => $response->json(),
            ]);

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'status'  => 500,
                'message' => $e->getMessage(),
            ], 500);
        }
    }
}
