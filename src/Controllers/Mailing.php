<?php

namespace App\Controllers;

use App\Api\WeatherMap;
use App\Models\Users;
use GuzzleHttp\Client;
use GuzzleHttp\Exception\ClientException;
use GuzzleHttp\Psr7\Message;
use Symfony\Component\HttpFoundation\Request;

class Mailing
{
    const TELEGRAM_URL = 'https://api.telegram.org/bot';

    public function __construct(private Client $client, private Request $request,private WeatherMap $weatherMap, private string $token)
    {}

    protected function getUrl($path)
    {
        return self::TELEGRAM_URL . $this->token . DIRECTORY_SEPARATOR . $path;
    }

    public function sendMessage($user, $message): void
    {
        $url = $this->getUrl('sendMessage');
        try {
            $this->client->request('POST', $url,
                ['form_params' => ['chat_id' => $user['chat_id'], 'text' => $message]]);
        } catch (ClientException $e) {
            $error = $e->getResponse();
            if ($error->getStatusCode() === 403) {
                Users::deleteUser($user['username']);
            } else {
                error_log(Message::toString($e->getResponse()));
            }
        }
    }

    public function mailing(): void
    {
        $serverToken = $_ENV['SERVER_TOKEN'];
        $token = $this->request->query->get('token');
        if ($token !== $serverToken) {
            echo 'Access denied';
            return;
        }
        $message = '';
        $users = Users::getUsers();
        foreach ($users as $user) {
            $weather= $this->weatherMap->getWeatherTown($user['town_weather']);
            $message .= 'Погода в ' . $weather['town'] . ' составляет ' .  $weather['temperature'] . '°C. Ощущается как ' .  $weather['feelsLike'] . '°C.';
            $message .= 'Скорость ветра составляет ' . $weather['wind'] . 'м/с, влажность ' .  $weather['humidity'] . '%.';
            $this->sendMessage($user, $message);
        }
    }
}