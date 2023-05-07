<?php declare(strict_types = 1);

return [
    ['GET', '/', ['App\Controllers\Homepage', 'show']],
    ['GET', '/mailings', ['App\Controllers\Mailing', 'mailing']],
    ['GET', '/{slug}', ['App\Controllers\Page', 'show']],
    ['POST', '/weather', ['App\Controllers\Homepage', 'getWeather']],
];
