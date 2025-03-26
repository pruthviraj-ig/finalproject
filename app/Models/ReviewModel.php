<?php

namespace App\Models;

use CodeIgniter\Model;

class ReviewModel extends Model
{
    protected $table = 'reviews';
    protected $primaryKey = 'id';
    protected $allowedFields = ['movie_id', 'user_id', 'username', 'review', 'rating', 'created_at', 'updated_at'];

    public function getAverageRating($movie_id)
    {
        return $this->selectAvg('rating')
                    ->where('movie_id', $movie_id)
                    ->get()
                    ->getRow()
                    ->rating;
    }

    public function getReviewsByMovieId($movie_id)
    {
        return $this->where('movie_id', $movie_id)->findAll();
    }
}
