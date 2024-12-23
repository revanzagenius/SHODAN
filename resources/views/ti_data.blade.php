<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Threat Intelligence Feed</title>
    <style>
        body { font-family: Arial, sans-serif; margin: 20px; }
        table { border-collapse: collapse; width: 100%; }
        table, th, td { border: 1px solid black; padding: 8px; }
        th { background-color: #f2f2f2; }
    </style>
</head>
<body>
    <h1>Threat Intelligence Feed</h1>
    @if(isset($data['objects']) && !empty($data['objects']))
        <table>
            <thead>
                <tr>
                    <th>Value</th>
                    <th>Created</th>
                    <th>Modified</th>
                    <th>External Reference</th>
                </tr>
            </thead>
            <tbody>
                @foreach($data['objects'] as $ioc)
                    <tr>
                        <td>{{ $ioc['value'] ?? 'N/A' }}</td>
                        <td>{{ $ioc['created'] ?? 'N/A' }}</td>
                        <td>{{ $ioc['modified'] ?? 'N/A' }}</td>
                        <td>
                            @foreach($ioc['external_references'] as $ref)
                                <a href="{{ $ref['url'] }}" target="_blank">{{ $ref['source_name'] }}</a>
                            @endforeach
                        </td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    @else
        <p>No data available.</p>
    @endif
</body>
</html>
