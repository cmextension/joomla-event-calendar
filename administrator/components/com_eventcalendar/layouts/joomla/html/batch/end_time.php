<?php

/**
 * @package     EventCalendar
 * @subpackage  com_eventcalendar
 * @copyright   Copyright (C) 2024 CMExension
 * @license     GNU General Public License version 2 or later
 */

use Joomla\CMS\HTML\HTMLHelper;
use Joomla\CMS\Language\Text;

\defined('_JEXEC') or die;

?>
<label id="batch-end-time-lbl" for="batch-end-time-id">
    <?php echo Text::_('COM_EVENTCALENDAR_BATCH_END_TIME_LABEL'); ?>
</label>
<?php echo HTMLHelper::_('calendar', null, 'batch[end_time]', 'batch_end_time', '%Y-%m-%d %H:%M:%S'); ?>