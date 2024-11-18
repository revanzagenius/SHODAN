<!DOCTYPE html>
<html>
<head>
    <title>Dashboard Shodan Monitor</title>
</head>
<body>
    <h1>Dashboard Shodan Monitor</h1>

    @if(session('status'))
        <p>{{ session('status')['message'] }}</p>
    @endif

    <h2>Monitored IPs</h2>
    <ul>
        @foreach($monitoredIps['data'] ?? [] as $ip)
            <li>{{ $ip }}</li>
        @endforeach
    </ul>

    <h2>Add IP to Monitor</h2>
    <form method="POST" action="{{ route('dashboard.addMonitor') }}">
        @csrf
        <input type="text" name="ip" placeholder="Enter IP">
        <button type="submit">Add</button>
    </form>
</body>
</html>
