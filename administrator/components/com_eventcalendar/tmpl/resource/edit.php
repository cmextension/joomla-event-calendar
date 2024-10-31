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

/** @var \CMExtension\Component\EventCalendar\Administrator\View\Resource\HtmlView $this */

/** @var Joomla\CMS\WebAsset\WebAssetManager $wa */
$wa = $this->getDocument()->getWebAssetManager();
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
    <form action="<?php echo Route::_('index.php?option=com_eventcalendar&layout=' . $layout . $tmpl . '&id=' . (int) $this->item->id); ?>" method="post" name="adminForm" id="resource-form" aria-label="<?php echo Text::_('COM_EVENTCALENDAR_MANAGER_RESOURCE_' . ((int) $this->item->id === 0 ? 'NEW' : 'EDIT'), true); ?>" class="form-validate">
        <div class="main-card">
            <?php echo HTMLHelper::_('uitab.startTabSet', 'myTab', ['active' => 'details', 'recall' => true, 'breakpoint' => 768]); ?>
            <?php foreach ($form->getFieldsets() as $fieldset) : ?>
                <?php echo HTMLHelper::_('uitab.addTab', 'myTab', $fieldset->name, Text::_($fieldset->label)); ?>
                <?php echo $form->renderFieldset($fieldset->name); ?>
                <?php echo HTMLHelper::_('uitab.endTab'); ?>
            <?php endforeach; ?>
            <?php echo HTMLHelper::_('uitab.endTabSet'); ?>

            <input type="hidden" name="task" value="">
            <?php echo HTMLHelper::_('form.token'); ?>

            <?php if ($isModal && $this->item->id) : ?>
                <input type="hidden" name="cid[]" value="<?php echo $this->item->id; ?>" />
            <?php endif; ?>
    </form>
</div>