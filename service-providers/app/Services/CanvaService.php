<?php

namespace App\Services;

use App\Data\Request\Canva\CreateDesignData;
use App\Data\Request\Canva\CreateFolderData;
use App\Data\Request\Canva\ExportDesignJobData;
use App\Data\Request\Canva\GetDesignData;
use App\Data\Request\Canva\GetFolderData;
use App\Data\Request\Canva\GetFolderItemsData;
use App\Data\Request\Canva\GetUploadJobData;
use App\Data\Request\Canva\ListDesignsData;
use App\Data\Request\Canva\MoveFolderItemData;
use App\Data\Request\Canva\OAuthCallbackData;
use App\Data\Request\Canva\UpdateFolderData;
use App\Data\Request\Canva\UploadAssetData;
use App\Http\Exceptions\Forbidden;
use App\Http\Exceptions\NotFound;
use App\Http\Resources\Canva\CreateDesignResource;
use App\Http\Resources\Canva\CreateFolderResource;
use App\Http\Resources\Canva\DeleteFolderResource;
use App\Http\Resources\Canva\ExportDesignJobResource;
use App\Http\Resources\Canva\GetDesignResource;
use App\Http\Resources\Canva\GetExportDesignJobResource;
use App\Http\Resources\Canva\GetFolderItemsResource;
use App\Http\Resources\Canva\GetFolderResource;
use App\Http\Resources\Canva\GetUploadJobResource;
use App\Http\Resources\Canva\ListDesignsResource;
use App\Http\Resources\Canva\MoveFolderItemResource;
use App\Http\Resources\Canva\OAuthCallbackResource;
use App\Http\Resources\Canva\OAuthInitResource;
use App\Http\Resources\Canva\OAuthRefreshTokenResource;
use App\Http\Resources\Canva\UpdateFolderResource;
use App\Http\Resources\Canva\UploadAssetResource;
use App\Models\CanvaAsset;
use App\Models\ServiceProvider;
use App\Traits\CanvaTrait;
use Exception;
use Illuminate\Http\Client\ConnectionException;
use Illuminate\Http\Client\PendingRequest;
use Illuminate\Http\Client\Response;
use Illuminate\Pagination\LengthAwarePaginator;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Str;
use stdClass;

class CanvaService
{
  use CanvaTrait;

  protected string $apiUrl;

  protected PendingRequest $client;

  /**
   * Initiate OAuth
   *
   * @return OAuthInitResource
   */
  public function oauthInit(): OAuthInitResource
  {
    $state = Str::random(96);

    $url = $this->getAuthorizationUrl($state);

    $data = new stdClass();
    $data->url = $url;

    return OAuthInitResource::make($data);
  }

  /**
   * Handle OAuth callback
   *
   * @param OAuthCallbackData $data
   * @return OAuthCallbackResource
   */
  public function oauthCallback(OAuthCallbackData $data): OAuthCallbackResource
  {
    $code = $data->code;
    $state = $data->state;

    $this->handleOAuthCallback($code, $state);

    $data = new stdClass();
    $data->access_token = $this->getAccessToken();
    $data->refresh_token = $this->getRefreshToken();
    $data->expires_in = $this->getTokenExpiresIn();

    return OAuthCallbackResource::make($data);
  }

  /**
   * Refresh OAuth token
   *
   * @return OAuthRefreshTokenResource
   */
  public function refreshToken(): OAuthRefreshTokenResource
  {
    $this->refreshAccessToken();

    $data = new stdClass();
    $data->access_token = $this->getAccessToken();
    $data->refresh_token = $this->getRefreshToken();
    $data->expires_in = $this->getTokenExpiresIn();

    return OAuthRefreshTokenResource::make($data);
  }

  /**
   * This file contains the RunwayMLService class, which is responsible for
   * interacting with the external Canva service. The service provides
   * methods to handle API requests and responses for integration purposes.
   */
  protected function initializeService(): void
  {
    $provider = ServiceProvider::where('type', 'Canva')->first();

    if (
      !$provider ||
      !isset($provider->parameters['base_url'], $provider->parameters['version'])
    ) {
      throw new NotFound('Canva service provider not found.');
    }

    $token = $this->getAccessToken();

    $this->apiUrl = "{$provider->parameters['base_url']}/rest/{$provider->parameters['version']}";
    $this->client = Http::withHeaders([
      'Authorization' => 'Bearer ' . $token,
    ])
      ->timeout(0)
      ->connectTimeout(15);
  }

