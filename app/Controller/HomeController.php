<?php

namespace App\Controller;

class HomeController extends Controller
{
    public function index(): void
    {
        getCart();
        $this->view('home/index', [
            'title' => 'Home'
        ]);
    }
}
