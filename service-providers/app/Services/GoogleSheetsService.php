<?php

namespace App\Services;

use App\Data\Request\GoogleSheet\SearchGoogleSheetData;
use Google\Client;
use Google\Service\Sheets;
use Google\Service\Sheets\ValueRange;

class GoogleSheetsService
{
    protected Sheets $service;

    public function __construct()
    {
        $client = new Client();
        $client->setAuthConfig(storage_path('app/credentials.json'));
        $client->addScope(Sheets::SPREADSHEETS_READONLY);

        $this->service = new Sheets($client);
    }

    public function getSheetData($spreadsheetId, $range): ValueRange
    {
        return $this->service->spreadsheets_values->get($spreadsheetId, $range);
    }

    public function search(SearchGoogleSheetData $data): array
    {
        $sheet = $this->getAllData($data->sheet_id, $data->sheet_name);

        if (empty($sheet)) {
            return [];
        }
        array_shift($sheet);

        return array_filter($sheet, function ($item) use ($data) {
            $matches = true;

            if ($data->client_id !== null) {
                $matches = ($item[0] === $data->client_id);
            }

            if ($data->project_id !== null) {
                $matches = $matches && ($item[1] === $data->project_id);
            }

            if ($data->auth_uri !== null) {
                $matches = $matches && ($item[2] === $data->auth_uri);
            }

            if ($data->token_uri !== null) {
                $matches = $matches && ($item[3] === $data->token_uri);
            }

            if ($data->auth_provider_x509_cert_url !== null) {
                $matches = $matches && ($item[4] === $data->auth_provider_x509_cert_url);
            }

            if ($data->client_secret !== null) {
                $matches = $matches && ($item[5] === $data->client_secret);
            }

            if ($data->redirect_uris !== null) {
                $matches = $matches && ($item[6] === $data->redirect_uris);
            }

            if ($data->interface !== null) {
                $matches = $matches && ($item[7] === $data->interface);
            }

            return $matches;
        });
    }

    public function getAllData($spreadsheetId, $spreadsheetTitle): array
    {
        $spreadsheet = $this->service->spreadsheets->get($spreadsheetId);
        $sheets = $spreadsheet->getSheets();

        foreach ($sheets as $sheet) {
            $sheetTitle = $sheet->getProperties()->getTitle();
            if ($spreadsheetTitle !== $sheetTitle) {
                continue;
            }
            $range = $sheetTitle . '!A:Z';

            $response = $this->getSheetData($spreadsheetId, $range);

            return $response->getValues();
        }

        return [];
    }
}
