<?php
// Если запрос на экспорт — выполняем сразу, без вывода HTML!
if (isset($_GET['export']) && $_GET['export'] === 'Y') {

    $filter = [
        'DATE_FROM' => $_GET['date_from'],
        'DATE_TO' => $_GET['date_to'],
        'ONLY_WITH_PHONE' => isset($_GET['with_phone']),
    ];

    // Получаем пользователей через компонент
    $users = $this->getComponent()->getUsers($filter);

    // Подключаем файл с функцией экспорта (путь с __DIR__)
    include_once __DIR__ . "/../../lib/excel.php";

    // Вызываем функцию для генерации Excel и отправки файла
    exportToExcel($users);

    // Завершаем скрипт, чтобы не выводить HTML
    exit;
}
?>

<!-- HTML форма для фильтрации и экспорта -->
<form method="GET">
    <label>Дата регистрации от:</label>
    <input type="date" name="date_from" value="<?=htmlspecialchars($_GET['date_from'] ?? '')?>">
    
    <label>до:</label>
    <input type="date" name="date_to" value="<?=htmlspecialchars($_GET['date_to'] ?? '')?>">
    
    <label>
        <input type="checkbox" name="with_phone" <?= isset($_GET['with_phone']) ? 'checked' : '' ?>>
        Только с телефоном
    </label>
    
    <button type="submit" name="export" value="Y">Выгрузить в Excel</button>
</form>