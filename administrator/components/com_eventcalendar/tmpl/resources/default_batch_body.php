<?php

/**
 * @package     EventCalendar
 * @subpackage  com_eventcalendar
 * @copyright   Copyright (C) 2025 CMExtension
 * @license     GNU General Public License version 2 or later
 */

use Joomla\CMS\Language\Multilanguage;
use Joomla\CMS\Language\Text;
use Joomla\CMS\Layout\LayoutHelper;

\defined('_JEXEC') or die;

/** @var \CMExtension\Component\EventCalendar\Administrator\View\Resources\HtmlView $this */

$published = (int) $this->state->get('filter.published');
?>
<div class="com_eventcalendar">
    <div class="p-3">
        <div class="row">
            <?php if (Multilanguage::isEnabled()) : ?>
                <div class="form-group col-md-6">
                    <div class="controls">
                        <?php echo LayoutHelper::render('joomla.html.batch.language', []); ?>
                    </div>
                </div>
            <?php endif; ?>
        </div>
    </div>

    <div class="btn-toolbar p-3">
        <joomla-toolbar-button task="event.batch" class="ms-auto">
            <button type="button" class="btn btn-success"><?php echo Text::_('JGLOBAL_BATCH_PROCESS'); ?></button>
        </joomla-toolbar-button>
    </div>
</div>