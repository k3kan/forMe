<?php

namespace App\Api;

use GuzzleHttp\Client;
use GuzzleHttp\Exception\ClientException;
use GuzzleHttp\Psr7;

class WeatherMap implements ApiWeather
{
    public float $lon;

    public float $lat;

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

    public function getWeather()
    {
        $queryParams = [
            'lat' => $this->lat,
            'lon' => $this->lon,
            'appid' => $this->token,
            'lang' => ' ru'
        ];
        $weather = [];

        try {
            $response = $this->client->request('GET', $this->url, ['query' => $queryParams]);
            $json = $response->getBody();
            $data = json_decode($json, true);
            $weather['temperature'] =  round($data['main']['temp'] - 273.15);
            $weather['feelsLike'] =  round($data['main']['feels_like'] - 273.15);
            $weather['wind'] =  $data['wind']['speed'];
            $weather['humidity'] =  $data['main']['humidity'];
            $weather['town'] =  $data['name'];
        } catch (ClientException $e) {
            /** TODO писать в логи */
            echo Psr7\Message::toString($e->getRequest());
            echo Psr7\Message::toString($e->getResponse());
            return [];
        }

        return $weather;
    }
}