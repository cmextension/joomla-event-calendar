<?php

/**
 * @package     EventCalendar
 * @subpackage  com_eventcalendar
 * @copyright   Copyright (C) 2025 CMExtension
 * @license     GNU General Public License version 2 or later
 */

use Joomla\CMS\HTML\HTMLHelper;
use Joomla\CMS\Language\Multilanguage;
use Joomla\CMS\Language\Text;
use Joomla\CMS\Layout\LayoutHelper;
use Joomla\CMS\Router\Route;

\defined('_JEXEC') or die;

/** @var \CMExtension\Component\EventCalendar\Administrator\View\Events\HtmlView $this */

/** @var \Joomla\CMS\WebAsset\WebAssetManager $wa */
$wa = $this->getDocument()->getWebAssetManager();
$wa->useScript('table.columns')
    ->useScript('multiselect');

$user           = $this->getCurrentUser();
$userId         = $user->get('id');
$listOrder      = $this->escape($this->state->get('list.ordering'));
$listDirn       = $this->escape($this->state->get('list.direction'));
$canCreate      = $user->authorise('core.create', 'com_eventcalendar');
$canEdit        = $user->authorise('core.edit', 'com_eventcalendar');
$canEditState   = $user->authorise('core.edit.state', 'com_eventcalendar');
?>
<div class="com_eventcalendar">
    <form action="<?php echo Route::_('index.php?option=com_eventcalendar&view=events'); ?>" method="post" name="adminForm" id="adminForm">
        <div class="row">
            <div class="col-md-12">
                <div id="j-main-container" class="j-main-container">
                    <?php
                    // Search tools bar
                    echo LayoutHelper::render('joomla.searchtools.default', ['view' => $this]);
                    ?>
                    <?php if (empty($this->items)) : ?>
                        <div class="alert alert-info">
                            <span class="icon-info-circle" aria-hidden="true"></span><span class="visually-hidden"><?php echo Text::_('INFO'); ?></span>
                            <?php echo Text::_('JGLOBAL_NO_MATCHING_RESULTS'); ?>
                        </div>
                    <?php else : ?>
                        <table class="table" id="eventList">
                            <caption class="visually-hidden">
                                <?php echo Text::_('COM_EVENTCALENDAR_EVENTS_TABLE_CAPTION'); ?>,
                                <span id="orderedBy"><?php echo Text::_('JGLOBAL_SORTED_BY'); ?> </span>,
                                <span id="filteredBy"><?php echo Text::_('JGLOBAL_FILTERED_BY'); ?></span>
                            </caption>
                            <thead>
                                <tr>
                                    <td class="w-1 text-center">
                                        <?php echo HTMLHelper::_('grid.checkall'); ?>
                                    </td>
                                    <th scope="col" class="w-1 text-center">
                                        <?php echo HTMLHelper::_('searchtools.sort', 'JSTATUS', 'a.state', $listDirn, $listOrder); ?>
                                    </th>
                                    <th scope="col">
                                        <?php echo HTMLHelper::_('searchtools.sort', 'COM_EVENTCALENDAR_FIELD_NAME_LABEL', 'a.name', $listDirn, $listOrder); ?>
                                    </th>
                                    <th scope="col">
                                        <?php echo HTMLHelper::_('searchtools.sort', 'COM_EVENTCALENDAR_FIELD_START_TIME_LABEL', 'a.start_time', $listDirn, $listOrder); ?>
                                    </th>
                                    <th scope="col">
                                        <?php echo HTMLHelper::_('searchtools.sort', 'COM_EVENTCALENDAR_FIELD_END_TIME_LABEL', 'a.end_time', $listDirn, $listOrder); ?>
                                    </th>
                                    <th scope="col" class="text-center">
                                        <?php echo HTMLHelper::_('searchtools.sort', 'COM_EVENTCALENDAR_FIELD_ALL_DAY_LABEL', 'a.all_day', $listDirn, $listOrder); ?>
                                    </th>
                                    <?php if (Multilanguage::isEnabled()) : ?>
                                        <th scope="col" class="w-10 d-none d-md-table-cell text-center">
                                            <?php echo HTMLHelper::_('searchtools.sort', 'JGRID_HEADING_LANGUAGE', 'a.language', $listDirn, $listOrder); ?>
                                        </th>
                                    <?php endif; ?>
                                    <th scope="col" class="w-5 d-none d-md-table-cell text-center">
                                        <?php echo HTMLHelper::_('searchtools.sort', 'JGRID_HEADING_ID', 'a.id', $listDirn, $listOrder); ?>
                                    </th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php foreach ($this->items as $i => $item) :
                                    $canCheckin = $user->authorise('core.manage', 'com_checkin') || $item->checked_out == $userId || is_null($item->checked_out);
                                    $canChange  = $canEditState && $canCheckin;
                                ?>
                                    <tr class="row<?php echo $i % 2; ?>">
                                        <td class="text-center">
                                            <?php echo HTMLHelper::_('grid.id', $i, $item->id, false, 'cid', 'cb', $item->name); ?>
                                        </td>
                                        <td class="text-center">
                                            <?php echo HTMLHelper::_('jgrid.published', $item->state, $i, 'events.', $canChange, 'cb'); ?>
                                        </td>
                                        <th scope="row">
                                            <div class="break-word">
                                                <?php if ($item->checked_out) : ?>
                                                    <?php echo HTMLHelper::_('jgrid.checkedout', $i, $item->editor, $item->checked_out_time, 'events.', $canCheckin); ?>
                                                <?php endif; ?>
                                                <?php if ($canEdit) : ?>
                                                    <a href="<?php echo Route::_('index.php?option=com_eventcalendar&task=event.edit&id=' . (int) $item->id); ?>" title="<?php echo Text::_('JACTION_EDIT'); ?> <?php echo $this->escape($item->name); ?>">
                                                        <?php echo $this->escape($item->name); ?></a>
                                                <?php else : ?>
                                                    <?php echo $this->escape($item->name); ?>
                                                <?php endif; ?>
                                            </div>
                                        </th>
                                        <td><?php echo HTMLHelper::_('date', $item->start_time, Text::_('DATE_FORMAT_LC2')); ?></td>
                                        <td><?php echo HTMLHelper::_('date', $item->end_time, Text::_('DATE_FORMAT_LC2')); ?></td>
                                        <td class="text-center">
                                            <?php if ($item->all_day) : ?>
                                                <i class="fa fa-check"></i>
                                            <?php else : ?>
                                                <i class="fa fa-close"></i>
                                            <?php endif; ?>
                                        </td>
                                        <?php if (Multilanguage::isEnabled()) : ?>
                                            <td class="small d-none d-md-table-cell text-center">
                                                <?php echo LayoutHelper::render('joomla.content.language', $item); ?>
                                            </td>
                                        <?php endif; ?>
                                        <td class="d-none d-md-table-cell text-center">
                                            <?php echo $item->id; ?>
                                        </td>
                                    </tr>
                                <?php endforeach; ?>
                            </tbody>
                        </table>

                        <?php // Load the pagination.
                        ?>
                        <?php echo $this->pagination->getListFooter(); ?>

                        <?php // Load the batch processing form.
                        ?>
                        <?php
                        if (
                            $canCreate
                            && $canEdit
                            && $canEditState
                        ) : ?>
                            <template id="joomla-dialog-batch"><?php echo $this->loadTemplate('batch_body'); ?></template>
                        <?php endif; ?>
                    <?php endif; ?>

                    <input type="hidden" name="task" value="">
                    <input type="hidden" name="boxchecked" value="0">
                    <?php echo HTMLHelper::_('form.token'); ?>
                </div>
            </div>
        </div>
    </form>
</div>