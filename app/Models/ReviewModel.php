<?php

namespace App\Models;

use CodeIgniter\Model;

class ReviewModel extends Model
{
    protected $table = 'reviews';
    protected $primaryKey = 'id';
    protected $allowedFields = ['movie_id', 'user_id', 'username', 'review', 'rating', 'created_at', 'updated_at'];

    // Calculate the average rating for a movie
    public function getAverageRating($movie_id)
    {
        return $this->selectAvg('rating')
                    ->where('movie_id', $movie_id)
                    ->get()
                    ->getRow()
                    ->rating;
    }

    // Update a review by ID
    public function updateReview($id, $data)
    {
        return $this->update($id, $data);
    }

    // Delete a review by ID
    public function deleteReview($id)
    {
        return $this->delete($id);
    }
}
