<?php

/**
 * @package     EventCalendar
 * @subpackage  com_eventcalendar
 * @copyright   Copyright (C) 2024 CMExension
 * @license     GNU General Public License version 2 or later
 */

defined('_JEXEC') or die;

use CMExtension\Component\EventCalendar\Administrator\View\Actionlogs\HtmlView;
use Joomla\CMS\Session\Session;

/** @var HtmlView $this */

/** @var Joomla\CMS\WebAsset\WebAssetManager $wa */
$wa = $this->document->getWebAssetManager();
$wa->useScript('keepalive')
    ->useScript('com_eventcalendar.calendar-admin')
    ->useStyle('com_eventcalendar.calendar-admin')
    ->useScript('joomla.dialog-autocreate');
?>
<div id="ec"></div>

<input type="hidden" id="eventCalendarToken" value="<?php echo Session::getFormToken(); ?>">