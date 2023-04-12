<?php declare(strict_types = 1);

namespace App\Templates;

use Twig\Environment;

class TwigRenderer implements Renderer
{
    public function __construct(private Environment $environment)
    {
    }

    public function render($template, $data = []): string
    {
        return $this->environment->render("{$template}.html", $data);
    }
}