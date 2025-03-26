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
            $movie['reviews'] = $reviewModel->getReviewsByMovieId($movie['id']);
            $movie['average_rating'] = $reviewModel->getAverageRating($movie['id']);
        }

        return view('movie_list', $data);
    }

    public function saveApiMovie()
    {
        $model = new MovieModel();

        $title = $this->request->getPost('title');
        $year = $this->request->getPost('year');
        $poster = $this->request->getPost('poster');

        $existingMovie = $model->where('title', $title)->first();

        if (!$existingMovie) {
            $model->save([
                'title' => $title,
                'description' => "From OMDb API",
                'release_date' => $year,
                'poster' => $poster
            ]);
        }

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

        $apiData = json_decode($response, true);

        if ($apiData && $apiData['Response'] === "True") {
            return $apiData;
        }

        return null;
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

        return redirect()->to('/movies');
    }

    public function editReview($id)
    {
        $reviewModel = new ReviewModel();

        $data = [
            'review' => $this->request->getPost('review'),
            'rating' => $this->request->getPost('rating'),
            'updated_at' => date('Y-m-d H:i:s'),
        ];

        $reviewModel->update($id, $data);

        return redirect()->to('/movies');
    }

    public function deleteReview($id)
    {
        $reviewModel = new ReviewModel();
        $reviewModel->delete($id);

        return redirect()->to('/movies');
    }
}
