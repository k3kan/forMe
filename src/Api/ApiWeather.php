<?php

namespace App\Api;

interface ApiWeather
{
    public function setLongitude($lon);
    public function setLatitude($lat);
    public function getWeatherTown($town);
    public function getWeatherCoordinate();
}