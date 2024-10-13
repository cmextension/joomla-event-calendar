<?php

/**
 * @package     EventCalendar
 * @subpackage  com_eventcalendar
 * @copyright   Copyright (C) 2024 CMExension
 * @license     GNU General Public License version 2 or later
 */

\defined('_JEXEC') or die;

$app = $this->app;
$params = $this->params;

$eventCalendarLocale = $app->getLanguage()->getTag();
$defaultCalendarView = $params->get('default_frontend_view', 'timeGridWeek');
$eventCalendarView = $app->getParams()->get('calendar_view', $defaultCalendarView);

$toolbarStart = $params->get('default_frontend_toolbar_start', '');
$toolbarCenter = $params->get('default_frontend_toolbar_center', '');
$toolbarEnd = $params->get('default_frontend_toolbar_end', '');

$eventCalendarConfig = [
    'locale'    => $eventCalendarLocale,
    'view'      => $eventCalendarView,
    'headerToolbar' => [
        'start'     => $toolbarStart,
        'center'    => $toolbarCenter,
        'end'       => $toolbarEnd,
    ]
];
/** @var Joomla\CMS\WebAsset\WebAssetManager $wa */
$wa = $this->document->getWebAssetManager();
$wa->useScript('keepalive')
    ->useScript('com_eventcalendar.site-calendar')
    ->useStyle('com_eventcalendar.site-calendar')
    ->addInlineScript('let eventCalendarConfig = ' . json_encode($eventCalendarConfig) . ';');
?>
<div class="com_eventcalendar">
    <div class="calendar-container">
        <div id="ec"></div>
    </div>
</div>