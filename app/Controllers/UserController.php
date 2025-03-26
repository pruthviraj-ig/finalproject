<?php

namespace App\Controllers;

use App\Models\UserModel;
use CodeIgniter\Controller;

class UserController extends Controller
{
    public function register()
    {
        return view('register');
    }

    public function store()
    {
        $model = new UserModel();  // Removed the escaped backslashes
        
        $data = [
            'username' => $this->request->getPost('username'),
            'email' => $this->request->getPost('email'),
            'password' => password_hash($this->request->getPost('password'), PASSWORD_DEFAULT)
        ];
        
        $model->save($data);  // Removed the escaped backslashes
        
        return redirect()->to('/login');
    }

    public function login()
    {
        return view('login');
    }

    public function authenticate()
    {
        $model = new UserModel();
        $username = $this->request->getPost('username');
        $password = $this->request->getPost('password');

        $user = $model->where('username', $username)->first();

        if ($user && password_verify($password, $user['password'])) {
            session()->set('user_id', $user['id']);
            session()->set('username', $user['username']);  // Store username in the session
            return redirect()->to('/movies');
        }

        return redirect()->to('/login');
    }

    public function logout()
    {
        session()->destroy();
        return redirect()->to('/login');  // Redirecting to the login page after logout
    }
}