<?php

/**
 * @package     EventCalendar
 * @subpackage  mod_eventcalendar
 * @copyright   Copyright (C) 2025 CMExension
 * @license     GNU General Public License version 2 or later
 */

// phpcs:disable PSR1.Files.SideEffects
\defined('_JEXEC') or die;
// phpcs:enable PSR1.Files.SideEffects

use Joomla\CMS\Layout\FileLayout;

$modId = 'mod-eventcalendar' . $module->id;
$layout = new FileLayout('calendar', JPATH_ROOT . '/components/com_eventcalendar/layouts');
?>

<div id="<?php echo $modId; ?>" class="mod-eventcalendar">
    <?php echo $layout->render(); ?>
</div>