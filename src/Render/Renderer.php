<?php declare(strict_types = 1);

namespace App\Render;

interface Renderer
{
    public function render($template, $data = []): string;
}