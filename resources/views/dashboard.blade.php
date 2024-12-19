@extends('layouts.app')

@section('content')
    <div class="container-fluid" style="background: linear-gradient(to bottom, #000000, #000000); color: white; min-height: 100vh; padding: 20px; margin-top: 30px;">

        <!-- Menampilkan pesan sukses atau error -->
        @if (session('success'))
            <div class="alert alert-success">
                {{ session('success') }}
            </div>
        @endif

        @if ($errors->any())
            <div class="alert alert-danger">
                <ul>
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        <!-- Button untuk menampilkan form -->
        <button class="btn btn-primary mb-4" id="toggleFormButton">+</button>

        <!-- Form disembunyikan secara default -->
        <div id="scanForm" style="display: none; margin-bottom: 20px;">
            <form action="{{ route('scan') }}" method="POST">
                @csrf
                <div class="form-group">
                    <label for="ip">Enter IP to Scan:</label>
                    <input type="text" name="ip" id="ip" class="form-control" placeholder="Enter IP address" required>
                </div>
                <div class="form-group">
                    <label for="email">Enter Your Email for Notifications:</label>
                    <input type="email" name="email" id="email" class="form-control" placeholder="Enter your email" required>
                </div>
                <button type="submit" class="btn btn-success mt-3">Save</button>
            </form>
        </div>

        <!-- Menampilkan hasil IP yang telah dipindai -->
        {{-- <h2 class="text-white">Scanned IPs</h2> --}}
        @if ($hosts->isEmpty())
            <p>No data available. Please scan an IP.</p>
        @else
            <div class="row">
                @foreach ($hosts as $host)
                    <div class="col-md-4 mb-4">
                        <!-- Card untuk setiap host -->
                        <div class="card h-100 d-flex flex-column" style="background-color: #3a3a3a; color: white; border: 1px solid #555; border-radius: 8px; min-height: 350px;">
                            <div class="card-body d-flex flex-column">
                                <h5 class="card-title mb-3 text-center">Details</h5>
                                <table class="table table-dark table-bordered mb-4">
                                    <tbody>
                                        <tr>
                                            <th scope="row">IP</th>
                                            <td>{{ $host->ip }}</td>
                                        </tr>
                                        <tr>
                                            <th scope="row">Country</th>
                                            <td>{{ $host->country }}</td>
                                        </tr>
                                        <tr>
                                            <th scope="row">City</th>
                                            <td>{{ $host->city }}</td>
                                        </tr>
                                        <tr>
                                            <th scope="row">Open Ports</th>
                                            <td>
                                                @php
                                                    $ports = json_decode($host->ports);
                                                    echo is_array($ports) ? implode(', ', $ports) : 'N/A';
                                                @endphp
                                            </td>
                                        </tr>
                                    </tbody>
                                </table>

                                <div class="mt-auto">
                                    <!-- Tombol untuk menampilkan lebih banyak detail -->
                                    <a href="{{ route('result', ['id' => $host->id]) }}" class="btn btn-info w-100 mb-2">
                                        Show Details
                                    </a>
                                    <a href="{{ route('dashboard.exportPdf', $host->id) }}" class="btn btn-success w-100">
                                        Export to PDF
                                    </a>
                                </div>
                            </div>
                        </div>
                    </div>
                @endforeach
            </div>
        @endif
    </div>

    <!-- Script untuk toggle form -->
    <script>
        document.getElementById('toggleFormButton').addEventListener('click', function() {
            const form = document.getElementById('scanForm');
            if (form.style.display === 'none' || form.style.display === '') {
                form.style.display = 'block';
            } else {
                form.style.display = 'none';
            }
        });
    </script>
@endsection
