<?php

namespace App\Controllers;

use App\Models\MovieModel;
use CodeIgniter\Controller;

class MovieController extends Controller
{
    private $apiKey = 'ef89affb';  // Your OMDb API Key

    public function index()
    {
        $model = new MovieModel();
        $search = $this->request->getGet('search');
        $data['movies'] = [];
        $data['search'] = $search;

        if ($search) {
            // Search Movies in Database
            $localMovies = $model->like('title', $search)->findAll();
            $data['movies'] = $localMovies;

            // Search Movies in OMDb API if not found locally
            if (empty($localMovies)) {
                $apiMovies = $this->fetchMovieDetails($search);
                if ($apiMovies && $apiMovies['Response'] === "True") {
                    $data['apiMovies'] = $apiMovies;
                } else {
                    $data['apiMovies'] = null;
                }
            }
        } else {
            $data['movies'] = $model->findAll();  // Fetch all movies from local database
        }

        return view('movie_list', $data);  
    }

    public function fetchMovieDetails($title)
    {
        $title = urlencode($title);
        $url = "http://www.omdbapi.com/?s=$title&apikey={$this->apiKey}";

        $response = file_get_contents($url);
        return json_decode($response, true);
    }

    public function saveMovie()
    {
        if (!session()->has('user_id')) {
            return redirect()->to('/login');
        }

        $model = new MovieModel();

        $data = [
            'title' => $this->request->getPost('title'),
            'description' => $this->request->getPost('description'),
            'release_date' => $this->request->getPost('release_date'),
            'poster' => $this->request->getPost('poster')
        ];

        $model->save($data);
        return redirect()->to('/movies');
    }
}
