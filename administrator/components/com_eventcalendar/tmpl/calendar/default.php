<?php

/**
 * @package     EventCalendar
 * @subpackage  com_eventcalendar
 * @copyright   Copyright (C) 2024 CMExension
 * @license     GNU General Public License version 2 or later
 */

defined('_JEXEC') or die;

use CMExtension\Component\EventCalendar\Administrator\View\Actionlogs\HtmlView;
use Joomla\CMS\Language\Text;
use Joomla\CMS\Router\Route;
use Joomla\CMS\Session\Session;

/** @var HtmlView $this */

$eventCalendarLocale = $this->app->getLanguage()->getTag();
$eventCalendarView = $this->params->get('default_calendar_view', 'timeGridWeek');

$eventCalendarConfig = [
    'locale'    => $eventCalendarLocale,
    'view'      => $eventCalendarView,
];

/** @var Joomla\CMS\WebAsset\WebAssetManager $wa */
$wa = $this->document->getWebAssetManager();
$wa->useScript('keepalive')
    ->useScript('com_eventcalendar.admin-calendar')
    ->useStyle('com_eventcalendar.admin-calendar')
    ->useScript('joomla.dialog-autocreate')
    ->addInlineScript('let eventCalendarConfig = ' . json_encode($eventCalendarConfig) . ';');

$popupOptions = [
    'popupType'  => 'iframe',
    'textHeader' => Text::_('COM_EVENTCALENDAR_MANAGER_EVENT_NEW'),
    'src'        => Route::_('index.php?option=com_eventcalendar&view=event&layout=modal&tmpl=component', false),
];
?>
<div class="com_eventcalendar">
    <div class="text-end">
        <button type="button" class="btn btn-primary"
            data-joomla-dialog="<?php echo $this->escape(json_encode($popupOptions, JSON_UNESCAPED_SLASHES)); ?>">
            <i class="fa fa-plus"></i>
            <?php echo Text::_('COM_EVENTCALENDAR_ADD_EVENT'); ?>
        </button>
    </div>

    <div class="calendar-container">
        <div id="ec"></div>
    </div>

    <input type="hidden" id="eventCalendarToken" value="<?php echo Session::getFormToken(); ?>">
</div>