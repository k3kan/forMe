<?php

namespace App\Controllers;

use App\Api\WeatherMap;
use App\Models\Locations;
use App\Models\Users;
use App\Pdo\Database;
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

    private function addNewUsers(): void
    {
        $url = $this->getUrl("getUpdates");
        $response = $this->client->request('GET', $url);
        $json = $response->getBody();
        $data = json_decode($json, true);
        $messages = $data['result'] ?? [];
        foreach ($messages as $message) {
            $chatId = $message['message']['chat']['id'] ?? '';
            if (empty($chatId)) {
                continue;
            }
            $username = $message['message']['chat']['username'];
            $sql = <<<SQL
INSERT IGNORE users (username, chat_id) VALUES (:username, :chatId)
SQL;
            Database::getInstance()->execute($sql, ['username' => $username, 'chatId' => $chatId]);
        }
    }

    private function getUsers(): array
    {
        return Users::getUsers();
    }

    private function getLocations(): array
    {
        return Locations::getLocations();
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
        $this->addNewUsers();
        $users = $this->getUsers();
        $locations = $this->getLocations();
        foreach ($locations as $location) {
            $this->weatherMap->setLatitude($location['latitude']);
            $this->weatherMap->setLongitude($location['longitude']);
            $weather= $this->weatherMap->getWeatherCoordinate();
            $message .= 'Погода в ' . $weather['town'] . ' составляет ' .  $weather['temperature'] . '°C. Ощущается как ' .  $weather['feelsLike'] . '°C.';
            $message .= 'Скорость ветра составляет ' . $weather['wind'] . 'м/с, влажность ' .  $weather['humidity'] . '%.';
        }
        foreach ($users as $user) {
            $this->sendMessage($user, $message);
        }
    }
}