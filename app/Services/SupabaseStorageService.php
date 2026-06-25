<?php

namespace App\Services;

class SupabaseStorageService
{
    private string $url;
    private string $key;

    public function __construct()
    {
        // Ensure these match your .env keys
        $this->url = env('SUPABASE_URL');
        $this->key = env('SUPABASE_SERVICE_ROLE_KEY');
    }

    public function upload($file, $bucket)
    {
        // Construct the full Supabase Storage path
        $path = $bucket . '/' . $file->hashName();
        $uploadUrl = $this->url . '/storage/v1/object/' . $path;

        $ch = curl_init($uploadUrl);
        
        curl_setopt($ch, CURLOPT_CUSTOMREQUEST, 'POST');
        curl_setopt($ch, CURLOPT_POSTFIELDS, file_get_contents($file->getRealPath()));
        curl_setopt($ch, CURLOPT_HTTPHEADER, [
            'Authorization: Bearer ' . $this->key,
            'apikey: ' . $this->key,
            'Content-Type: ' . $file->getMimeType(),
        ]);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        
        // Windows/Local Development connection fixes
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
        curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, false);

        $response = curl_exec($ch);
        $httpCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
        $error = curl_error($ch);
        curl_close($ch);

        if ($httpCode !== 200) {
            dd("HTTP CODE: " . $httpCode, "CURL ERROR: " . $error, "RESPONSE: " . $response);
        }

        return $path; // Returns the path to be saved in your database
    }
}