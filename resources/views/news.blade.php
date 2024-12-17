@extends('layouts.app')

@section('title', 'IP Vulnerability Scanner')

@section('content')
    <style>
        body {
            background-color: #000; /* Background hitam */
            color: #fff; /* Tulisan putih */
        }

        .news-card {
            background-color: #333; /* Kartu abu-abu */
            color: #fff; /* Tulisan putih */
            margin-bottom: 20px;
            display: flex;
            flex-direction: column;
            height: 100%;
            border-radius: 10px;
            overflow: hidden;
        }

        .news-title {
            font-size: 1.2rem;
            font-weight: bold;
            margin-bottom: 10px;
        }

        .news-description {
            color: #ccc; /* Warna abu-abu untuk deskripsi */
            margin-bottom: 20px;
        }

        .card-img-top {
            height: 200px;
            object-fit: cover; /* Agar gambar tetap proporsional */
        }

        .btn-primary {
            background-color: #007bff;
            border-color: #007bff;
        }

        .btn-primary:hover {
            background-color: #0056b3;
            border-color: #0056b3;
        }

        /* Menjaga ukuran card agar konsisten */
        .card {
            flex: 1;
            display: flex;
            flex-direction: column;
            justify-content: space-between;
        }

        .row.g-4 {
            display: flex;
            flex-wrap: wrap;
        }

        .col-md-4 {
            display: flex;
            justify-content: center;
        }
    </style>

    <div class="container my-5">
        <!-- Berita Terbaru -->
        <h2>Latest News</h2>
        <div class="row g-4">
            @foreach (array_slice($latestNews, 0, 9) as $article) <!-- Menggunakan array_slice untuk mengambil 6 berita -->
                <div class="col-md-4">
                    <div class="card news-card">
                        <img src="{{ $article['urlToImage'] ?? 'https://via.placeholder.com/400x200?text=No+Image' }}"
                             class="card-img-top"
                             alt="News Image">
                        <div class="card-body d-flex flex-column">
                            <h5 class="news-title">{{ $article['title'] }}</h5>
                            <p class="news-description">{{ Str::limit($article['description'], 100) }}</p>
                            <a href="{{ $article['url'] }}"
                               target="_blank"
                               class="btn btn-primary btn-sm mt-auto">Read More</a>
                        </div>
                    </div>
                </div>
            @endforeach
        </div>

        <!-- Berita Lainnya -->
        <h2 class=" mt-5 ">Other News</h2>
        <div class="row g-4 ">
            @foreach ($otherNews as $article)
                <div class="col-md-4">
                    <div class="card news-card">
                        <img src="{{ $article['urlToImage'] ?? 'https://via.placeholder.com/400x200?text=No+Image' }}"
                             class="card-img-top"
                             alt="News Image">
                        <div class="card-body d-flex flex-column">
                            <h5 class="news-title">{{ $article['title'] }}</h5>
                            <p class="news-description">{{ Str::limit($article['description'], 100) }}</p>
                            <a href="{{ $article['url'] }}"
                               target="_blank"
                               class="btn btn-primary btn-sm mt-auto">Read More</a>
                        </div>
                    </div>
                </div>
            @endforeach
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
@endsection
