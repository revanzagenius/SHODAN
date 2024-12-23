@extends('layouts.app')

@section('title', 'IP Vulnerability Scanner')

@section('content')
<style>
    body {
        background-color: black;
        color: white;
        font-family: Arial, sans-serif;
    }

    .container {
        margin-top: 20px;
    }

    .card {
        background-color: #333;
        color: white;
        border: 1px solid #444;
        margin-bottom: 20px;
    }

    .card-header {
        background-color: #007bff;
        color: white;
    }

    .card-body {
        padding: 15px;
    }

    .table {
        background-color: #444;
        color: white;
        border: 1px solid #555;
    }

    .table th, .table td {
        color: white;
        font-weight: bold;
        text-align: left;
    }

    .table th {
        background-color: #555;
    }

    .btn-info {
        background-color: #007bff;
        border-color: #0056b3;
    }

    .btn-info:hover {
        background-color: #0056b3;
    }

    #scanStatsChart {
        width: 300px;  /* Ukuran chart pie */
        height: 300px; /* Ukuran chart pie */
    }

    .text-danger {
        color: #ff4d4d;
    }
</style>

<body>
    <div class="container mt-5">
        <!-- Form Input -->
        <div class="card">
            <div class="card-header">
                <h5>Scan IP Address</h5>
            </div>
            <div class="card-body">
                <form action="{{ url('/shodan/scan') }}" method="POST">
                    @csrf
                    <div class="form-group">
                        <label for="ip">Input IP :</label>
                        <input type="text" class="form-control" id="ip" name="ip" placeholder="192.168.X.X" required>
                    </div>
                    <button type="submit" class="btn btn-primary btn-block mt-3">Scan IP</button>
                </form>
            </div>
        </div>

        @if(!empty($data['chart_data']))
        <div class="col-lg-12 mt-5">
            <div class="card shadow">
                <div class="card-header bg-info text-white">
                    <h5>Statistik Pemindaian</h5>
                </div>
                <div class="card-body">
                    <canvas id="scanStatsChart"></canvas> <!-- Ukuran chart akan diatur melalui CSS -->
                </div>
            </div>
        </div>
        @endif

        <!-- Leaflet Map -->
        @if(!empty($data['latitude']) && !empty($data['longitude']))
        <div class="col-lg-12 mt-5">
            <div class="card shadow">
                <div class="card-header bg-primary text-white">
                    <h5>Peta Lokasi IP</h5>
                </div>
                <div class="card-body">
                    <div id="map" style="height: 400px;"></div>
                </div>
            </div>
        </div>
        @endif

        <!-- Results -->
        @if(isset($data))
        <div class="row">
            <!-- Hasil Shodan di kiri -->
            <div class="col-md-6">
                <div class="card mt-5">
                    <div class="card-header">
                        <h5>Ringkasan Hasil Shodan</h5>
                    </div>
                    <div class="card-body">
                        <table class="table table-bordered">
                            <tr>
                                <th>IP Address</th>
                                <td>{{ $data['ip'] }}</td>
                            </tr>
                            <tr>
                                <th>OS</th>
                                <td>{{ $data['os'] ?? 'N/A' }}</td>
                            </tr>
                            <tr>
                                <th>ISP</th>
                                <td>{{ $data['isp'] ?? 'N/A' }}</td>
                            </tr>
                        </table>

                        <h5 class="mt-4">Kerentanan Ditemukan (CVE):</h5>
                        @if(!empty($data['cve_details']))
                        <table class="table table-bordered mt-3">
                            <thead>
                                <tr>
                                    <th>CVE</th>
                                    <th>Deskripsi</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($data['cve_details'] as $cve => $description)
                                <tr>
                                    <td>{{ $cve }}</td>
                                    <td>{{ $description }}</td>
                                </tr>
                                @endforeach
                            </tbody>
                        </table>
                        @else
                        <p class="text-danger mt-4">Tidak ada kerentanan yang ditemukan.</p>
                        @endif
                    </div>
                </div>
            </div>

            <!-- Hasil VirusTotal di kanan -->
            <div class="col-md-6">
                <div class="card shadow mt-5">
                    <div class="card-header bg-warning text-dark">
                        <h5 class="mb-0">
                            Ringkasan Hasil Pemindaian VirusTotal
                        </h5>
                    </div>
                    <div class="card-body">
                        <table class="table table-bordered">
                            <thead>
                                <tr>
                                    <th class="cve-item">Parameter</th>
                                    <th class="cve-item">Nilai</th>
                                </tr>
                            </thead>
                            <tbody>
                                <tr>
                                    <th class="cve-item">Community Score</th>
                                    <td class="cve-item">{{ $data['virus_total']['community_score'] ?? 'N/A' }}</td>
                                </tr>
                                <tr>
                                    <th class="cve-item">Tanggal Analisis Terakhir</th>
                                    <td class="cve-item">{{ $data['virus_total']['last_analysis_date'] ?? 'N/A' }}</td>
                                </tr>
                            </tbody>
                        </table>

                        <h5 class="cve-item mt-4">Detail Analisis Vendor Keamanan:</h5>
                        @if(!empty($data['virus_total']['last_analysis_results']))
                        <table class="table table-bordered mt-3">
                            <thead>
                                <tr>
                                    <th class="cve-item">Vendor</th>
                                    <th class="cve-item">Hasil Analisis</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($data['virus_total']['last_analysis_results'] as $vendor => $result)
                                <tr>
                                    <td class="cve-item">{{ $vendor }}</td>
                                    <td class="cve-item">
                                        @if($result['category'] == 'malicious')
                                            <span class="badge cve-item" style="background-color: red; color: white;">Malicious</span>
                                        @elseif($result['category'] == 'clean')
                                            <span class="badge cve-item" style="background-color: green; color: white;">Clean</span>
                                        @else
                                            <span class="badge cve-item" style="background-color: gray; color: white;">Unrated</span>
                                        @endif
                                    </td>
                                </tr>
                                @endforeach
                            </tbody>
                        </table>
                        @else
                        <p class="text-danger cve-item">Tidak ada data analisis vendor keamanan.</p>
                        @endif
                    </div>
                </div>
            </div>
        </div>

        <!-- Hasil OTX di bawah VirusTotal -->
