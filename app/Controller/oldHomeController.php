<?php

//namespace App\Controller;

class HomeController
{
    public function index(): void
    {
        $title = "Welcome to my shop";
        $currentlyHere = 'home';
        session_start();
        require __DIR__ ."/../../views/home/index.php";
    }
}
