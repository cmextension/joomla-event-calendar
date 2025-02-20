<?php

/**
 * @package     EventCalendar
 * @subpackage  com_eventcalendar
 * @copyright   Copyright (C) 2025 CMExtension
 * @license     GNU General Public License version 2 or later
 */

\defined('_JEXEC') or die;

/** @var \CMExtension\Component\EventCalendar\Administrator\View\Event\HtmlView $this */

$data = ['messageType' => 'com_eventcalendar:close-event-modal'];

// Use modal-content-select to send message.
// media/com_eventcalendar/js/admin-calendar.js receives the message and close the modal.
/** @var Joomla\CMS\WebAsset\WebAssetManager $wa */
$wa = $this->getDocument()->getWebAssetManager();
$wa->useScript('modal-content-select');

// The data for Content select script.
$this->getDocument()->addScriptOptions('content-select-on-load', $data, false);
