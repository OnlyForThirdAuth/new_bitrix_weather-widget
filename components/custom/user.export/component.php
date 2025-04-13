<?php
if (!defined('B_PROLOG_INCLUDED') || B_PROLOG_INCLUDED !== true) die();

use Bitrix\Main\Loader;
use Bitrix\Main\UserTable;
use Bitrix\Main\Type\DateTime;
use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;

class CustomUserExportComponent extends CBitrixComponent
{
    public function executeComponent()
    {
        global $USER;
        if (!$USER->IsAdmin()) {
            ShowError('Доступ запрещен.');
            return;
        }

        Loader::includeModule('main');

        if ($this->request->isPost() && $this->request->get('export')) {
            $this->exportToExcel();
            die();
        }

        $this->includeComponentTemplate();
    }

    private function exportToExcel()
    {
        $filter = $this->getFilter();
        $users = $this->getUsers($filter);

        $spreadsheet = new Spreadsheet();
        $sheet = $spreadsheet->getActiveSheet();

        // Заголовки
        $headers = ['ID', 'Имя', 'Фамилия', 'Email', 'Телефон', 'Дата регистрации'];
        $sheet->fromArray($headers, null, 'A1');

        // Данные
        $row = 2;
        foreach ($users as $user) {
            $sheet->setCellValue('A' . $row, $user['ID']);
            $sheet->setCellValue('B' . $row, $user['NAME']);
            $sheet->setCellValue('C' . $row, $user['LAST_NAME']);
            $sheet->setCellValue('D' . $row, $user['EMAIL']);
            $sheet->setCellValue('E' . $row, $user['PERSONAL_PHONE']);
            $sheet->setCellValue('F' . $row, $user['DATE_REGISTER']->format('d.m.Y H:i'));
            $row++;
        }

        // Отправка файла
        $writer = new Xlsx($spreadsheet);
        header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
        header('Content-Disposition: attachment; filename="users_export.xlsx"');
        $writer->save('php://output');
        exit;
    }

    private function getFilter()
    {
        $filter = [];
        $request = $this->request;

        // Фильтр по дате
        if ($startDate = $request->get('start_date')) {
            $filter['>=DATE_REGISTER'] = new DateTime($startDate, 'Y-m-d');
        }
        if ($endDate = $request->get('end_date')) {
            $filter['<=DATE_REGISTER'] = new DateTime($endDate, 'Y-m-d');
        }

        // Фильтр по телефону
        if ($request->get('has_phone')) {
            $filter['!=PERSONAL_PHONE'] = '';
        }

        return $filter;
    }

    private function getUsers(array $filter)
    {
        return UserTable::query()
            ->setSelect(['ID', 'NAME', 'LAST_NAME', 'EMAIL', 'PERSONAL_PHONE', 'DATE_REGISTER'])
            ->setFilter($filter)
            ->exec()
            ->fetchAll();
    }
}