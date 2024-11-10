<!DOCTYPE html>
<html>
<head>
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
    </style>
</head>
<body>
    <div class="container">
        <h1 class="text-center">Hasil Pemindaian</h1>
        <div class="card">
            <p><strong>IP:</strong> {{ $data['ip'] }}</p>
            <p><strong>OS:</strong> {{ $data['os'] }}</p>
            <p><strong>ISP:</strong> {{ $data['isp'] }}</p>
            <p><strong>Organization:</strong> {{ $data['org'] }}</p>
            <p><strong>Open Ports:</strong> {{ $data['ports'] }}</p>

            @if (!empty($data['vulns']))
                <h2>Kerentanan (CVE): <span class="badge badge-success">{{ count($data['cve_details']) }} ditemukan</span></h2>
                @foreach ($data['cve_details'] as $cve => $description)
                    <div class="card cve-card">
                        <h5 class="card-title">{{ $cve }}</h5>
                        <p class="card-text">{{ $description }}</p>
                    </div>
                @endforeach
            @else
                <h2>Kerentanan (CVE): <span class="badge badge-danger">Tidak ada kerentanan ditemukan</span></h2>
            @endif

            <a href="/shodan" class="btn btn-primary mt-3">Kembali</a>
        </div>
    </div>
</body>
</html>
