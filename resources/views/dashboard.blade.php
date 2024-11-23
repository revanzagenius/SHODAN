@extends('layouts.app')

@section('content')
    <div class="container">
        <h1>Shodan Dashboard</h1>

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

        <!-- Form untuk memasukkan IP dan memulai pemindaian -->
        <form action="{{ route('scan') }}" method="POST">
            @csrf
            <div class="form-group">
                <label for="ip">Enter IP to Scan:</label>
                <input type="text" name="ip" id="ip" class="form-control" placeholder="Enter IP address" required>
            </div>
            <button type="submit" class="btn btn-primary">Scan IP</button>
        </form>

        <hr>

        <!-- Menampilkan hasil IP yang telah dipindai -->
        <h2>Scanned IPs</h2>
        @if ($hosts->isEmpty())
            <p>No data available. Please scan an IP.</p>
        @else
            <div class="row">
                @foreach ($hosts as $host)
                    <div class="col-md-4 mb-3">
                        <!-- Card untuk setiap host -->
                        <div class="card">
                            <div class="card-body">
                                <h5 class="card-title">IP: {{ $host->ip }}</h5>
                                <p class="card-text"><strong>Country:</strong> {{ $host->country }}</p>
                                <p class="card-text"><strong>City:</strong> {{ $host->city }}</p>
                                <p class="card-text"><strong>Open Ports:</strong>
                                    @php
                                        $ports = json_decode($host->ports);
                                        echo is_array($ports) ? implode(', ', $ports) : 'N/A';
                                    @endphp
                                </p>

                                <!-- Tombol untuk menampilkan lebih banyak detail -->
                                <a href="{{ route('result', ['id' => $host->id]) }}" class="btn btn-info">
                                    Show Details
                                </a>
                            </div>
                        </div>
                    </div>
                @endforeach
            </div>
        @endif
    </div>
@endsection
