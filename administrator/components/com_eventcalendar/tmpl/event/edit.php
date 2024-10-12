<?php

/**
 * @package     EventCalendar
 * @subpackage  com_eventcalendar
 * @copyright   Copyright (C) 2024 CMExension
 * @license     GNU General Public License version 2 or later
 */

use Joomla\CMS\Factory;
use Joomla\CMS\HTML\HTMLHelper;
use Joomla\CMS\Language\Text;
use Joomla\CMS\Layout\LayoutHelper;
use Joomla\CMS\Router\Route;

\defined('_JEXEC') or die;

/** @var \CMExtension\Component\EventCalendar\Administrator\View\Event\HtmlView $this */

/** @var Joomla\CMS\WebAsset\WebAssetManager $wa */
$wa = $this->document->getWebAssetManager();
$wa->useScript('keepalive')
    ->useScript('form.validate');

$form = $this->form;

// In case of modal
$input      = Factory::getApplication()->getInput();
$isModal    = $input->get('layout') === 'modal';
$layout     = $isModal ? 'modal' : 'edit';
$tmpl       = $input->get('tmpl');
$tmpl       = $tmpl ? '&tmpl=' . $tmpl : '';
?>
<div class="com_eventcalendar">
    <form action="<?php echo Route::_('index.php?option=com_eventcalendar&layout=' . $layout . $tmpl . '&id=' . (int) $this->item->id); ?>" method="post" name="adminForm" id="event-form" aria-label="<?php echo Text::_('COM_EVENTCALENDAR_MANAGER_EVENT_' . ((int) $this->item->id === 0 ? 'NEW' : 'EDIT'), true); ?>" class="form-validate">
        <div class="main-card">
            <?php echo HTMLHelper::_('uitab.startTabSet', 'myTab', ['active' => 'details', 'recall' => true, 'breakpoint' => 768]); ?>
            <?php echo HTMLHelper::_('uitab.addTab', 'myTab', 'details', Text::_('COM_EVENTCALENDAR_EVENT_DETAILS')); ?>
            <div class="row">
                <div class="col-12 col-md-6">
                    <?php echo $form->renderField('name'); ?>
                    <?php echo $form->renderField('resource_ids'); ?>
                    <?php echo $form->renderField('start_time'); ?>
                    <?php echo $form->renderField('end_time'); ?>
                    <?php echo $form->renderField('all_day'); ?>
                    <?php echo $form->renderField('language'); ?>
                    <?php echo $form->renderField('state'); ?>
                </div>
                <div class="col-12 col-md-6">
                    <?php echo $form->renderField('background_color'); ?>
                    <?php echo $form->renderField('text_color'); ?>
                    <?php echo $form->renderField('class_names'); ?>
                    <?php echo $form->renderField('styles'); ?>
                </div>
            </div>
            <?php echo HTMLHelper::_('uitab.endTab'); ?>
            <?php echo HTMLHelper::_('uitab.addTab', 'myTab', 'publishing', Text::_('JGLOBAL_FIELDSET_PUBLISHING')); ?>
            <?php echo LayoutHelper::render('joomla.edit.publishingdata', $this); ?>
            <?php echo HTMLHelper::_('uitab.endTab'); ?>
            <?php echo HTMLHelper::_('uitab.endTabSet'); ?>

            <input type="hidden" name="task" value="">
            <?php echo HTMLHelper::_('form.token'); ?>

            <?php if ($isModal && $this->item->id) : ?>
                <input type="hidden" name="cid[]" value="<?php echo $this->item->id; ?>" />
            <?php endif; ?>
    </form>
</div>