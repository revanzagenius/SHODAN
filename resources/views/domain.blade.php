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

        /* Tabel Styling */
        .table-container {
            margin-top: 20px;
        }

        .table th, .table td {
            text-align: center;
        }

        .btn-custom {
            background-color: #3498db;
            border-radius: 5px;
            padding: 10px;
            text-align: center;
            font-size: 1rem;
            text-decoration: none;
            display: inline-block;
        }

        .btn-custom:hover {
            background-color: #2980b9;
        }

        /* Menambahkan margin-top pada tombol + Tambah Domain */
        .btn-success {
            margin-top: 100px; /* Atur sesuai dengan kebutuhan agar tombol tidak tertutup navbar */
        }

        /* Agar modal muncul di atas tabel */
        .modal-dialog {
            margin-top: 20px; /* Menambahkan jarak atas untuk memastikan modal tidak tertutup oleh navbar */
        }


        .countdown {
            font-weight: bold;
            color: red;
        }
    </style>

    <!-- Menampilkan pesan sukses atau error -->
    @if(session('success'))
        <p class="alert alert-success">{{ session('success') }}</p>
    @endif

    @if(session('error'))
        <p class="alert alert-danger">{{ session('error') }}</p>
    @endif

    <!-- Tombol untuk tambah domain (ditempatkan di atas tabel) -->
    <button class="btn btn-success" data-bs-toggle="modal" data-bs-target="#exampleModal">
        + Tambah Domain
    </button>

     <!-- Modal untuk tambah domain (ditempatkan di atas tabel) -->
     <div class="modal fade" id="exampleModal" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
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

    <!-- Domain List (Tabel yang menampilkan daftar domain) -->
    <div class="table-container">
        <table class="table table-dark table-bordered">
            <thead>
                <tr>
                    <th>Domain Name</th>
                    <th>Expiry Date</th>
                    <th>Registrar</th>
                    <th>Created Date</th>
                    <th>Updated Date</th>
                    <th>Name Servers</th>
                    <th>Status</th>
                    <th>Countdown</th>
                    <th>Action</th>
                </tr>
            </thead>
            <tbody>
                @foreach ($domains as $domain)
                    <tr>
                        <td>{{ $domain->domain_name }}</td>
                        <td>{{ $domain->expiry_date }}</td>
                        <td>{{ $domain->registrar_name }}</td>
                        <td>{{ $domain->created_date }}</td>
                        <td>{{ $domain->updated_date }}</td>
                        <td>
                            <ul>
                                @foreach (json_decode($domain->name_servers, true) as $nameServer)
                                    <li>{{ $nameServer }}</li>
                                @endforeach
                            </ul>
                        </td>
                        <td>{{ $domain->domain_status }}</td>
                        <td class="countdown" data-expiry="{{ $domain->expiry_date }}"></td>
                        <td>
                            <a href="{{ route('domains.downloadPdf') }}" class="btn-custom">Unduh Laporan PDF</a>
                        </td>
                    </tr>
                @endforeach
            </tbody>
        </table>
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



@endsection
