<?php

// app/Http/Controllers/DashboardController.php

namespace App\Http\Controllers;

use GuzzleHttp\Client;
use App\Models\ShodanHost;
use Illuminate\Http\Request;
use Barryvdh\DomPDF\Facade\Pdf;

class DashboardController extends Controller
{
    protected $client;
    protected $shodanApiKey;

    public function __construct()
    {
        $this->client = new Client();
        $this->shodanApiKey = env('SHODAN_API_KEY');  // Ambil API Key dari .env
    }

    // Method untuk menampilkan dashboard
    public function index()
    {
        // Ambil semua data ShodanHost dari database
        $hosts = ShodanHost::all();

        // Kirim data ke view 'dashboard'
        return view('dashboard', compact('hosts'));
    }

    public function showResult($id)
    {
        // Ambil data host berdasarkan ID
        $host = ShodanHost::findOrFail($id);

        // Kirim data ke view 'result'
        return view('result', compact('host'));
    }

    public function scan(Request $request)
    {
        $ip = $request->input('ip');
        $data = [];

        try {
            // Mengambil informasi host dari Shodan
            $response = $this->client->get("https://api.shodan.io/shodan/host/$ip?key={$this->shodanApiKey}");
            $result = json_decode($response->getBody(), true);

            // Ambil banner services
            $serviceBanners = [];
            if (isset($result['data'])) {
                foreach ($result['data'] as $service) {
                    $serviceBanners[] = [
                        'port' => $service['port'],
                        'banner' => $service['data'] ?? 'No banner found',
                    ];
                }
            }

            // Menyimpan data hasil scan ke dalam database
            $host = ShodanHost::create([
                'ip' => $result['ip_str'] ?? 'N/A',
                'hostnames' => json_encode($result['hostnames'] ?? []),
                'country' => $result['country_name'] ?? 'N/A',
                'city' => $result['city'] ?? 'N/A',
                'ports' => json_encode($result['ports'] ?? []),
                'vulns' => json_encode($result['vulns'] ?? []),
                'isp' => $result['isp'] ?? 'N/A',
                'domains' => json_encode($result['domains'] ?? []),
                'organizations' => json_encode($result['organizations'] ?? []),
                'asn' => $result['asn'] ?? 'N/A',
                'service_banners' => json_encode($serviceBanners),  // Simpan banner
            ]);

            // Mengambil deskripsi CVE dari Circle.LU API
            $cveDetails = [];
            foreach ($result['vulns'] as $cve) {
                try {
                    $cveResponse = $this->client->get("https://cve.circl.lu/api/cve/$cve");
                    $cveData = json_decode($cveResponse->getBody(), true);
                    $cveDetails[$cve] = $cveData['summary'] ?? 'Tidak ada deskripsi';
                } catch (\Exception $e) {
                    $cveDetails[$cve] = 'Gagal mengambil data CVE: ' . $e->getMessage();
                }
            }

            // Menyimpan deskripsi CVE ke dalam database
            $host->cve_details = json_encode($cveDetails);
            $host->save();

            return redirect()->route('dashboard.index')->with('success', 'IP scanned and data saved successfully!');
        } catch (\Exception $e) {
            return redirect()->route('dashboard.index')->withErrors('Failed to scan IP: ' . $e->getMessage());
        }
    }

    // Fungsi untuk menampilkan PDF
    public function exportPdf($id)
    {
        // Ambil data host berdasarkan ID
        $host = ShodanHost::findOrFail($id);

        // Menghasilkan PDF menggunakan view yang sudah ada
        $pdf = Pdf::loadView('pdf', compact('host'));

        // Menyajikan PDF sebagai download
        return $pdf->download("scan_result_{$host->ip}.pdf");
    }

}
