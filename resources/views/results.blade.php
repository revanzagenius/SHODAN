<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Hasil Pemindaian</title>
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
    <style>
        body {
            background-color: #f8f9fa;
        }
        .container {
            margin-top: 50px;
        }
        h1 {
            color: #343a40;
            margin-bottom: 30px;
        }
        .card {
            padding: 20px;
            border: none;
            border-radius: 8px;
            box-shadow: 0 2px 10px rgba(0, 0, 0, 0.1);
            margin-bottom: 20px;
        }
        .cve-card {
            border-left: 5px solid #007bff;
        }
        .badge {
            font-size: 14px;
        }
        .clean-vendor {
            color: green;
        }
        .malicious-vendor {
            color: red;
        }
        .unrated-vendor {
            color: orange;
        }
        .vendor-info p {
            margin: 0;
        }
        .vendor-header {
            margin-bottom: 10px;
            font-weight: bold;
        }
        .info-table td, .info-table th {
            padding: 8px;
        }
        .info-table th {
            text-align: left;
        }
        .list-group-item {
            border: 1px solid #ddd;
            margin-bottom: 10px;
        }
    </style>
</head>
<body>
    <div class="container">
        <h1 class="text-center">Hasil Pemindaian</h1>
        <div class="row">
            <!-- Kolom Kiri: Shodan -->
            <div class="col-md-6">
                <div class="card">
                    <h3 class="card-title">Shodan Scanning</h3>
                    <p><strong>IP:</strong> {{ $data['ip'] }}</p>
                    <p><strong>OS:</strong> {{ $data['os'] }}</p>
                    <p><strong>ISP:</strong> {{ $data['isp'] }}</p>
                    <p><strong>Organization:</strong> {{ $data['org'] }}</p>
                    <p><strong>Open Ports:</strong> {{ $data['ports'] }}</p>

                    @if (!empty($data['vulns']))
                        <h4>Kerentanan (CVE): <span class="badge badge-success">{{ count($data['cve_details']) }} ditemukan</span></h4>
                        @foreach ($data['cve_details'] as $cve => $description)
                            <div class="card cve-card">
                                <h5 class="card-title">{{ $cve }}</h5>
                                <p class="card-text">{{ $description }}</p>
                            </div>
                        @endforeach
                    @else
                        <h4>Kerentanan (CVE): <span class="badge badge-danger">Tidak ada kerentanan ditemukan</span></h4>
                    @endif
                </div>
            </div>

           <!-- Kolom Kanan: VirusTotal dan OTX -->
                <div class="col-md-6">
                    <div class="card">
                        <h3 class="card-title">VirusTotal Scanning</h3>

                        @if (isset($data['virus_total']))
                            <div class="card">
                                <h4 class="vendor-header">Community Score</h4>
                                @php
                                    $malicious = $data['virus_total']['attributes']['last_analysis_stats']['malicious'] ?? 0;
                                    $undetected = $data['virus_total']['attributes']['last_analysis_stats']['undetected'] ?? 0;
                                    $total = $malicious + $undetected;
                                    $community_score = ($total > 0) ? round(($malicious / $total) * 100, 2) : 0;
                                @endphp
                                <p><strong>Community Score:</strong> {{ $community_score }}%</p>

                                <h4 class="vendor-header">Last Analysis</h4>
                                <p><strong>Last Analysis Date:</strong> {{ $data['virus_total']['attributes']['last_analysis_date'] ?? 'N/A' }}</p>
                                <p><strong>Malicious Results:</strong> {{ $malicious }}</p>
                                <p><strong>Undetected Results:</strong> {{ $undetected }}</p>
                            </div>

                            <h4 class="vendor-header">Security Vendors' Analysis</h4>
                            <div class="list-group">
                                @foreach ($data['virus_total']['attributes']['last_analysis_results'] as $vendor => $result)
                                    <div class="list-group-item">
                                        <p><strong>{{ $vendor }}:</strong>
                                            @if($result['category'] == 'malicious')
                                                <span class="vendor-status malicious">❌ Malicious</span>
                                            @elseif($result['category'] == 'clean')
                                                <span class="vendor-status clean">✅ Clean</span>
                                            @else
                                                <span class="vendor-status unrated">⚠️ Unrated</span>
                                            @endif
                                        </p>
                                        @if (isset($result['engine_version']))
                                            <p><strong>Engine Version:</strong> {{ $result['engine_version'] }}</p>
                                        @endif
                                    </div>
                                @endforeach
                            </div>
                        @else
                            <p>Gagal mengambil data dari VirusTotal.</p>
                        @endif
                    </div>

                    <!-- Kolom OTX -->
                    <div class="card mt-4">
                        <h3 class="card-title">OTX Scanning</h3>

                        @if (isset($data['otx_data']))
                            <h4 class="vendor-header">OTX Threat Information</h4>
                            <p><strong>IP Address:</strong> {{ $data['ip'] }}</p>
                            <p><strong>Threat Level:</strong> {{ $data['otx_data']['threat_level'] ?? 'N/A' }}</p>
                            <p><strong>Last Update:</strong> {{ $data['otx_data']['last_update'] ?? 'N/A' }}</p>
                            <p><strong>Indicator Count:</strong> {{ $data['otx_data']['indicator_count'] ?? '0' }}</p>

                            <h4 class="vendor-header">Indicators Type</h4>
                            <ul>
                                @foreach ($data['otx_data']['indicators'] as $indicator)
                                    <li>{{ $indicator['type'] }}</li>
                                @endforeach
                            </ul>
                        @else
                            <p>Gagal mengambil data dari OTX.</p>
                        @endif
                    </div>
                </div>
            </div>
        </div>
        <a href="/shodan" class="btn btn-primary mt-3">Kembali</a>
    </div>
</body>
</html>
