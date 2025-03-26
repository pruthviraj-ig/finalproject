<?php

namespace App\Models;

use CodeIgniter\Model;

class MovieModel extends Model
{
    protected $table = 'movies';
    protected $primaryKey = 'id';
    protected $allowedFields = ['title', 'description', 'release_date', 'poster'];

    // Save movie only if it doesn't already exist
    public function saveIfNotExists($data)
    {
        $existingMovie = $this->where('title', $data['title'])->first();

        if (!$existingMovie) {  // Movie doesn't exist, so save it
            $this->insert($data);
        }
    }
}
