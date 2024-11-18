{{-- <!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dashboard Monitoring IP Publik</title>
    <link rel="stylesheet" href="{{ asset('css/app.css') }}">
</head>
<body>
    <h1>Daftar IP yang Dimonitor</h1>

    <form action="{{ route('dashboard.add.ip') }}" method="POST">
        @csrf
        <label for="ip_address">Masukkan IP Publik:</label>
        <input type="text" name="ip_address" id="ip_address" required>
        <button type="submit">Tambahkan</button>
    </form>

    <table border="1" cellpadding="10">
        <thead>
            <tr>
                <th>IP Address</th>
                <th>Port Terbuka</th>
                <th>Layanan</th>
                <th>Kerentanan</th>
            </tr>
        </thead>
        <tbody>
            @foreach($ips as $ip)
                <tr>
                    <td>{{ $ip->ip_address }}</td>
                    <td>{{ implode(', ', json_decode($ip->open_ports) ?? []) }}</td>
                    <td>{{ implode(', ', json_decode($ip->services) ?? []) }}</td>
                    <td>
                        @if (!empty($ip->vulnerabilities))
                            @foreach (json_decode($ip->vulnerabilities) as $vuln)
                                <span>{{ $vuln }}</span><br>
                            @endforeach
                        @else
                            Tidak ada kerentanan
                        @endif
                    </td>
                </tr>
            @endforeach
        </tbody>
    </table>
</body>
</html> --}}
