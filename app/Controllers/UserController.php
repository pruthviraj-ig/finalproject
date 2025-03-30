<?php

namespace App\Controllers;

use App\Models\UserModel;
use CodeIgniter\Controller;

class UserController extends Controller
{
    // this function just shows the register form
    public function register()
    {
        return view('register'); // user can create account here
    }

    // this is used to store user data in db after registration
    public function store()
    {
        $model = new UserModel();

        // getting the data from the form (register form)
        $data = [
            'username' => $this->request->getPost("username"),
            'email' => $this->request->getPost('email'),
            // hashing the password here so it's safe
            'password' => password_hash($this->request->getPost('password'), PASSWORD_DEFAULT)
        ];

        // saving data to users table
        $model->save($data);

        // after register send user to login page
        return redirect()->to("/login");
    }

    // show the login form to the user
    public function login()
    {
        return view("login"); // simple form with username and password
    }

    // here I’m checking login details
    public function authenticate()
    {
        $model = new UserModel();

        // getting values from form
        $username = $this->request->getPost("username");
        $password = $this->request->getPost('password');

        // check if user exists in db
        $user = $model->where("username", $username)->first();

        // now check if password matches the one in db
        if ($user && password_verify($password, $user['password'])) {

            // if everything is okay then we set session data
            session()->set('user_id', $user['id']);
            session()->set("username", $user["username"]);

            // redirecting to welcome page after successful login
            return redirect()->to('/welcomepage');
        }

        // if username or password is wrong then come back to login
        return redirect()->to('/login');
    }

    // this will log the user out
    public function logout()
    {
        session()->destroy(); // clearing session
        return redirect()->to('/login'); // back to login page
    }
}
