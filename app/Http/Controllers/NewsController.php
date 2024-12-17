<?php

namespace App\Http\Controllers;

use App\Services\NewsAPIService; // Gunakan NewsAPIService
use Illuminate\Http\Request;

class NewsController extends Controller
{
    protected $newsService;

    public function __construct(NewsAPIService $newsService)
    {
        $this->newsService = $newsService;
    }

    public function index()
    {
        // Ambil berita terbaru dan berita lainnya dari API
        $news = $this->newsService->getLatestNews();

        // Pisahkan berita menjadi dua kategori
        $latestNews = array_slice($news['articles'], 0, 9); // Ambil 5 berita terbaru
        $otherNews = array_slice($news['articles'], 5); // Berita lainnya

        return view('news', compact('latestNews', 'otherNews'));
    }
}
