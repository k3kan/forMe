<?php

namespace App\Menu;

class ArrayMenuReader implements MenuReader
{
    public function readMenu(): array
    {
        return [
            ['href' => '/', 'text' => 'Домашняя страница'],
            ['href' => '/weather', 'text' => 'Узнать текущую погоду в городе'],
        ];
    }
}