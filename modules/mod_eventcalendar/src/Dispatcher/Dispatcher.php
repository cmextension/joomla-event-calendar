<?php

/**
 * @package     EventCalendar
 * @subpackage  mod_eventcalendar
 * @copyright   Copyright (C) 2025 CMExtension
 * @license     GNU General Public License version 2 or later
 */

namespace CMExtension\Module\EventCalendar\Site\Dispatcher;

use Joomla\CMS\Dispatcher\AbstractModuleDispatcher;

// phpcs:disable PSR1.Files.SideEffects
\defined('_JEXEC') or die;
// phpcs:enable PSR1.Files.SideEffects

/**
 * Dispatcher class for mod_eventcalendar.
 *
 * @since  0.3.0
 */
class Dispatcher extends AbstractModuleDispatcher
{
    /**
     * Returns the layout data.
     *
     * @return  array
     *
     * @since   0.3.0
     */
    protected function getLayoutData()
    {
        return parent::getLayoutData();
    }
}
