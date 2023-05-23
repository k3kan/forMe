<?php

namespace App\Api;

use GuzzleHttp\Client;
use GuzzleHttp\Exception\ClientException;
use GuzzleHttp\Psr7;

class WeatherMap implements ApiWeather
{
    public float $lon;

    public float $lat;

    public array $queryParams = [
        'units' => 'metric',
        'lang' => 'ru',
    ];

    public function __construct(
        public string $token,
        public Client $client,
        public string $url = 'https://api.openweathermap.org/data/2.5/weather')
    {}

    public function setLongitude($lon)
    {
        $this->lon = $lon;
    }

    public function setLatitude($lat)
    {
        $this->lat = $lat;
    }

    public function getWeatherTown($town)
    {
        $this->queryParams['appid'] = $this->token;
        $this->queryParams['q'] = $town;

        return $this->getWeatherData($this->queryParams);
    }

    public function getWeatherCoordinate()
    {
        $this->queryParams['appid'] = $this->token;
        $this->queryParams['lat'] = $this->lat;
        $this->queryParams['lon'] = $this->lon;

        return $this->getWeatherData($this->queryParams);
    }

    public function getWeatherData($queryParams)
    {
        try {
            $response = $this->client->request('GET', $this->url, ['query' => $queryParams]);
            $json = $response->getBody();
            $data = json_decode($json, true);
            $weather['temperature'] =  $data['main']['temp'];
            $weather['feelsLike'] =  $data['main']['feels_like'];
            $weather['wind'] =  $data['wind']['speed'];
            $weather['humidity'] =  $data['main']['humidity'];
            $weather['town'] =  $data['name'];
            $weather['error'] = false;
        } catch (ClientException $e) {
            $weather['error'] =  true;
            error_log ("Ответ не получен. Смотреть подробности " . Psr7\Message::toString($e->getRequest()) . Psr7\Message::toString($e->getResponse()));
        }

        return $weather;
    }
}