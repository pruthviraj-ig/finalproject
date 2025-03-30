<?php

namespace App\Controllers;

class WelcomeController extends BaseController
{
    // this function is used to show the welcome page after login
    public function index()
    {
        // loading the welcome view here
        return view('welcome');
    }
}
