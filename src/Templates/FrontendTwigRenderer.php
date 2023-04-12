<?php

namespace App\Templates;

use App\Menu\ArrayMenuReader;
use Twig\Environment;

class FrontendTwigRenderer implements FrontendRenderer
{
    public function __construct(private Environment $renderer, private ArrayMenuReader $menuReader) {}

    public function render($template, $data = []): string
    {
        $data = array_merge($data, [
           'menuItems'=> $this->menuReader->readMenu(),
        ]);
        return $this->renderer->render("{$template}.html", $data);
    }
}