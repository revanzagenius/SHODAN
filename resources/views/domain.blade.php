@extends('layouts.app')

@section('title', 'IP Vulnerability Scanner')

@section('content')
    <style>
        /* Styling dasar */
        body {
            background-color: #000000;
            color: white;
            font-family: 'Arial', sans-serif;
            padding: 20px;
        }

        h1, h2 {
            color: #ecf0f1;
            font-size: 2rem;
            margin-bottom: 20px;
        }

        /* Card Styling */
        .card {
            background-color: #3a3a3a;
            color: white;
            border-radius: 8px;
            border: none;
            margin-bottom: 20px;
            display: flex;
            flex-direction: column;
            height: 100%;
        }

        .card-header {
            background-color: #838885;
            border-radius: 8px 8px 0 0;
            padding: 10px;
            font-size: 1.5rem;
        }

        .card-body {
            padding: 15px;
            flex-grow: 1; /* Membuat card body tumbuh untuk mengisi ruang */
        }

        .card-footer {
            text-align: center;
            padding: 10px;
        }

        .btn-custom {
            background-color: #3498db;
            border-radius: 5px;
            padding: 10px;
            width: 100%;
            text-align: center;
            font-size: 1rem;
            text-decoration: none;
        }

        .btn-custom:hover {
            background-color: #2980b9;
        }
        .btn-success {
    margin-top: 100px; /* Memberikan jarak dari navbar */
}

        /* Countdown Styling */
        .countdown {
            font-weight: bold;
            color: red;
        }

        /* Responsiveness */
        @media (max-width: 768px) {
            .card {
                margin-bottom: 15px;
            }
        }
    </style>

    <!-- Menampilkan pesan sukses atau error -->
    @if(session('success'))
        <p class="alert alert-success">{{ session('success') }}</p>
    @endif

    @if(session('error'))
        <p class="alert alert-danger">{{ session('error') }}</p>
    @endif

    <!-- Tombol untuk tambah domain -->
    <button class="btn btn-success" data-bs-toggle="modal" data-bs-target="#exampleModal">
        + Tambah Domain
    </button>

    <!-- Domain List (Tabel yang menampilkan daftar domain) -->
    <div class="row" style="margin-top: 10px">
        @foreach ($domains as $domain)
            <div class="col-md-4">
                <div class="card">
                    <div class="card-header text-center">
                        {{ $domain->domain_name }}
                    </div>
                    <div class="card-body">
                        <table class="table table-dark table-bordered">
                            <tbody>
                                <tr>
                                    <th scope="row">Expiry Date</th>
                                    <td>{{ $domain->expiry_date }}</td>
                                </tr>
                                <tr>
                                    <th scope="row">Registrar</th>
                                    <td>{{ $domain->registrar_name }}</td>
                                </tr>
                                <tr>
                                    <th scope="row">Created Date</th>
                                    <td>{{ $domain->created_date }}</td>
                                </tr>
                                <tr>
                                    <th scope="row">Updated Date</th>
                                    <td>{{ $domain->updated_date }}</td>
                                </tr>
                                <tr>
                                    <th scope="row">Name Servers</th>
                                    <td>
                                        <ul>
                                            @foreach (json_decode($domain->name_servers, true) as $nameServer)
                                                <li>{{ $nameServer }}</li>
                                            @endforeach
                                        </ul>
                                    </td>
                                </tr>
                                <tr>
                                    <th scope="row">Status</th>
                                    <td>{{ $domain->domain_status }}</td>
                                </tr>
                            </tbody>
                        </table>
                        <div class="countdown" data-expiry="{{ $domain->expiry_date }}"></div>
                    </div>
                    <div class="card-footer">
                        <a href="{{ route('domains.downloadPdf') }}" class="btn-custom">Unduh Laporan PDF</a>
                    </div>
                </div>
            </div>
        @endforeach
    </div>

    <!-- Countdown Script -->
    <script>
        const countdownElements = document.querySelectorAll('.countdown');

        countdownElements.forEach(element => {
            const expiryDate = new Date(element.dataset.expiry);

            function updateCountdown() {
                const now = new Date();
                const timeLeft = expiryDate - now;

                if (timeLeft > 0) {
                    const days = Math.floor(timeLeft / (1000 * 60 * 60 * 24));
                    element.textContent = `${days} hari lagi`;
                } else {
                    element.textContent = "Expired";
                }
            }

            updateCountdown();
            setInterval(updateCountdown, 1000);
        });
    </script>

    <!-- Modal untuk tambah domain -->
    <div class="modal fade" id="exampleModal" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="exampleModalLabel">Tambah Domain</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <form action="{{ route('domains.store') }}" method="POST">
                        @csrf
                        <div class="mb-3">
                            <label for="domain" class="form-label">Masukkan URL Domain</label>
                            <input type="url" name="domain" id="domain" class="form-control" placeholder="Masukkan URL domain" required>
                        </div>
                        <button type="submit" class="btn btn-primary">Tambah Domain</button>
                    </form>
                </div>
            </div>
        </div>
    </div>

@endsection
