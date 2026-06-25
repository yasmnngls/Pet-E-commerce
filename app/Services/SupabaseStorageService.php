<?php

namespace App\Services;

use Illuminate\Http\UploadedFile;
use Illuminate\Support\Str;

class SupabaseStorageService
{
    private string $url;
    private string $key;
    private string $bucket;

    public function __construct()
    {
        $this->url    = rtrim(config('services.supabase.url'), '/');
        $this->key    = config('services.supabase.key');
        $this->bucket = config('services.supabase.bucket');
    }

    /**
     * Upload a file to Supabase Storage and return the public URL.
     */
    public function upload(UploadedFile $file, string $folder = 'products'): string
    {
        $extension = $file->getClientOriginalExtension();
        $filename  = $folder . '/' . Str::uuid() . '.' . $extension;
        $contents  = file_get_contents($file->getRealPath());
        $mimeType  = $file->getMimeType();

        $endpoint = "{$this->url}/storage/v1/object/{$this->bucket}/{$filename}";

        $ch = curl_init($endpoint);
        curl_setopt_array($ch, [
            CURLOPT_CUSTOMREQUEST  => 'POST',
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_HTTPHEADER     => [
                'Authorization: Bearer ' . $this->key,
                'Content-Type: ' . $mimeType,
                'x-upsert: true',
            ],
            CURLOPT_POSTFIELDS     => $contents,
        ]);

        $response = curl_exec($ch);
        $httpCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
        curl_close($ch);

        if ($httpCode !== 200) {
            throw new \Exception('Supabase Storage upload failed: ' . $response);
        }

        // Return the public URL
        return "{$this->url}/storage/v1/object/public/{$this->bucket}/{$filename}";
    }
}