<?php

namespace App\Controllers;

use App\Models\MovieModel;
use CodeIgniter\Controller;

class MovieController extends Controller
{
    public function index()
    {
        $model = new MovieModel();
        $data['movies'] = $model->findAll();

        return view('movie_list', $data);
    }

    public function addMovie()
    {
        return view('add_movie');
    }

    public function saveMovie()
    {
        $model = new MovieModel();

        $data = [
            'title' => $this->request->getPost('title'),
            'description' => $this->request->getPost('description'),
            'release_date' => $this->request->getPost('release_date'),
            'poster' => $this->request->getPost('poster'),
        ];

        $model->save($data);
        return redirect()->to('/movies');
    }
}
