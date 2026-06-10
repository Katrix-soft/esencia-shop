<?php

namespace App\Services;

use Illuminate\Support\Facades\Http;
use Illuminate\Http\Client\PendingRequest;

class FragellaService
{
    /**
     * @var string
     */
    protected $baseUrl = 'https://api.fragella.com/api/v1';

    /**
     * Create a new HTTP client instance with default headers.
     *
     * @return PendingRequest
     */
    protected function client(): PendingRequest
    {
        return Http::baseUrl($this->baseUrl)
            ->withoutVerifying()
            ->withHeaders([
                'x-api-key' => config('services.fragella.api_key'),
                'Accept' => 'application/json',
            ]);
    }

    /**
     * Check API Usage.
     *
     * @return array
     */
    public function getUsage(): array
    {
        $response = $this->client()->get('/usage');
        
        return $response->json() ?? [];
    }

    /**
     * Search Fragrances by name.
     *
     * @param string $search
     * @return array
     */
    public function searchFragrances(string $search): array
    {
        $response = $this->client()->get('/fragrances', [
            'search' => $search,
        ]);
        
        return $response->json() ?? [];
    }

    /**
     * Find similar fragrances.
     *
     * @param string $name
     * @param int $limit
     * @return array
     */
    public function findSimilar(string $name, int $limit = 3): array
    {
        $response = $this->client()->get('/fragrances/similar', [
            'name' => $name,
            'limit' => $limit,
        ]);
        
        return $response->json() ?? [];
    }

    /**
     * Match by accords and notes.
     *
     * @param string $accords (e.g. "floral:100,fruity:90")
     * @param string|null $topNote
     * @return array
     */
    public function matchByAccordsAndNotes(string $accords, ?string $topNote = null): array
    {
        $params = ['accords' => $accords];
        if ($topNote) {
            $params['top'] = $topNote;
        }

        $response = $this->client()->get('/fragrances/match', $params);
        
        return $response->json() ?? [];
    }

    /**
     * Search brand info and fragrances.
     *
     * @param string $brand
     * @param int $limit
     * @return array
     */
    public function searchBrand(string $brand, int $limit = 2): array
    {
        // El endpoint es /brands/{brand}
        $response = $this->client()->get('/brands/' . urlencode($brand), [
            'limit' => $limit,
        ]);
        
        return $response->json() ?? [];
    }

    /**
     * Search notes and accords.
     *
     * @param string $search
     * @param int $limit
     * @return array
     */
    public function searchNotes(string $search, int $limit = 3): array
    {
        $response = $this->client()->get('/notes', [
            'search' => $search,
            'limit' => $limit,
        ]);
        
        return $response->json() ?? [];
    }
}
