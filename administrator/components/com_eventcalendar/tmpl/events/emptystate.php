<?php

/**
 * @package     EventCalendar
 * @subpackage  com_eventcalendar
 * @copyright   Copyright (C) 2024 CMExension
 * @license     GNU General Public License version 2 or later
 */

\defined('_JEXEC') or die;

use Joomla\CMS\Layout\LayoutHelper;

/** @var \CMExtension\Component\EventCalendar\Administrator\View\Events\HtmlView $this */

$displayData = [
    'textPrefix' => 'COM_EVENTCALENDAR_EVENTS',
    'formURL'    => 'index.php?option=com_eventcalendar&view=events',
    'icon'       => 'icon-calendar',
];

$user = $this->getCurrentUser();

if ($user->authorise('core.create', 'com_eventcalendar') || count($user->getAuthorisedCategories('com_eventcalendar', 'core.create')) > 0) {
    $displayData['createURL'] = 'index.php?option=com_eventcalendar&task=event.add';
}

echo LayoutHelper::render('joomla.content.emptystate', $displayData);
