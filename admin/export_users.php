<?php
require_once $_SERVER["DOCUMENT_ROOT"]."/bitrix/modules/main/include/prolog_admin_before.php";

$APPLICATION->SetTitle("Выгрузка пользователей в Excel");

$APPLICATION->IncludeComponent(
    "custom:user.export",
    "",
    []
);

require_once $_SERVER["DOCUMENT_ROOT"]."/bitrix/modules/main/include/prolog_admin_after.php";
// Здесь можно вывести дополнительные элементы, если нужно

require_once $_SERVER["DOCUMENT_ROOT"]."/bitrix/modules/main/include/epilog_admin.php";
?>