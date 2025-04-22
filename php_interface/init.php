<?php
AddEventHandler('main', 'OnEpilog', 'autoInsertWeatherWidget');
function autoInsertWeatherWidget()
{
    global $APPLICATION;

    if ($APPLICATION->GetCurPage(false) !== '/stream/')
        return;

    $APPLICATION->IncludeComponent(
        "custom:weather.widget",
        "",
        [
            "API_KEY" => "e0abed56-c442-46d5-baa0-25afac0a8ce6",
            "LAT" => "55.7558",
            "LON" => "37.6173",
        ],
        false,
        ["HIDE_ICONS" => "Y"]
    );
}