<div class="col-md-6">
    <div class="card shadow mt-5">
        <div class="card-header bg-info text-white">
            <h5 class="mb-0">Ringkasan Hasil Pemindaian OTX</h5>
        </div>
        <div class="card-body">
            <table class="table table-bordered">
                <thead>
                    <tr>
                        <th class="cve-item">Parameter</th>
                        <th class="cve-item">Nilai</th>
                    </tr>
                </thead>
                <tbody>
                    <tr>
                        <th class="cve-item">Threat Level</th>
                        <td class="cve-item">{{ $data['otx']['threat_level'] ?? 'Tidak tersedia' }}</td>
                    </tr>
                    <tr>
                        <th class="cve-item">Deskripsi</th>
                        <td class="cve-item">{{ $data['otx']['description'] ?? 'Tidak tersedia' }}</td>
                    </tr>
                    <tr>
                        <th class="cve-item">Jumlah Pulse</th>
                        <td class="cve-item">{{ $data['otx']['pulse_count'] ?? 'Tidak tersedia' }}</td>
                    </tr>
                    <tr>
                        <th class="cve-item">Pertama Kali Terlihat</th>
                        <td class="cve-item">{{ $data['otx']['first_seen'] ?? 'Tidak tersedia' }}</td>
                    </tr>
                    <tr>
                        <th class="cve-item">Terakhir Kali Terlihat</th>
                        <td class="cve-item">{{ $data['otx']['last_seen'] ?? 'Tidak tersedia' }}</td>
                    </tr>
                </tbody>
            </table>
        </div>
    </div>
</div>

        @endif
</div>

<!-- Scripts -->
@if(!empty($data['latitude']) && !empty($data['longitude']))
<script>
    // Leaflet.js Script
    var map = L.map('map').setView([{{ $data['latitude'] }}, {{ $data['longitude'] }}], 13);
    L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
        maxZoom: 19,
        attribution: '© OpenStreetMap'
    }).addTo(map);
    L.marker([{{ $data['latitude'] }}, {{ $data['longitude'] }}]).addTo(map)
        .bindPopup('<b>IP Address:</b> {{ $data['ip'] }}<br><b>ISP:</b> {{ $data['isp'] ?? "N/A" }}')
        .openPopup();
</script>
@endif

@if(!empty($data['chart_data']))
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
    var ctx = document.getElementById('scanStatsChart').getContext('2d');
    var chartData = {
        labels: ['Port Terbuka', 'Jumlah CVE', 'Malicious Score (VirusTotal)'],
        datasets: [{
            label: 'Statistik Pemindaian',
            data: [
                {{ $data['chart_data']['ports_open'] }},
                {{ $data['chart_data']['cve_count'] }},
                {{ $data['chart_data']['malicious_count'] }}
            ],
            backgroundColor: [
                'rgba(54, 162, 235, 0.7)',
                'rgba(255, 99, 132, 0.7)',
                'rgba(255, 206, 86, 0.7)'
            ],
            borderColor: [
                'rgba(54, 162, 235, 1)',
                'rgba(255, 99, 132, 1)',
                'rgba(255, 206, 86, 1)'
            ],
            borderWidth: 1
        }]
    };

    var scanStatsChart = new Chart(ctx, {
        type: 'pie',
        data: chartData,
        options: {
            responsive: true,
            maintainAspectRatio: false,
            plugins: {
                legend: {
                    position: 'top',
                },
                tooltip: {
                    callbacks: {
                        label: function(tooltipItem) {
                            return tooltipItem.label + ': ' + tooltipItem.raw;
                        }
                    }
                }
            }
        }
    });
</script>
@endif
@endsection
