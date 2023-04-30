<?php

return [
    'dbname' => $_ENV['MYSQL_DATABASE'],
    'user' => $_ENV['MYSQL_USER'],
    'password' => $_ENV['MYSQL_PASSWORD'],
    'host' => $_ENV['MYSQL_HOST'],
    'driver' => 'pdo_mysql',
];
