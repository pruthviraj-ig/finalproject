<?php

namespace App\Models;

use CodeIgniter\Model;

class MovieModel extends Model
{
    protected $table = 'movies';
    protected $primaryKey = 'id';
    protected $allowedFields = ['title', 'description', 'release_date', 'poster'];

    // Check if a movie already exists in the database
    public function getMovieByTitle($title)
    {
        return $this->where('title', $title)->first();
    }
}
