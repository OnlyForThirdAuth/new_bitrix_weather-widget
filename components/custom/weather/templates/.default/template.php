<?php if (!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED !== true) die(); ?>

<div class="weather-widget" style="padding:10px; border:1px solid #eee; border-radius:8px; text-align:center; background: #fff;">
    <?php if (!empty($arResult['ICON'])): ?>
        <img src="https://yastatic.net/weather/i/icons/funky/dark/<?= htmlspecialchars($arResult['ICON']) ?>.svg" alt="Погода">
    <?php endif; ?>
    <div style="font-size:18px; margin-top:5px;">
        <?= htmlspecialchars($arResult['TEMP']) ?>°C — <?= htmlspecialchars($arResult['CONDITION']) ?>
    </div>
</div>