  public function createDesign(CreateDesignData $data): CreateDesignResource
  {
    $this->initializeService();

    try {
      $response = $this->client
        ->withHeader('Content-Type', 'application/json')
        ->post($this->apiUrl . '/designs', $data);
    } catch (ConnectionException | Exception $e) {
      Log::error('Canva API request error: ' . json_encode($e->getMessage()));
      throw new Forbidden('Canva API request failed: ' . $e->getMessage());
    }

    $result = $this->handleResponse($response);

    return CreateDesignResource::make($result);
  }

  public function listDesigns(ListDesignsData $data): ListDesignsResource
  {
    $this->initializeService();

    try {
      $response = $this->client
        ->withHeader('Content-Type', 'application/json')
        ->get($this->apiUrl . '/designs', [
          'continuation' => $data->continuation
        ]);
    } catch (ConnectionException | Exception $e) {
      Log::error('Canva API request error: ' . json_encode($e->getMessage()));
      throw new Forbidden('Canva API request failed');
    }

    $result = $this->handleResponse($response);

    return ListDesignsResource::make($result);
  }

  public function getDesign(GetDesignData $data): GetDesignResource
  {
    $this->initializeService();

    try {
      $response = $this->client
        ->withHeader('Content-Type', 'application/json')
        ->get($this->apiUrl . "/designs/{$data->design_id}");
    } catch (ConnectionException | Exception $e) {
      Log::error('Canva API request error: ' . json_encode($e->getMessage()));
      throw new Forbidden('Canva API request failed');
    }

    $result = $this->handleResponse($response);

    return GetDesignResource::make($result);
  }

  public function exportDesignJob(ExportDesignJobData $data): ExportDesignJobResource
  {
    $this->initializeService();

    try {
      $response = $this->client
        ->withHeader('Content-Type', 'application/json')
        ->post($this->apiUrl . "/exports", [
          "design_id" => $data->design_id,
          "format" => $data->format
        ]);
    } catch (ConnectionException | Exception $e) {
      Log::error('Canva API request error: ' . json_encode($e->getMessage()));
      throw new Forbidden('Canva API request failed');
    }

    $result = $this->handleResponse($response);

    return ExportDesignJobResource::make($result);
  }

  public function getExportDesignJob(string $exportID): GetExportDesignJobResource
  {
    $this->initializeService();

    try {
      $response = $this->client
        ->withHeader('Content-Type', 'application/json')
        ->get($this->apiUrl . "/exports/{$exportID}");
    } catch (ConnectionException | Exception $e) {
      Log::error('Canva API request error: ' . json_encode($e->getMessage()));
      throw new Forbidden('Canva API request failed');
    }

    $result = $this->handleResponse($response);

    return GetExportDesignJobResource::make($result);
  }

  /**
   * Upload asset
   * 
   * @param UploadAssetData $data
   * @return UploadAssetResource
   */
  public function uploadAsset(UploadAssetData $data): UploadAssetResource
  {
    $this->initializeService();

    try {
      $response = $this->client
        ->withHeader('Content-Type', 'application/octet-stream')
        ->withHeader('Asset-Upload-Metadata', json_encode([
          'name_base64' => base64_encode($data->file->getClientOriginalName()),
        ]))
        ->withBody(
          $data->file->get(),
          'application/octet-stream'
        )
        ->post($this->apiUrl . '/asset-uploads');
    } catch (ConnectionException | Exception $e) {
      Log::error('Canva API request error: ' . $e->getMessage());
      throw new Forbidden('Canva API request failed');
    }

    $result = $this->handleResponse($response);

    return UploadAssetResource::make($result->data);
  }

  /**
   * Get upload job
   * 
   * @param GetUploadJobData $data
   * @return GetUploadJobResource
   */
  public function getUploadJob(GetUploadJobData $data): GetUploadJobResource
  {
    $this->initializeService();

    try {
      $response = $this->client
        ->withHeader('Content-Type', 'application/json')
        ->get($this->apiUrl . "/asset-uploads/{$data->job_id}");
    } catch (ConnectionException | Exception $e) {
      Log::error('Canva API request error: ' . json_encode($e->getMessage()));
      throw new Forbidden('Canva API request failed');
    }

    $result = $this->handleResponse($response);

    return GetUploadJobResource::make($result->data);
  }

