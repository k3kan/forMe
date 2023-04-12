<?php

namespace App\Controllers;

use App\Templates\FrontendTwigRenderer;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

class Homepage
{
    public function __construct(private Request $request, private Response $response,private FrontendTwigRenderer $renderer)
    {}

    public function show()
    {
        $data = [
            'name' =>  $this->request->query->get('name', 'User')
        ];
        $content = $this->renderer->render('Homepage', $data);
        $this->response->setContent($content);
        $this->response->sendContent();
    }
}