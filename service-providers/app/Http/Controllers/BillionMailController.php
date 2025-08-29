<?php

namespace App\Http\Controllers;

use App\Http\Requests\BillionMail\SendEmailRequest;
use App\Http\Requests\BillionMail\SendBatchEmailRequest;
use App\Services\BillionMailService;
use Illuminate\Http\JsonResponse;

class BillionMailController extends BaseController
{
    protected BillionMailService $billionMail;

    public function __construct(BillionMailService $billionMail)
    {
        $this->billionMail = $billionMail;
    }

    /**
     * Send a single email
     */
    public function sendEmail(SendEmailRequest $request): JsonResponse
    {
        $response = $this->billionMail->sendEmail($request->validated());

        return $this->logAndResponse($response);
    }

    /**
     * Send batch emails
     */
    public function sendBatchEmail(SendBatchEmailRequest $request): JsonResponse
    {
        $response = $this->billionMail->sendBatchEmail($request->validated());

        return $this->logAndResponse($response);
    }
}
