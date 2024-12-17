<!DOCTYPE html>
<html>
<head>
    <title>Shodan Service Update Notification</title>
</head>
<body>
    <h2>Shodan Alert - Service Update Detected</h2>
    <p><strong>IP Address:</strong> {{ $host->ip }}</p>
    <p><strong>Country:</strong> {{ $host->country }}</p>
    <p><strong>City:</strong> {{ $host->city }}</p>

    <h4>New Service Banners:</h4>
    <ul>
        @foreach ($newServiceBanners as $service)
            <li>Port: {{ $service['port'] }} - Banner: {{ $service['banner'] }}</li>
        @endforeach
    </ul>
</body>
</html>
