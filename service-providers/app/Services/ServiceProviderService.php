<?php

namespace App\Services;

use App\Data\Request\GoogleSheet\SearchGoogleSheetData;
use App\Repositories\ServiceProviderRepository;

class ServiceProviderService
{
    protected $serviceProviderRepository;

    protected $googleSheetsService;

    public function __construct(
        ServiceProviderRepository $serviceProviderRepository,
        GoogleSheetsService $googleSheetsService
    ) {
        $this->serviceProviderRepository = $serviceProviderRepository;
        $this->googleSheetsService = $googleSheetsService;
    }

    /**
     * List service providers based on service_provider_id and interface_id
     *
     * @param  string  $serviceProviderId
     * @param  string  $interfaceId
     * @return array
     */
    public function listProviders(string $serviceProviderId, string $interface): ?array
    {
        $serviceProviders = $this->serviceProviderRepository->findByType([
            '_id' => $serviceProviderId,
            'interface' => $interface,
        ]);
        if (!$serviceProviders) {
            return null;
        }
        $searchData = new SearchGoogleSheetData(
            sheet_id: $serviceProviders->sheet_id,
            sheet_name: $serviceProviders->sheet_name,
            client_id: null,
            project_id: null,
            auth_uri: null,
            token_uri: null,
            auth_provider_x509_cert_url: null,
            client_secret: null,
            redirect_uris: null,
            interface: null
        );

        // Map the params to the search data object
        if (isset($serviceProviders->client_id)) {
            $searchData->client_id = $serviceProviders->client_id;
        }

        if (isset($serviceProviders->project_id)) {
            $searchData->project_id = $serviceProviders->project_id;
        }

        if (isset($serviceProviders->auth_uri)) {
            $searchData->auth_uri = $serviceProviders->auth_uri;
        }

        if (isset($serviceProviders->token_uri)) {
            $searchData->token_uri = $serviceProviders->token_uri;
        }

        if (isset($serviceProviders->auth_provider_x509_cert_url)) {
            $searchData->auth_provider_x509_cert_url = $serviceProviders->auth_provider_x509_cert_url;
        }

        if (isset($serviceProviders->client_secret)) {
            $searchData->client_secret = $serviceProviders->client_secret;
        }

        if (isset($serviceProviders->redirect_uris)) {
            $searchData->redirect_uris = $serviceProviders->redirect_uris;
        }

        if (isset($serviceProviders->interface)) {
            $searchData->interface = $serviceProviders->interface;
        }
        $results = $this->googleSheetsService->search($searchData);

        return $results;
    }
}
