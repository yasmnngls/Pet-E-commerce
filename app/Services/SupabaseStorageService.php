<?php

namespace App\Services;

class SupabaseStorageService
{
    private string $url;
    private string $key;

    public function __construct()
    {
        // Ensure these exactly match your .env file
        $this->url = env('SUPABASE_URL');
        $this->key = env('SUPABASE_KEY'); 
    }

    public function upload($file, $bucket = 'products')
    {
        // Create a clean, unique filename
        $filename = time() . '_' . preg_replace('/[^a-zA-Z0-9.\-_]/', '', $file->getClientOriginalName());
        $path = $bucket . '/' . $filename;
        
        // The API endpoint to upload the file
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
        
        // CRITICAL FIX FOR WINDOWS LOCALHOST: Ignores local SSL verification
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
        curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, false);

        $response = curl_exec($ch);
        $httpCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
        $error = curl_error($ch);
        curl_close($ch);

        // If successful, return the public URL that anyone can view
        if ($httpCode >= 200 && $httpCode < 300) {
            return $this->url . '/storage/v1/object/public/' . $path;
        }

        // If it fails, dump the error so we can see exactly why
        dd("HTTP CODE: " . $httpCode, "CURL ERROR: " . $error, "RESPONSE: " . $response);
    }
}