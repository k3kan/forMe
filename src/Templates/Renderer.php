<?php declare(strict_types = 1);

namespace App\Templates;

interface Renderer
{
    public function render($template, $data = []): string;
}