<?php 

namespace App\Controllers;

use App\Models\MovieModel;
use App\Models\ReviewModel;
use CodeIgniter\Controller;

class MovieController extends Controller
{
    private $apiKey = 'ef89affb'; // lol I hope this still works

    public function index()
    {
        $model=new MovieModel();
        $reviewModel = new ReviewModel();

        // get the search query if user searched something
        $search = $this->request->getGet("search");

        $data['movies'] = [];
        $data['search'] = $search;
        $data['apiMovies'] = null;

        if ($search) {
            // here I’m just checking if there’s a search
            $localMovies = $model->like('title', $search)->findAll();  
            $data['movies'] = $localMovies;

            // calling api to get movie info
            $apiMovies = $this->fetchMovieDetails($search);
            if ($apiMovies && $apiMovies['Response'] === "True") {
                $data['apiMovies'] = $apiMovies['Search'];
            }
        } else {
            // if no search then just show all movies from db
            $data['movies'] = $model->findAll();
        }

        // adding reviews to each movie here
        foreach ($data['movies'] as &$movie) {
            $movie['reviews'] = $reviewModel->where('movie_id', $movie['id'])->findAll();
            $movie['average_rating'] = $reviewModel->getAverageRating($movie['id']) ?? 0; // default 0 if no rating
        }

        return view('movie_list', $data);
    }

    public function saveMovieAndRedirect()
    {
        $model = new MovieModel();

        // getting values from ajax/post
        $data = [ 
            "title" => $this->request->getPost("title"),
            'description' => $this->request->getPost('description'),  
            'release_date' => $this->request->getPost('release_date'),
            'poster' => $this->request->getPost('poster')
        ];

        // check if this movie is already in database
        $existingMovie = $model->where('title', $data['title'])->first();

        if (!$existingMovie) {
            // if not found then insert it
            $model->save($data);
            $movieId = $model->insertID();
        } else {
            // if found then use the existing ID
            $movieId = $existingMovie['id'];
        }

        // send back json with redirect url
        return $this->response->setJSON([
            'redirect' => base_url('/movie-detail/' . $movieId)
        ]);
    }

    public function detail($id)
    {
        $model = new MovieModel();
        $reviewModel = new ReviewModel();

        // get movie by id
        $movie = $model->find($id);

        if ($movie) {
            // get extra data from OMDb
            $apiMovieDetails = $this->fetchDetailedMovieInfo($movie['title']);
            if ($apiMovieDetails) {
                // merging api data with local movie info
                $movie = array_merge($movie, $apiMovieDetails); 
            }
        }

        // get all reviews of the movie
        $reviews = $reviewModel->where('movie_id', $id)->findAll();
        $averageRating = $reviewModel->getAverageRating($id) ?? 0;

        $data = [
            'movie' => $movie,
            'reviews' => $reviews,
            'averageRating' => $averageRating
        ];

        return view("movie_detail", $data);
    }

    public function saveReview()
    {
        // only logged in users can review
        if (!session()->has('user_id')) {
            return redirect()->to('/login'); // redirect if not logged in
        }

        $reviewModel = new ReviewModel();

        // getting data from review form
        $data = [
            'movie_id' => $this->request->getPost('movie_id'),
            'user_id' => session()->get('user_id'),
            'username' => session()->get('username'),
            'review' => $this->request->getPost('review'),
            'rating' => $this->request->getPost('rating'),
            'created_at' => date("Y-m-d H:i:s"),
            'updated_at' => date("Y-m-d H:i:s"),
        ];

        // save the review
        $reviewModel->save($data);

        return $this->response->setJSON(["success" => true]);
    }

    public function editReview($id)
    {
        $reviewModel = new ReviewModel();

        // update review here
        $data = [
            "review" => $this->request->getPost("review"),
            "rating" => $this->request->getPost("rating"),
            'updated_at' => date('Y-m-d H:i:s')
        ];

        $reviewModel->updateReview($id, $data);

        return redirect()->back(); // send back to detail page
    }

    public function deleteReview($id)
    {
        $reviewModel = new ReviewModel();

        // delete the review by id
        $reviewModel->deleteReview($id);

        return $this->response->setJSON(["success" => true]);
    }

    public function fetchMovieDetails($title)
    {
        // encoding title for url
        $title = urlencode($title);

        // api call to search movie
        $url = "http://www.omdbapi.com/?s=$title&apikey={$this->apiKey}";

        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, $url);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);

        $response = curl_exec($ch);
        curl_close($ch);

        if ($response === false) {
            return null;
        }

        // return api response
        return json_decode($response, true);
    }

    public function fetchDetailedMovieInfo($title)
    {
        // another api call but to get detailed info
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

        // only return if the api said it's successful
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