  protected function handleResponse(Response $response): stdClass
  {
    if ($response->failed()) {
      Log::error('Canva request error: ' . json_encode($response->json()));
      if ($response->json()) {
        if (array_key_exists('message', $response->json())) {
          throw new Forbidden('Canva request failed: ' . $response->json()['message']);
        }
        if (array_key_exists('error', $response->json())) {
          throw new Forbidden('Canva request failed: ' . $response->json()['error']);
        }
      }

      throw new Forbidden('Canva request failed');
    }

    $result = new stdClass;
    $result->data = $response->json();

    return $result;
  }

  public function createFolder(CreateFolderData $data): CreateFolderResource
  {
    $this->initializeService();

    try {
      $response = $this->client
        ->withHeader('Content-Type', 'application/json')
        ->post($this->apiUrl . '/folders', [
          'parent_folder_id' => $data->parent_folder_id,
          'name' => $data->name,
        ]);
    } catch (ConnectionException | Exception $e) {
      Log::error('Canva API request error: ' . json_encode($e->getMessage()));
      throw new Forbidden('Canva API request failed');
    }

    $result = $this->handleResponse($response);

    return CreateFolderResource::make($result);
  }

  public function getFolder(GetFolderData $data): GetFolderResource
  {
    $this->initializeService();

    try {
      $response = $this->client
        ->withHeader('Content-Type', 'application/json')
        ->get($this->apiUrl . "/folders/{$data->folder_id}");
    } catch (ConnectionException | Exception $e) {
      Log::error('Canva API request error: ' . json_encode($e->getMessage()));
      throw new Forbidden('Canva API request failed');
    }

    $result = $this->handleResponse($response);

    return GetFolderResource::make($result);
  }

  public function updateFolder(UpdateFolderData $data): UpdateFolderResource
  {
    $this->initializeService();

    try {
      $response = $this->client
        ->withHeader('Content-Type', 'application/json')
        ->patch($this->apiUrl . "/folders/{$data->folder_id}", [
          'name' => $data->name,
        ]);
    } catch (ConnectionException | Exception $e) {
      Log::error('Canva API request error: ' . json_encode($e->getMessage()));
      throw new Forbidden('Canva API request failed');
    }

    $result = $this->handleResponse($response);

    return UpdateFolderResource::make($result);
  }

  public function deleteFolder(string $folderIID): DeleteFolderResource
  {
    $this->initializeService();

    try {
      $response = $this->client
        ->withHeader('Content-Type', 'application/json')
        ->delete($this->apiUrl . "/folders/{$folderIID}");
    } catch (ConnectionException | Exception $e) {
      Log::error('Canva API request error: ' . json_encode($e->getMessage()));
      throw new Forbidden('Canva API request failed');
    }

    $result = $this->handleResponse($response);

    return DeleteFolderResource::make($result);
  }

  public function getFolderItems(GetFolderItemsData $data): GetFolderItemsResource
  {
    $this->initializeService();

    try {
      $response = $this->client
        ->withHeader('Content-Type', 'application/json')
        ->get($this->apiUrl . "/folders/{$data->folder_id}/items", [
          'continuation' => $data->continuation,
          'item_types' => $data->item_types,
          'sort_by' => $data->sort_by,
        ]);
    } catch (ConnectionException | Exception $e) {
      Log::error('Canva API request error: ' . json_encode($e->getMessage()));
      throw new Forbidden('Canva API request failed');
    }

    $result = $this->handleResponse($response);

    return GetFolderItemsResource::make($result);
  }

  public function moveFolderItem(MoveFolderItemData $data): MoveFolderItemResource
  {
    $this->initializeService();

    try {
      $response = $this->client
        ->withHeader('Content-Type', 'application/json')
        ->post($this->apiUrl . "/folders/move", [
          'to_folder_id' => $data->to_folder_id,
          'item_id' => $data->item_id,
        ]);
    } catch (ConnectionException | Exception $e) {
      Log::error('Canva API request error: ' . json_encode($e->getMessage()));
      throw new Forbidden('Canva API request failed');
    }

    $result = $this->handleResponse($response);

    return MoveFolderItemResource::make($result);
  }
}
