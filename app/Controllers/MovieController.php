<?php

namespace App\Controllers;

use App\Models\MovieModel;
use App\Models\ReviewModel;
use CodeIgniter\Controller;

class MovieController extends Controller
{
    private $apiKey = 'ef89affb';

    public function index()
    {
        $model = new MovieModel();
        $reviewModel = new ReviewModel();

        $search = $this->request->getGet('search');
        $data['movies'] = [];
        $data['search'] = $search;

        if ($search) {
            $localMovies = $model->like('title', $search)->findAll();
            $data['movies'] = $localMovies;

            // Fetch Movies from API if not found locally
            $apiMovies = $this->fetchMovieDetails($search);
            if ($apiMovies && $apiMovies['Response'] === "True") {
                $data['apiMovies'] = $apiMovies['Search'];
            } else {
                $data['apiMovies'] = null;
            }
        } else {
            $data['movies'] = $model->findAll();
        }

        foreach ($data['movies'] as &$movie) {
            $movie['reviews'] = $reviewModel->where('movie_id', $movie['id'])->findAll();
        }

        return view('movie_list', $data);  
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
            'rating' => $this->request->getPost('rating')
        ];

        $reviewModel->save($data);

        return $this->response->setJSON(['success' => true]);
    }

    public function fetchMovieDetails($title)
    {
        $title = urlencode($title);
        $url = "http://www.omdbapi.com/?s=$title&apikey={$this->apiKey}";

        $response = file_get_contents($url);
        return json_decode($response, true);
    }
}
