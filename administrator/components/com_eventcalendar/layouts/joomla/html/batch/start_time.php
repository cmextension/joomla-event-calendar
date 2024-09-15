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
<label id="batch-start-time-lbl" for="batch-start-time-id">
    <?php echo Text::_('COM_EVENTCALENDAR_BATCH_START_TIME_LABEL'); ?>
</label>
<?php echo HTMLHelper::_('calendar', null, 'batch[start_time]', 'batch_start_time', '%Y-%m-%d %H:%M:%S'); ?>