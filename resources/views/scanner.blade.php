@extends('layouts.app')

@section('title', 'Master User')

@section('content')
<body>
    <div class="container" style="margin-left: 250px;">
        <h1 class="text-center">IP Scanner</h1>
        <div class="card">
            <form action="/shodan/scan" method="POST">
                @csrf
                <div class="form-group">
                    <label for="ip">Masukkan IP address:</label>
                    <input type="text" class="form-control" id="ip" name="ip" placeholder="Contoh: 192.168.1.1" required>
                </div>
                <button type="submit" class="btn btn-primary btn-block" style="margin-top : 10px;">Scan IP</button>
            </form>
        </div>
    </div>
</body>
@endsection
