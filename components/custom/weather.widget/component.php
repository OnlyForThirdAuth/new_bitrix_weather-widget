<?php
if (!defined('B_PROLOG_INCLUDED') || B_PROLOG_INCLUDED !== true) die();

$apiKey = trim($arParams['API_KEY'] ?? '');
$lat = trim($arParams['LAT'] ?? '');
$lon = trim($arParams['LON'] ?? '');

$arResult = [
    'ERROR' => '',
    'TEMP' => '',
    'CONDITION' => '',
    'WIND' => '',
    'HUMIDITY' => '',
];

if ($apiKey && $lat && $lon) {
    $url = "https://api.weather.yandex.ru/v2/forecast?lat={$lat}&lon={$lon}";
    $opts = [
        'http' => [
            'method' => 'GET',
            'header' => "X-Yandex-Weather-Key: {$apiKey}\r\n"
        ]
    ];
    $context = stream_context_create($opts);
    $response = @file_get_contents($url, false, $context);

    if ($response !== false) {
        $data = json_decode($response, true);
        if (!empty($data['fact'])) {
            $map = [
                'clear' => 'Ясно',
                'partly-cloudy' => 'Переменная облачность',
                'cloudy' => 'Пасмурно',
                'overcast' => 'Сплошная облачность',
                'light-rain' => 'Небольшой дождь',
                'rain' => 'Дождь',
                'light-snow' => 'Небольшой снег',
                'snow' => 'Снег',
                'thunderstorm' => 'Гроза',
            ];
            $fact = $data['fact'];
            $arResult = [
                'TEMP' => $fact['temp'] ?? 'N/A',
                'CONDITION' => $map[$fact['condition']] ?? 'Неизвестно',
                'WIND' => $fact['wind_speed'] ?? '0',
                'HUMIDITY' => $fact['humidity'] ?? '0',
            ];
        } else {
            $arResult['ERROR'] = 'Данные о погоде не найдены';
        }
    } else {
        $arResult['ERROR'] = 'Не удалось получить данные от API';
    }
} else {
    $arResult['ERROR'] = 'Не передан API ключ или координаты';
}

$this->includeComponentTemplate();
