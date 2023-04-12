<?php declare(strict_types = 1);

namespace App\Page;

class FilePageReader implements PageReader
{
    public function __construct(private string $pageFolder)
    {}

    public function readBySlug(string $slug): string
    {
        $path = "$this->pageFolder/{$slug}.md";

        if (!file_exists($path)) {
            throw new InvalidPageException();
        }

        return file_get_contents($path);
    }
}