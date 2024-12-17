@extends('layouts.app')

@section('title', 'IP Vulnerability Scanner')

@section('content')
<style>
    body {
    background-color: black;
    color: white;
}

.container {
    background-color: black;
    color: white;
}

.card {
    background-color: #333; /* Gelap untuk card */
    color: white;
}

.table {
    background-color: #555; /* Abu-abu untuk tabel */
    color: white;
}

.table th, .table td {
    color: white; /* Pastikan teks di tabel berwarna putih */
}

.card-header {
    background-color: #007bff; /* Ubah warna header jika perlu */
    color: white;
}

.btn-primary, .btn-info, .btn-secondary {
    background-color: #007bff; /* Sesuaikan warna tombol */
    border-color: #0056b3;
}

.text-center a {
    background-color: #6c757d;
    color: white;
}

    /* CSS tambahan untuk teks CVE */
    .cve-item {
        color: white; /* Warna putih */
        font-weight: bold; /* Teks tebal */
    }
</style>
<body>
    <div class="container" style="margin-left: 250px;">
        <div class="card">
            <form action="{{ url('/shodan/scan') }}" method="POST">
                @csrf
                <div class="form-group">
                    <label for="ip">Input IP :</label>
                    <input type="text" class="form-control" id="ip" name="ip" placeholder="192.168.X.X" required>
                </div>
                <button type="submit" class="btn btn-primary btn-block" style="margin-top : 10px;">Scan IP</button>
            </form>
        </div>

        @if(isset($data))
        <div class="row mt-5">
            <!-- Shodan Results (Summary) -->
            <div class="col-lg-12">
                <div class="card shadow">
                    <div class="card-header bg-success text-white">
                        <h5 class="mb-0" data-bs-toggle="collapse" data-bs-target="#shodanSummary" aria-expanded="false" aria-controls="shodanSummary">
                            Ringkasan Hasil Pemindaian Shodan
                        </h5>
                    </div>
                    <div class="collapse" id="shodanSummary">
                        <div class="card-body">
                            <table class="table table-bordered">
                                <tbody>
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
                                </tbody>
                            </table>

                            <button class="btn btn-info" data-bs-toggle="collapse" data-bs-target="#shodanDetails">Lihat Detail</button>
                            <div class="collapse mt-3" id="shodanDetails">
                                <tr>
                                    <th>Port Terbuka</th>
                                    <td>{{ implode(', ', $data['ports']) }}</td>
                                </tr>

                                @if(!empty($data['cve_details']))
                                <h6 class="mt-4">Kerentanan Ditemukan (CVE):</h6>
                                <ul class="list-group">
                                    @foreach($data['cve_details'] as $cve => $description)
                                        <li class="list-group-item">
                                            <strong>{{ $cve }}</strong>: {{ $description }}
                                        </li>
                                    @endforeach
                                </ul>
                            @else
                                <p class="text-danger mt-4">Tidak ada kerentanan yang ditemukan.</p>
                            @endif
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- VirusTotal Results (Summary) -->
            <div class="col-lg-12 mt-5">
                <div class="card shadow">
                    <div class="card-header bg-warning text-dark">
                        <h5 class="mb-0" data-bs-toggle="collapse" data-bs-target="#virusTotalSummary" aria-expanded="false" aria-controls="virusTotalSummary">
                            Ringkasan Hasil Pemindaian VirusTotal
                        </h5>
                    </div>
                    <div class="collapse" id="virusTotalSummary">
                        <div class="card-body">
                            <table class="table table-bordered">
                                <tbody>
                                    <tr>
                                        <th>Community Score</th>
                                        <td>{{ $data['virus_total']['community_score'] }}</td>
                                    </tr>
                                    <tr>
                                        <th>Tanggal Analisis Terakhir</th>
                                        <td>{{ $data['virus_total']['last_analysis_date'] }}</td>
                                    </tr>
                                </tbody>
                            </table>

                            <button class="btn btn-info" data-bs-toggle="collapse" data-bs-target="#virusTotalDetails">Lihat Detail</button>
                            <div class="collapse mt-3" id="virusTotalDetails">
                                <h6>Detail Analisis Vendor Keamanan:</h6>
                                <ul class="list-group">
                                    @foreach($data['virus_total']['last_analysis_results'] as $vendor => $result)
                                        <li class="list-group-item d-flex justify-content-between align-items-center">
                                            {{ $vendor }}
                                            @if($result['category'] == 'malicious')
                                                <span class="badge bg-danger">Malicious</span>
                                            @elseif($result['category'] == 'clean')
                                                <span class="badge bg-success">Clean</span>
                                            @else
                                                <span class="badge bg-secondary">Unrated</span>
                                            @endif
                                        </li>
                                    @endforeach
                                </ul>
                            </div>
                        </div>
                    </div>
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

        <!-- Chart.js -->
        @if(!empty($data['chart_data']))
        <div class="col-lg-12 mt-5">
            <div class="card shadow">
                <div class="card-header bg-info text-white">
                    <h5>Statistik Pemindaian</h5>
                </div>
                <div class="card-body">
                    <canvas id="scanStatsChart"></canvas>
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
    // Chart.js Script
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
        type: 'bar',
        data: chartData,
        options: {
            scales: {
                y: {
                    beginAtZero: true
                }
            }
        }
    });
</script>
@endif
@endsection
