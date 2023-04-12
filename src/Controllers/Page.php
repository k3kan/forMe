<?php

namespace App\Controllers;

use App\Page\FilePageReader;
use App\Page\InvalidPageException;
use App\Templates\FrontendTwigRenderer;
use Symfony\Component\HttpFoundation\Response;

class Page
{
    public function __construct(private Response $response,private FrontendTwigRenderer $renderer, private FilePageReader $pageReader)
    {}

    public function show($params)
    {
        $slug = $params['slug'];

        try {
            $data['content'] = $this->pageReader->readBySlug($slug);
        } catch (InvalidPageException $e) {
            $this->response->setStatusCode(404);
            $this->response->setContent('404 - Page not found');
            $this->response->send();
           return;
        }
        $html = $this->renderer->render('Page', $data);
        $this->response->setContent($html);
        $this->response->sendContent();
    }
}