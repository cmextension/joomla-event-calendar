<?php

/**
 * @package     EventCalendar
 * @subpackage  com_eventcalendar
 * @copyright   Copyright (C) 2024 CMExension
 * @license     GNU General Public License version 2 or later
 */

\defined('_JEXEC') or die;

use Joomla\CMS\Layout\LayoutHelper;

/** @var \CMExtension\Component\EventCalendar\Administrator\View\Resources\HtmlView $this */

$displayData = [
    'textPrefix' => 'COM_EVENTCALENDAR_RESOURCES',
    'formURL'    => 'index.php?option=com_eventcalendar&view=resources',
    'icon'       => 'icon-file-alt',
];

$user = $this->getCurrentUser();

if ($user->authorise('core.create', 'com_eventcalendar')) {
    $displayData['createURL'] = 'index.php?option=com_eventcalendar&task=resource.add';
}

echo LayoutHelper::render('joomla.content.emptystate', $displayData);
