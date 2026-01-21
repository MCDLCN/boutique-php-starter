<?php

namespace App\Controller;

class HomeController extends Controller
{
    public function index(): void
    {
        $cart= getCart();
        $this->view('home/index', [
            'title' => 'Home'
        ]);
    }
}
