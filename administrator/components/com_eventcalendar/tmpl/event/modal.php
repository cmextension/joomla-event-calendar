<?php

/**
 * @package     EventCalendar
 * @subpackage  com_eventcalendar
 * @copyright   Copyright (C) 2024 CMExension
 * @license     GNU General Public License version 2 or later
 */

\defined('_JEXEC') or die;

/** @var \CMExtension\Component\EventCalendar\Administrator\View\Event\HtmlView $this */

/** @var \Joomla\CMS\Document\HtmlDocument $doc */
$doc = $this->getDocument();
?>
<div class="com_eventcalendar">
    <div class="subhead noshadow mb-3">
        <?php echo $doc->getToolbar('toolbar')->render(); ?>
    </div>
    <div class="container-popup">
        <?php $this->setLayout('edit'); ?>
        <?php echo $this->loadTemplate(); ?>
    </div>
</div>