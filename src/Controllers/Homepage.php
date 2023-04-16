<?php

namespace App\Controllers;

use App\Api\WeatherMap;
use App\Render\FrontendTwigRenderer;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

class Homepage
{
    public function __construct(private Request $request, private Response $response,private FrontendTwigRenderer $renderer, private WeatherMap $weatherApi)
    {}

    public function show()
    {
    /*    $this->weatherApi->setLongitude(49.66);
        $this->weatherApi->setLatitude(58.59);
        $weather = $this->weatherApi->getWeather();*/
        $data = [
            'name' =>  $this->request->query->get('name', 'User'),
        ];
        $content = $this->renderer->render('Homepage', $data);
        $this->response->setContent($content);
        $this->response->sendContent();
    }

    public function getWeather()
    {
        /** TODO получаем данные с формы и подаем запрос к апи погоды */
        $data = $this->request->request->all();
        foreach ($data as $id => $value) {
            $data[$id] = filter_var($value, FILTER_VALIDATE_FLOAT);
            if (!is_float($data[$id])) {
                $content = $this->renderer->render('Homepage',[
                    'validateError' => "В форме указаны некорректные данные. Введите их еще раз"
                ]);
                return  $this->sendContent($content);
            }
        }

        $this->weatherApi->setLongitude($data['lot']);
        $this->weatherApi->setLatitude($data['lat']);
        $weather = $this->weatherApi->getWeather();
        $content = [
            'name' =>  $this->request->query->get('name', 'User'),
            'weather' => $weather,
        ];
        $content = $this->renderer->render('Homepage',$content);
        return $this->sendContent($content);
    }

    private function sendContent($content)
    {
        $this->response->setContent($content);
        return $this->response->sendContent();
    }
}