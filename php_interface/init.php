<?
AddEventHandler('main', 'OnEpilog', 'addYandexWeatherWidget');

function addYandexWeatherWidget()
{
    global $APPLICATION;
    
    if ($APPLICATION->GetCurPage() !== '/stream/') {
        return;
    }
    // апи ключ, широта и долгота Москвы
    $access_key = 'e0abed56-c442-46d5-baa0-25afac0a8ce6';
    $lat = 55.7558;
    $lon = 37.6173; 

    $weatherData = getYandexWeather($access_key, $lat, $lon);
    
    $widgetHtml = generateWeatherHtml($weatherData);
    
    echo "
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const pulseBlock = document.getElementById('pulse_open_btn');
            if (pulseBlock) {
                pulseBlock.insertAdjacentHTML('afterend', `{$widgetHtml}`);
            }
        });
    </script>
    ";
}

function getYandexWeather($apiKey, $lat, $lon)
{
    $url = "https://api.weather.yandex.ru/v2/forecast?lat={$lat}&lon={$lon}";
    
    $opts = [
        'http' => [
            'method' => 'GET',
            'header' => "X-Yandex-Weather-Key: {$apiKey}\r\n"
        ]
    ];
    
    $context = stream_context_create($opts);
    
    try {
        $response = file_get_contents($url, false, $context);
        return json_decode($response, true);
    } catch (Exception $e) {
        return ['error' => $e->getMessage()];
    }
}

function generateWeatherHtml($data)
{
    if (!empty($data['error'])) {
        return '<div class="weather-error">Ошибка: '.$data['error'].'</div>';
    }
    
    $temp = $data['fact']['temp'] ?? 'N/A';
    $condition = $data['fact']['condition'] ?? '';
    $wind = $data['fact']['wind_speed'] ?? '0';
    $humidity = $data['fact']['humidity'] ?? '0';

    $conditionText = getConditionText($condition);
    
    return '
    <div class="sidebar-widget sidebar-widget-tasks">
    <div class="sidebar-widget-top">
        <div class="sidebar-widget-top-title">
            <h3 style="font-size: 12px;">Погода в Москве</h3>
        </div>
    </div>
        <div class="sidebar-widget-content" style="padding-left:25px !important; color: #828B95">'.$temp.'°C
        '.$conditionText.'
        
            <br><span>Ветер: '.$wind.' м/с</span>
            <br><span>Влажность: '.$humidity.'%</span> <br>
    </div>
    </div>';
}

function getConditionText($conditionCode)
{
    $conditions = [
        'clear' => 'Ясно',
        'partly-cloudy' => 'Переменная облачность',
        'cloudy' => 'Пасмурно',
        'rain' => 'Дождь',
    ];
    
    return $conditions[$conditionCode] ?? '';
}