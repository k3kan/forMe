<?php declare(strict_types = 1);

return [
    ['GET', '/', ['App\Controllers\Homepage', 'show']],
    ['GET', '/{slug}', ['App\Controllers\Page', 'show']],
];
