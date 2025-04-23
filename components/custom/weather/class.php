<?php
if (!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED !== true) die();

class WeatherComponent extends CBitrixComponent
{
    public function executeComponent()
{
    // отключи кеш на время отладки
    if ($this->StartResultCache(false, [], ['CACHE_TYPE' => 'N'])) {
        try {
            $opts = [/*…*/];
            $response = @file_get_contents($url, false, stream_context_create($opts));
            if ($response === false) {
                throw new \Exception("Промах при запросе к Яндекс.Погоде");
            }
            $data = json_decode($response, true);
            if (json_last_error() !== JSON_ERROR_NONE) {
                throw new \Exception("JSON decode error: ".json_last_error_msg());
            }
            $this->arResult = [/*…*/];
        } catch (\Throwable $e) {
            // выведем в лог
            AddMessage2Log("WeatherComponent error: ".$e->getMessage(), 'custom.weather');
            $this->AbortResultCache();
        }
        $this->IncludeComponentTemplate();
    }
}

}