<?php

require_once $_SERVER['DOCUMENT_ROOT'] . '/vendor/autoload.php';

use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;
use Bitrix\Main\Type\DateTime;

function exportToExcel($users) {
    // Начинаем буферизацию вывода, чтобы гарантированно не было лишнего вывода
    ob_start();
    try {
        if (empty($users)) {
            throw new Exception("Нет данных для экспорта.");
        }

        $spreadsheet = new Spreadsheet();
        $sheet = $spreadsheet->getActiveSheet();

        // Заголовки колонок
        $sheet->setCellValue('A1', 'ID');
        $sheet->setCellValue('B1', 'Имя');
        $sheet->setCellValue('C1', 'Фамилия');
        $sheet->setCellValue('D1', 'Email');
        $sheet->setCellValue('E1', 'Телефон');
        $sheet->setCellValue('F1', 'Дата регистрации');

        $row = 2;
        foreach ($users as $user) {
            // Записываем данные в соответствующие ячейки
            $sheet->setCellValue('A' . $row, $user['ID'] ?? '');
            $sheet->setCellValue('B' . $row, $user['NAME'] ?? '');
            $sheet->setCellValue('C' . $row, $user['LAST_NAME'] ?? '');
            $sheet->setCellValue('D' . $row, $user['EMAIL'] ?? '');
            $sheet->setCellValue('E' . $row, $user['PERSONAL_PHONE'] ?? '');

            // Обработка даты регистрации
            if (isset($user['DATE_REGISTER'])) {
                if ($user['DATE_REGISTER'] instanceof DateTime) {
                    $dateValue = $user['DATE_REGISTER']->format('Y-m-d H:i:s');
                } elseif (is_string($user['DATE_REGISTER'])) {
                    $dateValue = $user['DATE_REGISTER'];
                } else {
                    $dateValue = 'N/A';
                }
            } else {
                $dateValue = 'N/A';
            }
            $sheet->setCellValue('F' . $row, $dateValue);

            $row++;
        }

        // Сбрасываем буфер вывода, чтобы не было лишних символов
        ob_clean();

        // Устанавливаем заголовки, чтобы файл скачивался
        header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
        header('Content-Disposition: attachment;filename="users.xlsx"');
        header('Cache-Control: max-age=0');
        header('Pragma: public');

        // $writer = new Xlsx($spreadsheet);
        // $writer->save('php://output');
        exit;
    } catch (Exception $e) {
        ob_clean();
        die("Ошибка: " . $e->getMessage());
    }
}