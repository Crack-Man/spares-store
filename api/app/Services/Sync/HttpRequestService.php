<?php

namespace App\Services\Sync;

use Illuminate\Support\Facades\Http;
use Illuminate\Http\Client\Response;

class HttpRequestService
{
    public function post(string $url, array $data): Response
    {
        $response = Http::post($url, $data);

        if (!$response->successful()) {
            throw new \RuntimeException("HTTP POST to {$url} failed with status {$response->status()}");
        }

        return $response;
    }
}
