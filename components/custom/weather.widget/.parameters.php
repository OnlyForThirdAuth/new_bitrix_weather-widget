<?php
if (!defined('B_PROLOG_INCLUDED') || B_PROLOG_INCLUDED !== true) die();

$arParams = [
    'PARAMETERS' => [
        'API_KEY' => [
            'PARENT' => 'BASE',
            'NAME'   => 'API‑ключ Яндекс.Погоды',
            'TYPE'   => 'STRING',
            'DEFAULT'=> '',
        ],
        'LAT' => [
            'PARENT' => 'BASE',
            'NAME'   => 'Широта',
            'TYPE'   => 'STRING',
            'DEFAULT'=> '55.7558',
        ],
        'LON' => [
            'PARENT' => 'BASE',
            'NAME'   => 'Долгота',
            'TYPE'   => 'STRING',
            'DEFAULT'=> '37.6173',
        ],
    ],
];