<?php

require_once $_SERVER['DOCUMENT_ROOT'] . '/vendor/autoload.php';

use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;
use Bitrix\Main\Type\DateTime;

function exportToExcel($users) {
    ob_end_clean();

    if (empty($users)) {
        echo '<div style="padding:20px; text-align:center; font-size:16px;">Нет данных для экспорта. Выбран диапазон дат, для которых нет записей. Пожалуйста, выберите корректный диапазон.</div>';
        echo '<div style="text-align:center; margin-top:20px;">';
        echo '<a href="' . htmlspecialchars($_SERVER['HTTP_REFERER'] ?? '/') . '" class="adm-btn">Вернуться назад</a>';
        echo '</div>';
        exit;
    }
    
    $spreadsheet = new Spreadsheet();
    $sheet = $spreadsheet->getActiveSheet();

    $sheet->fromArray(
        ['ID', 'Имя', 'Фамилия', 'Email', 'Телефон', 'Дата регистрации'],
        null,
        'A1'
    );

    // Заполняем данные пользователей
    $row = 2;
    foreach ($users as $user) {
        $sheet->setCellValue("A{$row}", $user['ID']);
        $sheet->setCellValue("B{$row}", $user['NAME']);
        $sheet->setCellValue("C{$row}", $user['LAST_NAME']);
        $sheet->setCellValue("D{$row}", $user['EMAIL']);
        $sheet->setCellValue("E{$row}", $user['PERSONAL_PHONE']);

        // Обработка даты регистрации: если это объект DateTime, форматируем его в строку
        $dateValue = ($user['DATE_REGISTER'] instanceof DateTime)
            ? $user['DATE_REGISTER']->format('Y-m-d H:i:s')
            : $user['DATE_REGISTER'];
        $sheet->setCellValue("F{$row}", $dateValue);

        $row++;
    }

    header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
    header('Content-Disposition: attachment;filename="users.xlsx"');
    header('Cache-Control: max-age=0');
    header('Pragma: public');

    $writer = new Xlsx($spreadsheet);
    $writer->save('php://output');
    exit;
}