<?php

namespace App\Controllers;

use App\Api\WeatherMap;
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
        $greetings = "Это информационный бот для получения погоды.\n
Чтобы получить погоду по конкретному городу введите название города. Например Москва.
Если хотите получать ежедневную рассылку в 12:00 по конкретному городу введите собщение формата\n
Получить рассылку с городом:Москва\n
в данному случае будет рассылка с городом Москва.";

        $this->mailing->sendMessage($user, $greetings);
    }

    /** TODO вынести сообщения отдельно */
    protected function checkMessage($text, $user)
    {
        $text = filter_var($text, FILTER_SANITIZE_STRING);
        if (str_starts_with($text, self::MAILING_COMMAND)) {
            preg_match("/:[а-яА-ЯёЁ]+/u", $text, $matches);
            $town = trim($matches[0], ':');
            $weatherInfo = $this->getWeatherInfo($town, false);
            if (!$weatherInfo) {
                $message = "Проверьте корректность сообщения. Текст должнен быть вида
'Получить рассылку с городом:город'";
            } else {
                $result = $this->checkRecord($user, $town);
                $message = $result ? 'Рассылка установлена' : 'Ошибка сохранения настройки';
            }
        } else {
            $message = $this->getWeatherInfo($text);
        }
        $this->mailing->sendMessage($user, $message);
    }

    protected function getWeatherInfo($town, $showMessage = true): string
    {
        $message = '';
        $weather = $this->weatherMap->getWeatherTown($town);
        if ($weather['error'] && !$showMessage) {
            return false;
        }
        elseif ($weather['error']) {
            return "Не удалось определить город. Проверьте корректность сообщения.";
        }
        $message .= 'Погода в ' . $weather['town'] . ' составляет ' .  $weather['temperature'] . '°C. Ощущается как ' .  $weather['feelsLike'] . '°C.';
        $message .= 'Скорость ветра составляет ' . $weather['wind'] . 'м/с, влажность ' .  $weather['humidity'] . '%.';
        return $message;
    }

    protected function deleteRecord($user)
    {
        $result = Users::deleteUser($user['username']);
        $message = empty($result) ? 'У вас не было установленно рассылки': 'Рассылка отменена';
        $this->mailing->sendMessage($user, $message);
    }

    protected function checkRecord($user, $town)
    {
        $record = Users::getUser($user['username']);
        if (empty($record)) {
            $result = Users::addUser($user, $town);
        } else {
            $result = Users::updateTown($record[0], $town);
        }

        return $result;
    }
}