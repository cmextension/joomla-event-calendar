<?php

/**
 * @package     EventCalendar
 * @subpackage  com_eventcalendar
 * @copyright   Copyright (C) 2024 CMExension
 * @license     GNU General Public License version 2 or later
 */

use Joomla\CMS\Language\Text;

\defined('_JEXEC') or die;

$app = $this->app;
$params = $this->params;

$linkTarget = $params->get('event_link_target', '_self');

$eventCalendarLocale = $app->getLanguage()->getTag();
$defaultCalendarView = $params->get('default_frontend_view', 'timeGridWeek');
$eventCalendarView = $app->getParams()->get('calendar_view', $defaultCalendarView);

$toolbarStart = $params->get('default_frontend_toolbar_start', '');
$toolbarCenter = $params->get('default_frontend_toolbar_center', '');
$toolbarEnd = $params->get('default_frontend_toolbar_end', '');

$eventCalendarConfig = [
    'link_target'   => $linkTarget,
    'locale'        => $eventCalendarLocale,
    'view'          => $eventCalendarView,
    'headerToolbar' => [
        'start'     => $toolbarStart,
        'center'    => $toolbarCenter,
        'end'       => $toolbarEnd,
    ],
    'buttonText'    => [
        'close'                 => Text::_('COM_EVENTCALENDAR_CALENDAR_BUTTON_CLOSE'),
        'dayGridMonth'          => Text::_('COM_EVENTCALENDAR_CALENDAR_BUTTON_DAYGRIDMONTH'),
        'listDay'               => Text::_('COM_EVENTCALENDAR_CALENDAR_BUTTON_LISTDAY'),
        'listMonth'             => Text::_('COM_EVENTCALENDAR_CALENDAR_BUTTON_LISTMONTH'),
        'listWeek'              => Text::_('COM_EVENTCALENDAR_CALENDAR_BUTTON_LISTWEEK'),
        'listYear'              => Text::_('COM_EVENTCALENDAR_CALENDAR_BUTTON_LISTYEAR'),
        'resourceTimeGridDay'   => Text::_('COM_EVENTCALENDAR_CALENDAR_BUTTON_RESOURCETIMEGRIDDAY'),
        'resourceTimeGridWeek'  => Text::_('COM_EVENTCALENDAR_CALENDAR_BUTTON_RESOURCETIMEGRIDWEEK'),
        'resourceTimelineDay'   => Text::_('COM_EVENTCALENDAR_CALENDAR_BUTTON_RESOURCETIMELINEDAY'),
        'resourceTimelineMonth' => Text::_('COM_EVENTCALENDAR_CALENDAR_BUTTON_RESOURCETIMELINEMONTH'),
        'resourceTimelineWeek'  => Text::_('COM_EVENTCALENDAR_CALENDAR_BUTTON_RESOURCETIMELINEWEEK'),
        'timeGridDay'           => Text::_('COM_EVENTCALENDAR_CALENDAR_BUTTON_TIMEGRIDAY'),
        'timeGridWeek'          => Text::_('COM_EVENTCALENDAR_CALENDAR_BUTTON_TIMEGRIDWEEK'),
        'today'                 => Text::_('COM_EVENTCALENDAR_CALENDAR_BUTTON_TODAY'),
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