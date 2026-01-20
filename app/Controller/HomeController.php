<?php
namespace App\Controller;

class HomeController{
    public function index():void {
        $title = "Welcome to my shop";
        require __DIR__ ."/../../views/home/index.php";
    }
}