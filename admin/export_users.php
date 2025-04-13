<?php
require_once $_SERVER['DOCUMENT_ROOT'] . '/bitrix/modules/main/include/prolog_admin_before.php';
require_once $_SERVER['DOCUMENT_ROOT'] . '/vendor/autoload.php';

use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;
use Bitrix\Main\Loader;
use Bitrix\Main\Type\DateTime;
use Bitrix\Main\UserTable;

// Включаем отображение ошибок
error_reporting(E_ALL);
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);

Loader::includeModule('main');

// Получаем параметры фильтра
$dateFrom = $_GET['date_from'] ?? null;
$dateTo = $_GET['date_to'] ?? null;
$withPhone = isset($_GET['with_phone']);

// Формируем фильтр
$filter = [];
if (!empty($dateFrom)) {
    $filter['>=DATE_REGISTER'] = new DateTime($dateFrom . ' 00:00:00');
}
if (!empty($dateTo)) {
    $filter['<=DATE_REGISTER'] = new DateTime($dateTo . ' 23:59:59');
}
if ($withPhone) {
    $filter['!PERSONAL_PHONE'] = false;
}

// Получаем пользователей
$users = UserTable::getList([
    'select' => ['ID', 'NAME', 'LAST_NAME', 'EMAIL', 'PERSONAL_PHONE', 'DATE_REGISTER'],
    'filter' => $filter,
    'order' => ['ID' => 'ASC']
])->fetchAll();

// Если запрос был с параметром export=Y, создаём Excel
if (isset($_GET['export']) && $_GET['export'] === 'Y') {
    $spreadsheet = new Spreadsheet();
    $sheet = $spreadsheet->getActiveSheet();

    // Заголовки
    $sheet->fromArray(
        ['ID', 'Имя', 'Фамилия', 'Email', 'Телефон', 'Дата регистрации'],
        null,
        'A1'
    );

    // Данные
    $row = 2;
    foreach ($users as $user) {
        $sheet->setCellValue("A{$row}", $user['ID']);
        $sheet->setCellValue("B{$row}", $user['NAME']);
        $sheet->setCellValue("C{$row}", $user['LAST_NAME']);
        $sheet->setCellValue("D{$row}", $user['EMAIL']);
        $sheet->setCellValue("E{$row}", $user['PERSONAL_PHONE']);
        $sheet->setCellValue("F{$row}", $user['DATE_REGISTER'] instanceof DateTime
            ? $user['DATE_REGISTER']->format('Y-m-d H:i:s')
            : $user['DATE_REGISTER']
        );
        $row++;
    }

    // Отдаём файл
    header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
    header('Content-Disposition: attachment;filename="users.xlsx"');
    header('Cache-Control: max-age=0');

    $writer = new Xlsx($spreadsheet);
    $writer->save('php://output');
    exit;
}
?> <!-- Форма фильтра -->
<form method="GET">
 <label>Дата от: <input type="date" name="date_from" value="<?=htmlspecialchars($dateFrom)?>"></label> <label>до: <input type="date" name="date_to" value="<?=htmlspecialchars($dateTo)?>"></label> <label>
	<?= $withPhone ? 'checked' : '' ?>Только с телефоном </label> <button type="submit" name="export" value="Y">Выгрузить в Excel</button>
</form>