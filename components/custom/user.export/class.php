<?php
use Bitrix\Main\Engine\Contract\Controllerable;
use Bitrix\Main\UI\Extension;
use Bitrix\Main\UserTable;
use Bitrix\Main\Type\DateTime;

class ExportUsersComponent extends CBitrixComponent implements Controllerable
{
    public function configureActions() {
        return []; 
    }

    public function executeComponent()
    {
        Extension::load("ui.forms");
        $this->includeComponentTemplate();
    }

    public function getUsers($filter = [])
{
    $query = [
        'select' => ['ID', 'NAME', 'LAST_NAME', 'EMAIL', 'PERSONAL_PHONE', 'DATE_REGISTER'],
        'filter' => [],
        'order' => ['ID' => 'ASC'],
    ];

    if (!empty($filter['DATE_FROM'])) {
        $query['filter']['>=DATE_REGISTER'] = new DateTime($filter['DATE_FROM'] . ' 00:00:00', 'Y-m-d H:i:s');
    }

    if (!empty($filter['DATE_TO'])) {
        $query['filter']['<=DATE_REGISTER'] = new DateTime($filter['DATE_TO'] . ' 23:59:59', 'Y-m-d H:i:s');
    }

    if (!empty($filter['ONLY_WITH_PHONE'])) {
        $query['filter']['!PERSONAL_PHONE'] = false;
    }

    $result = UserTable::getList($query);
    return $result->fetchAll();
}
}