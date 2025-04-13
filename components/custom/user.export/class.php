<?php
use Bitrix\Main\Engine\Contract\Controllerable;
use Bitrix\Main\UI\Extension;
use Bitrix\Main\Type\DateTime;
use Bitrix\Main\UserTable;

class ExportUsersComponent extends CBitrixComponent implements Controllerable
{
    public function configureActions() {
        return [];
    }

    public function executeComponent()
    {
        // Загрузка расширения Bitrix
        Extension::load("ui.forms");

        if (isset($_GET['export']) && $_GET['export'] === 'Y') {
            $filter = [
                'DATE_FROM' => $_GET['date_from'] ?? '',
                'DATE_TO'   => $_GET['date_to'] ?? '',
                'ONLY_WITH_PHONE' => isset($_GET['with_phone']),
            ];

            $users = $this->getUsers($filter);

            require_once __DIR__ . '/lib/excel.php';
            exportToExcel($users);
            exit;
        }
        $this->includeComponentTemplate();
    }

    public function getUsers($filter = [])
    {
        $query = [
            'select' => ['ID', 'NAME', 'LAST_NAME', 'EMAIL', 'PERSONAL_PHONE', 'DATE_REGISTER'],
            'filter' => [],
            'order'  => ['ID' => 'ASC'],
        ];

        if (!empty($filter['DATE_FROM'])) {
            // Приводим дату к формату "Y-m-d 00:00:00"
            $query['filter']['>=DATE_REGISTER'] = new DateTime($filter['DATE_FROM'] . ' 00:00:00', 'Y-m-d H:i:s');
        }
        if (!empty($filter['DATE_TO'])) {
            
            $query['filter']['<=DATE_REGISTER'] = new DateTime($filter['DATE_TO'] . ' 23:59:59', 'Y-m-d H:i:s');
        }
        if (!empty($filter['ONLY_WITH_PHONE'])) {
            // Фильтр пользователей с заполненным телефоном
            $query['filter']['!PERSONAL_PHONE'] = false;
        }

        $result = UserTable::getList($query);
        return $result->fetchAll();
    }
}