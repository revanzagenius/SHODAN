<?php

namespace App\Console\Commands;

use App\Models\Port;
use GuzzleHttp\Client;
use App\Models\ShodanHost;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Mail;

class CheckNewPorts extends Command
{
    protected $signature = 'ports:check';
    protected $description = 'Check for new open ports from Shodan and send email notifications.';

    protected $client;
    protected $shodanApiKey;

    public function __construct()
    {
        parent::__construct();
        $this->client = new Client();
        $this->shodanApiKey = env('SHODAN_API_KEY');  // API Key Shodan
    }

    public function handle()
    {
        $hosts = ShodanHost::all(); // Ambil semua host yang ada di database

        foreach ($hosts as $host) {
            $this->checkPorts($host);
        }

        $this->info('Port check completed.');
    }

    public function checkPorts($host)
    {
        try {
            // Memanggil API Shodan untuk memeriksa port
            $response = $this->client->get("https://api.shodan.io/shodan/host/{$host->ip}?key={$this->shodanApiKey}");
            $shodanData = json_decode($response->getBody(), true);

            if ($shodanData && isset($shodanData['ports'])) {
                $serviceData = $shodanData['ports']; // Ambil data port

                foreach ($serviceData as $portNumber) {
                    $this->processPort($host, $portNumber);
                }
            }
        } catch (\Exception $e) {
            $this->error("Error checking ports for IP {$host->ip}: " . $e->getMessage());
        }
    }

    public function processPort($host, $portNumber)
    {
        // Cek apakah port sudah ada
        $existingPort = Port::where('shodan_host_id', $host->id)
                            ->where('port_number', $portNumber)
                            ->first();

        if (!$existingPort) {
            // Jika belum ada, simpan port baru
            $port = $host->ports()->create([
                'port_number' => $portNumber,
                'asset_group' => 'Unknown', // Sesuaikan dengan data yang Anda butuhkan
                'trigger' => 'New port detected',
                'version' => 'Unknown', // Sesuaikan dengan data yang Anda butuhkan
                'details' => json_encode(['port' => $portNumber]),
            ]);

            // Kirim email ke pengguna tentang port baru
            $this->sendPortNewEmail($host->email, $port);
        }
    }

    public function sendPortNewEmail($email, $port)
    {
        $data = [
            'port' => $port->port_number,
            'asset_group' => $port->asset_group,
            'trigger' => $port->trigger,
            'version' => $port->version,
            'details' => $port->details,
        ];

        // Mengirim email ke pengguna
        Mail::send('emails.new_port', $data, function ($message) use ($email) {
            $message->to($email)
                    ->subject('New Open Port Detected');
        });
    }
}
