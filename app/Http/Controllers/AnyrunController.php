<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use GuzzleHttp\Client;

class AnyrunController extends Controller
{
    public function getThreatIntel()
    {
        $url = 'https://api.any.run/v1/feeds/stix.json'; // Endpoint API
        $apiKey = env('ANYRUN_API_KEY'); // API Key dari .env

        $queryParams = [
            'IP' => 'true', // Contoh parameter untuk menyertakan data IP
            'URL' => 'true', // Menyertakan data URL
            'Domain' => 'true', // Menyertakan data Domain
            'period' => 'week', // Rentang waktu data
            'limit' => 500, // Batas data yang diambil
            'page' => 1, // Nomor halaman
        ];

        $client = new Client();

        try {
            $response = $client->request('GET', $url, [
                'headers' => [
                    'Authorization' => 'API-Key ' . $apiKey, // Otorisasi dengan API Key
                ],
                'query' => $queryParams, // Parameter query
            ]);

            $data = json_decode($response->getBody(), true); // Decode JSON
            return view('anyrun.threat-intel', compact('data')); // Kirim data ke view
        } catch (\Exception $e) {
            return response()->json([
                'error' => 'Gagal mengambil data dari ANY.RUN: ' . $e->getMessage()
            ]);
        }
    }
}
