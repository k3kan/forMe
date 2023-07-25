<?php

namespace App\Controllers;

use App\Api\WeatherMap;
use App\Helper\Message;
use App\Models\Users;
use GuzzleHttp\Client;
use Symfony\Component\HttpFoundation\Request;

class Webhook
{
    public const START_COMMAND = '/start';
    public const DELETE_COMMAND = '/delete';
    public const MAILING_COMMAND = 'Получить рассылку с городом:';

    public function __construct(public Client $client, public Request $request,public WeatherMap $weatherMap, public Mailing $mailing)
    {}

    public function handler()
    {
        $message = $this->request->getContent();
        $json = json_decode($message, true);
        $text = $json['message']['text'];
        $user = [];
        $user['chat_id'] = $json['message']['chat']['id'];
        $user['username'] = $json['message']['from']['username'];
        match ($text) {
            self::START_COMMAND => $this->sendGreetings($user),
            self::DELETE_COMMAND => $this->deleteRecord($user),
            default => $this->checkMessage($text, $user),
        };
    }

    protected function sendGreetings($user)
    {
        $this->mailing->sendMessage($user, Message::GREETING);
    }

    protected function checkMessage($text, $user)
    {
        $text = filter_var($text, FILTER_SANITIZE_STRING);
        if (str_starts_with($text, self::MAILING_COMMAND)) {
            preg_match("/:[а-яА-ЯёЁ]+/u", $text, $matches);
            $town = trim($matches[0], ':');
            $weatherInfo = $this->getWeatherInfo($town, false);
            if (!$weatherInfo) {
                $message = Message::WEATHER_ERROR;
            } else {
                $result = $this->checkRecord($user, $town);
                $message = $result ? Message::SAVE_SUCCESS : Message::SAVE_FAIL;
            }
        } else {
            $message = $this->getWeatherInfo($text);
        }
        $this->mailing->sendMessage($user, $message);
    }

    protected function getWeatherInfo($town, $showMessage = true): string
    {
        $weather = $this->weatherMap->getWeatherTown($town);
        if ($weather['error'] && !$showMessage) {
            return false;
        }
        elseif ($weather['error']) {
            return Message::INCORRECT_TOWN;
        }
        $message = sprintf(Message::WEATHER, $weather['town'], $weather['temperature'], $weather['feelsLike'], $weather['wind'], $weather['humidity']);
        $message .= '%.';
        return $message;
    }

    protected function deleteRecord($user)
    {
        $result = Users::deleteUser($user['username']);
        $message = empty($result) ? Message::MAILING_FAIL : Message::MAILING_SUCCESS;
        $this->mailing->sendMessage($user, $message);
    }

    protected function checkRecord($user, $town)
    {
        $record = Users::getUser($user['username']);
        if (!empty($record)) {
            $userTown = $record['town_weather'];
            if ($userTown === $town) {
                return true;
            }
        }

        return empty($record) ? Users::addUser($user, $town) : Users::updateTown($record[0], $town);
    }
}