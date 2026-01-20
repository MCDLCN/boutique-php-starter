<?php
namespace App\Controller;

abstract class Controller
{
    protected function view(string $template, array $data = []): void
    {
        view($template, array_merge($data, [
            'flash' => getFlash()
        ]));
    }

    protected function redirect(string $url): void
    {
        redirect($url);
    }

    protected function json(array $data): void
    {
        header('Content-Type: application/json');
        echo json_encode($data);
        exit;
    }
}