<?php

/**
 * @package     EventCalendar
 * @subpackage  com_eventcalendar
 * @copyright   Copyright (C) 2024 CMExension
 * @license     GNU General Public License version 2 or later
 */

\defined('_JEXEC') or die;

$eventCalendarLocale = $this->app->getLanguage()->getTag();
$defaultCalendarView = $this->params->get('default_calendar_view', 'timeGridWeek');
$eventCalendarView = $this->app->getParams()->get('calendar_view', $defaultCalendarView);

$eventCalendarConfig = [
    'locale'    => $eventCalendarLocale,
    'view'      => $eventCalendarView,
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