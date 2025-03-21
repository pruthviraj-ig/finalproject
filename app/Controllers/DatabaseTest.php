<?php

namespace App\Controllers;
use CodeIgniter\Controller;
use CodeIgniter\Database\Exceptions\DatabaseException;

class DatabaseTest extends Controller
{
    public function index()
    {
        $db = \Config\Database::connect();
        
        try {
            if ($db->connect()) {
                echo "Database Connection Successful!";
            }
        } catch (DatabaseException $e) {
            echo "Database Connection Failed: " . $e->getMessage();
        }
    }
}
