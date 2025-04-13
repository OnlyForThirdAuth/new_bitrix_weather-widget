<?php

?>
<form method="GET">
    <label>Дата регистрации от:
        <input type="date" name="date_from" value="<?= htmlspecialchars($_GET['date_from'] ?? '') ?>">
    </label>
    <label>до:
        <input type="date" name="date_to" value="<?= htmlspecialchars($_GET['date_to'] ?? '') ?>">
    </label>
    <label>
        <input type="checkbox" name="with_phone" <?= isset($_GET['with_phone']) ? 'checked' : '' ?>>
        Только с телефоном
    </label>
    <button type="submit" name="export" value="Y">Выгрузить в Excel</button>
</form>