<?php

use Joomla\CMS\Factory;
use Joomla\CMS\Language\Text;

\defined('_JEXEC') or die;

/** @var CMSApplication $app */
$app = Factory::getApplication();

$wa = $app->getDocument()->getWebAssetManager();
$wa->addInlineScript(
    "window.copyToFrontend = function () {
    jQuery('#jform_default_frontend_toolbar_start').val(jQuery('#toolbarStartDemo').val());
    jQuery('#jform_default_frontend_toolbar_center').val(jQuery('#toolbarCenterDemo').val());
    jQuery('#jform_default_frontend_toolbar_end').val(jQuery('#toolbarEndDemo').val());
};
window.copyToBackend = function () {
    jQuery('#jform_default_backend_toolbar_start').val(jQuery('#toolbarStartDemo').val());
    jQuery('#jform_default_backend_toolbar_center').val(jQuery('#toolbarCenterDemo').val());
    jQuery('#jform_default_backend_toolbar_end').val(jQuery('#toolbarEndDemo').val());
};",
    ['name' => 'com_eventcalendar.admin.layout.toolbarhelp'],
    ['type' => 'module']
);
?>
<p><?php echo Text::_('COM_EVENTCALENDAR_CONFIG_FIELD_TOOLBAR_HELP_LINE_1'); ?></p>

<p><?php echo Text::_('COM_EVENTCALENDAR_CONFIG_FIELD_TOOLBAR_HELP_LINE_2'); ?>

<ul>
    <li><code>title</code></li>
    <li><code>prev</code></li>
    <li><code>next</code></li>
    <li><code>today</code></li>
</ul>

<p><?php echo Text::_('COM_EVENTCALENDAR_CONFIG_FIELD_TOOLBAR_HELP_LINE_3'); ?>

<ul>
    <li><code>dayGridMonth</code></li>
    <li><code>listDay</code></li>
    <li><code>listWeek</code></li>
    <li><code>listMonth</code></li>
    <li><code>listYear</code></li>
    <li><code>resourceTimeGridDay</code></li>
    <li><code>resourceTimeGridWeek</code></li>
    <li><code>resourceTimelineDay</code></li>
    <li><code>resourceTimelineWeek</code></li>
    <li><code>resourceTimelineMonth</code></li>
    <li><code>timeGridDay</code></li>
    <li><code>timeGridWeek</code></li>
</ul>

<p><?php echo Text::_('COM_EVENTCALENDAR_CONFIG_FIELD_TOOLBAR_HELP_LINE_4'); ?></p>

<div class="form-grid">
    <div class="control-group">
        <div class="control-label">
            <label><?php echo Text::_('COM_EVENTCALENDAR_CONFIG_FIELD_TOOLBAR_START'); ?></label>
        </div>
        <div class="controls">
            <input id="toolbarStartDemo" type="text" value="prev,next" class="form-control" readonly>
        </div>
    </div>
    <div class="control-group">
        <div class="control-label">
            <label><?php echo Text::_('COM_EVENTCALENDAR_CONFIG_FIELD_TOOLBAR_CENTER'); ?></label>
        </div>
        <div class="controls">
            <input id="toolbarCenterDemo" type="text" value="title" class="form-control" readonly>
        </div>
    </div>
    <div class="control-group">
        <div class="control-label">
            <label><?php echo Text::_('COM_EVENTCALENDAR_CONFIG_FIELD_TOOLBAR_END'); ?></label>
        </div>
        <div class="controls">
            <input id="toolbarEndDemo" type="text" value="dayGridMonth,timeGridWeek,timeGridDay,listWeek resourceTimeGridWeek,resourceTimelineWeek" class="form-control" readonly>
        </div>
    </div>
    <div class="control-group">
        <div class="control-label"></div>
        <div class="controls">
            <button type="button" class="btn btn-primary" onclick="copyToFrontend()"><?php echo Text::_('COM_EVENTCALENDAR_CONFIG_COPY_TO_FRONTEND'); ?></button>
            <button type="button" class="btn btn-primary" onclick="copyToBackend()"><?php echo Text::_('COM_EVENTCALENDAR_CONFIG_COPY_TO_BACKEND'); ?></button>
        </div>
    </div>
</div>