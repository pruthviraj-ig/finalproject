<?php

namespace App\Controllers;

use App\Models\MovieModel;
use App\Models\ReviewModel;
use CodeIgniter\Controller;

class MovieController extends Controller
{
    private $apiKey = 'ef89affb';  // Your API Key, make sure it's correct

    public function index()
    {
        $model = new MovieModel();
        $reviewModel = new ReviewModel();

        $search = $this->request->getGet('search');
        $data['movies'] = [];
        $data['search'] = $search;
        $data['apiMovies'] = null; // Initialize apiMovies as null

        if ($search) {
            $localMovies = $model->like('title', $search)->findAll();
            $data['movies'] = $localMovies;

            // Fetch Movies from API if search is provided
            $apiMovies = $this->fetchMovieDetails($search);
            if ($apiMovies && $apiMovies['Response'] === "True") {
                $data['apiMovies'] = $apiMovies['Search'];  // Pass API movies to the view
            }
        } else {
            $data['movies'] = $model->findAll();
        }

        foreach ($data['movies'] as &$movie) {
            $movie['reviews'] = $reviewModel->where('movie_id', $movie['id'])->findAll();
            $movie['average_rating'] = $reviewModel->getAverageRating($movie['id']);
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
            'rating' => $this->request->getPost('rating'),
            'created_at' => date('Y-m-d H:i:s'),
            'updated_at' => date('Y-m-d H:i:s'),
        ];

        $reviewModel->save($data);

        return $this->response->setJSON(['success' => true]);
    }

    public function editReview($id)
    {
        $reviewModel = new ReviewModel();
        $data = [
            'review' => $this->request->getPost('review'),
            'rating' => $this->request->getPost('rating'),
            'updated_at' => date('Y-m-d H:i:s'),
        ];
        $reviewModel->updateReview($id, $data);
        return redirect()->to('/movies');
    }

    public function deleteReview($id)
    {
        $reviewModel = new ReviewModel();
        $reviewModel->deleteReview($id);
        return redirect()->to('/movies');
    }

    public function fetchMovieDetails($title)
    {
        $title = urlencode($title);
        $url = "http://www.omdbapi.com/?s=$title&apikey={$this->apiKey}";

        $ch = curl_init();  // Initialize cURL
        curl_setopt($ch, CURLOPT_URL, $url);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);

        $response = curl_exec($ch);
        curl_close($ch);

        if ($response === false) {
            return null;  // Return null if the API call fails
        }

        $apiData = json_decode($response, true);

        if ($apiData && $apiData['Response'] === "True") {
            return $apiData; // Return the movie data if found
        }

        return null; // Return null if no movie found
    }
}
