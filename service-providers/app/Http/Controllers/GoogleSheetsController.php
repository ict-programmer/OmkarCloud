<?php

namespace App\Http\Controllers;

use App\Data\Request\GoogleSheet\SearchGoogleSheetData;
use App\Http\Requests\GoogleSheet\SearchGoogleSheetRequest;
use App\Services\GoogleSheetsService;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Artisan;
use OpenApi\Attributes as OA;

class GoogleSheetsController extends Controller
{
    public function __construct(protected GoogleSheetsService $sheetsService) {}

    #[OA\Post(
        path: '/api/google_spreadsheet/search',
        operationId: '/api/google_spreadsheet/search',
        description: 'Search Google Sheet',
        summary: 'Search Google Sheet',
        security: [['authentication' => []]],
        tags: ['GoogleSheet'],
    )]
    #[OA\QueryParameter(
        name: 'sheet_id',
        description: 'Unique Sheet ID',
        required: true,
        schema: new OA\Schema(
            type: 'string',
            example: '12wZ5VsQPPNUsFieDf_RNuuaQ30Mjy2sOJWUCOm1uHKA'
        )
    )]
    #[OA\QueryParameter(
        name: 'sheet_name',
        description: 'Name of the sheet',
        required: true,
        schema: new OA\Schema(
            type: 'string',
            example: 'Sheet1'
        )
    )]
    #[OA\QueryParameter(
        name: 'client_id',
        description: 'Client ID',
        required: false,
    )]
    #[OA\QueryParameter(
        name: 'project_id',
        description: 'Project ID',
        required: false,
    )]
    #[OA\QueryParameter(
        name: 'auth_uri',
        description: 'Auth URI',
        required: false,
    )]
    #[OA\QueryParameter(
        name: 'token_uri',
        description: 'Token URI',
        required: false,
    )]
    #[OA\QueryParameter(
        name: 'auth_provider_x509_cert_url',
        description: 'Auth Provider X509 Cert URL',
        required: false,
    )]
    #[OA\QueryParameter(
        name: 'client_secret',
        description: 'Client Secret',
        required: false,
    )]
    #[OA\QueryParameter(
        name: 'redirect_uris',
        description: 'Redirect URIs',
        required: false,
    )]
    #[OA\QueryParameter(
        name: 'interface',
        description: 'Interface',
        required: false,
    )]
    #[OA\Response(
        response: 200,
        description: 'Search Google Sheet',
        content: new OA\JsonContent(
            example: [
                'client-7249-8582',
                'project-550',
                'https://auth.example.com/oauth/89',
                'https://token.example.com/oauth/39',
                'https://certs.example.com/x509/85',
                'secret-tlzcnski8mp',
                'https://redirect.example.com/callback/7',
                'ui-4',
            ],
        )
    )]
    public function search(SearchGoogleSheetRequest $request): JsonResponse
    {
        $data = SearchGoogleSheetData::from($request->validated());

        $result = $this->sheetsService->search($data);

        if (empty($result)) {
            return response()->json(['message' => 'No data found'], 404);
        }

        return response()->json($result);
    }

    #[OA\Get(
        path: '/api/google_spreadsheet/seed',
        operationId: '/api/google_spreadsheet/seed',
        description: 'Seed Google Sheet',
        summary: 'Seed Google Sheet',
        security: [['authentication' => []]],
        tags: ['GoogleSheet'],
    )]
    #[OA\Response(
        response: 200,
        description: 'Seed Google Sheet',
        content: new OA\JsonContent(
            example: [
                'message' => 'Google Sheet seeded successfully',
            ],
        )
    )]
    public function seed(): JsonResponse
    {
        Artisan::call('google-sheet:insert-users');

        return response()->json([
            'message' => 'Google Sheet seeded successfully',
        ]);
    }
}
