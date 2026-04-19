<?php

namespace App\Services\Logging;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Storage;

class LogService
{
    public function save(string $folder, string $fileName, array $data): void
    {
        $timestamp = Carbon::now()->format('Y-m-d_H-i-s');
        $filePath = "{$folder}/{$timestamp}_{$fileName}.json";

        Storage::disk('logging')->put(
            $filePath,
            json_encode($data, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE)
        );
    }
}
