@extends('layouts.app')

@section('title', 'Master User')

@section('content')

<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <style>
        body {
            font-family: 'Arial', sans-serif;
            background-color: #f4f7fa;
            margin: 0;
            padding: 0;
            display: flex;
            justify-content: center;
            align-items: center;
            height: 100vh;
            flex-direction: column;
        }

        h1 {
            color: #2c3e50;
            font-size: 2em;
            margin-bottom: 20px;
        }

        #search-form {
            background: #fff;
            padding: 20px;
            border-radius: 8px;
            box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
            width: 100%;
            max-width: 600px;
        }

        #search-form label {
            font-size: 1.1em;
            color: #34495e;
            margin-bottom: 8px;
            display: inline-block;
        }

        #search-form input {
            width: 100%;
            padding: 10px;
            margin: 10px 0 20px;
            border-radius: 4px;
            border: 1px solid #ccc;
            font-size: 1em;
        }

        #search-form button {
            width: 100%;
            padding: 10px;
            background-color: #3498db;
            color: #fff;
            border: none;
            border-radius: 4px;
            font-size: 1.1em;
            cursor: pointer;
            transition: background-color 0.3s ease;
        }

        #search-form button:hover {
            background-color: #2980b9;
        }

        #results {
            margin-top: 30px;
            width: 100%;
            max-width: 600px;
        }

        .result-item {
            background: #fff;
            padding: 15px;
            margin-bottom: 10px;
            border-radius: 8px;
            box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
        }

        .result-item h3 {
            font-size: 1.2em;
            color: #2c3e50;
        }

        .result-item p {
            font-size: 1em;
            color: #34495e;
            margin: 5px 0;
        }

        .no-results {
            background: #fff;
            padding: 15px;
            border-radius: 8px;
            box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
            color: #e74c3c;
            font-size: 1.1em;
            text-align: center;
        }

        /* Responsiveness */
        @media (max-width: 768px) {
            body {
                padding: 10px;
            }

            #search-form {
                width: 100%;
                padding: 15px;
            }
        }
    </style>
</head>

<body>
    <h1>Pencarian Shodan</h1>

    <form id="search-form">
        <label for="query">Masukkan query pencarian:</label>
        <input type="text" id="query" name="query" required placeholder="Contoh: apache">
        <button type="submit">Cari</button>
    </form>

    <div id="results"></div>

    <script>
        $(document).ready(function () {
            $('#search-form').submit(function (e) {
                e.preventDefault();

                var query = $('#query').val();

                $.ajax({
                    url: '{{ route("shodan.search") }}',
                    method: 'GET',
                    data: { query: query },
                    success: function (response) {
                        var resultHtml = '';
                        if (response.matches && response.matches.length > 0) {
                            response.matches.forEach(function (match) {
                                resultHtml += '<div class="result-item">';
                                resultHtml += '<h3>IP: ' + match.ip_str + '</h3>';
                                resultHtml += '<p><strong>Port:</strong> ' + match.port + '</p>';
                                resultHtml += '<p><strong>Hostname:</strong> ' + (match.hostnames.length > 0 ? match.hostnames.join(', ') : 'Tidak ditemukan') + '</p>';
                                resultHtml += '<p><strong>Location:</strong> ' + (match.location.country_name || 'Tidak ditemukan') + '</p>';
                                resultHtml += '</div>';
                            });
                        } else {
                            resultHtml = '<div class="no-results">Tidak ada hasil ditemukan.</div>';
                        }
                        $('#results').html(resultHtml);
                    },
                    error: function () {
                        $('#results').html('<div class="no-results">Gagal melakukan pencarian.</div>');
                    }
                });
            });
        });
    </script>
</body>

@endsection
