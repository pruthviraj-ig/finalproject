<?php 

namespace App\Controllers;

use App\Models\MovieModel;
use App\Models\ReviewModel;
use CodeIgniter\Controller;

class MovieController extends Controller
{
    private $apiKey = 'ef89affb';  // Your OMDb API Key

    public function index()
    {
        $model = new MovieModel();
        $reviewModel = new ReviewModel();

        $search = $this->request->getGet('search');
        $data['movies'] = [];
        $data['search'] = $search;
        $data['apiMovies'] = null;

        if ($search) {
            $localMovies = $model->like('title', $search)->findAll();
            $data['movies'] = $localMovies;

            $apiMovies = $this->fetchMovieDetails($search);
            if ($apiMovies && $apiMovies['Response'] === "True") {
                $data['apiMovies'] = $apiMovies['Search'];
            }
        } else {
            $data['movies'] = $model->findAll();
        }

        foreach ($data['movies'] as &$movie) {
            $movie['reviews'] = $reviewModel->where('movie_id', $movie['id'])->findAll();
            $movie['average_rating'] = $reviewModel->getAverageRating($movie['id']) ?? 0;
        }

        return view('movie_list', $data);
    }

    public function saveMovieAndRedirect()
    {
        $model = new MovieModel();

        $data = [
            'title' => $this->request->getPost('title'),
            'description' => $this->request->getPost('description'),
            'release_date' => $this->request->getPost('release_date'),
            'poster' => $this->request->getPost('poster'),
        ];

        $existingMovie = $model->where('title', $data['title'])->first();
        if (!$existingMovie) {
            $model->save($data);
            $movieId = $model->insertID();
        } else {
            $movieId = $existingMovie['id'];
        }

        return $this->response->setJSON(['redirect' => base_url('/movie-detail/' . $movieId)]);
    }

    public function detail($id)
    {
        $model = new MovieModel();
        $reviewModel = new ReviewModel();

        $movie = $model->find($id);
        if ($movie) {
            $apiMovieDetails = $this->fetchDetailedMovieInfo($movie['title']);
            if ($apiMovieDetails) {
                $movie = array_merge($movie, $apiMovieDetails);  // Combine DB data with API data
            }
        }

        $reviews = $reviewModel->where('movie_id', $id)->findAll();
        $averageRating = $reviewModel->getAverageRating($id) ?? 0;

        $data = [
            'movie' => $movie,
            'reviews' => $reviews,
            'averageRating' => $averageRating
        ];

        return view('movie_detail', $data);
    }

    public function saveReview()
    {
        if (!session()->has('user_id')) {
            return redirect()->to('/login');
        }

        $reviewModel = new ReviewModel();

        $data = [
            'movie_id' => $this->request->getPost('movie_id'),
            'user_id' => session()->get('user_id'),
            'username' => session()->get('username'),
            'review' => $this->request->getPost('review'),
            'rating' => $this->request->getPost('rating'),
            'created_at' => date('Y-m-d H:i:s'),
            'updated_at' => date('Y-m-d H:i:s'),
        ];

        $reviewModel->save($data);

        return $this->response->setJSON(['success' => true]);
    }

    public function fetchMovieDetails($title)
    {
        $title = urlencode($title);
        $url = "http://www.omdbapi.com/?s=$title&apikey={$this->apiKey}";

        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, $url);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);

        $response = curl_exec($ch);
        curl_close($ch);

        if ($response === false) {
            return null;
        }

        return json_decode($response, true);
    }

    public function fetchDetailedMovieInfo($title)
    {
        $title = urlencode($title);
        $url = "http://www.omdbapi.com/?t=$title&apikey={$this->apiKey}&plot=full";

        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, $url);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);

        $response = curl_exec($ch);
        curl_close($ch);

        if ($response === false) {
            return null;
        }

        $apiData = json_decode($response, true);

        if ($apiData && $apiData['Response'] === "True") {
            return [
                'genre' => $apiData['Genre'] ?? '',
                'director' => $apiData['Director'] ?? '',
                'actors' => $apiData['Actors'] ?? '',
                'plot' => $apiData['Plot'] ?? '',
                'runtime' => $apiData['Runtime'] ?? '',
                'language' => $apiData['Language'] ?? ''
            ];
        }

        return null;
    }
}
