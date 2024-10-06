<?php

/**
 * @package     EventCalendar
 * @subpackage  com_eventcalendar
 * @copyright   Copyright (C) 2024 CMExension
 * @license     GNU General Public License version 2 or later
 */

\defined('_JEXEC') or die;

/** @var Joomla\CMS\WebAsset\WebAssetManager $wa */
$wa = $this->document->getWebAssetManager();
$wa->useScript('keepalive')
    ->useScript('com_eventcalendar.site-calendar')
    ->useStyle('com_eventcalendar.site-calendar');
?>
<div class="com_eventcalendar">
    <div class="calendar-container">
        <div id="ec"></div>
    </div>
</div>