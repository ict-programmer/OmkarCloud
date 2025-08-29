<?php

namespace App\Http\Controllers;

use App\Http\Requests\BillionMail\SendEmailRequest;
use App\Http\Requests\BillionMail\SendBatchEmailRequest;
use App\Services\BillionMailService;

class BillionMailController extends Controller
{
    protected BillionMailService $billionMail;

    public function __construct(BillionMailService $billionMail)
    {
        $this->billionMail = $billionMail;
    }

    public function sendEmail(SendEmailRequest $request)
    {
        return response()->json(
            $this->billionMail->sendEmail($request->validated())
        );
    }

    public function sendBatchEmail(SendBatchEmailRequest $request)
    {
        return response()->json(
            $this->billionMail->sendBatchEmail($request->validated())
        );
    }
}
