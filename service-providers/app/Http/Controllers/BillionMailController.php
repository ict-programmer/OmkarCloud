<?php

namespace App\Http\Controllers;

use App\Http\Requests\BillionMail\SendEmailRequest;
use App\Http\Requests\BillionMail\SendBatchEmailRequest;
use App\Data\Request\BillionMail\SendEmailData;
use App\Data\Request\BillionMail\SendBatchEmailData;
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
        $data = SendEmailData::from($request->validated());
        $response = $this->billionMail->sendEmail($data);

        return $this->logAndResponse($response);
    }

    /**
     * Send batch emails
     */
    public function sendBatchEmail(SendBatchEmailRequest $request): JsonResponse
    {
        $data = SendBatchEmailData::from($request->validated());
        $response = $this->billionMail->sendBatchEmail($data);

        return $this->logAndResponse($response);
    }

}
