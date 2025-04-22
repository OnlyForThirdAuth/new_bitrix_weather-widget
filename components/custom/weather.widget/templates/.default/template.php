<?php if (!defined('B_PROLOG_INCLUDED') || B_PROLOG_INCLUDED !== true) die(); ?>

<div class="sidebar-widget sidebar-widget-tasks weather-widget">
  <?php if (!empty($arResult['ERROR'])): ?>
    <div class="weather-error">Ошибка: <?=htmlspecialchars($arResult['ERROR'])?></div>
  <?php else: ?>
    <div class="sidebar-widget-top">
      <div class="sidebar-widget-top-title">
        <h3>Текущая погода в Москве</h3>
      </div>
    </div>
    <div class="sidebar-widget-content">
      <div class="w-temp"><?=htmlspecialchars($arResult['TEMP'])?>°C, <?=htmlspecialchars($arResult['CONDITION'])?></div>
      <div class="w-details">
        <span>Ветер: <?=htmlspecialchars($arResult['WIND'])?> м/с</span>
        <span>Влажность: <?=htmlspecialchars($arResult['HUMIDITY'])?>%</span>
      </div>
    </div>
  <?php endif; ?>
</div>