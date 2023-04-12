<?php

namespace App\Menu;

class ArrayMenuReader implements MenuReader
{
    public function readMenu(): array
    {
        return [
            ['href' => '/', 'text' => 'HomePage'],
            ['href' => '/test', 'text' => 'Test Link'],
        ];
    }
}