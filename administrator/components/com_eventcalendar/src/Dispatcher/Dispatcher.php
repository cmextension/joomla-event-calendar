<?php

/**
 * @package     EventCalendar
 * @subpackage  com_eventcalendar
 * @copyright   Copyright (C) 2024 CMExension
 * @license     GNU General Public License version 2 or later
 */

namespace CMExtension\Component\EventCalendar\Administrator\Dispatcher;

use Joomla\CMS\Access\Exception\NotAllowed;
use Joomla\CMS\Dispatcher\ComponentDispatcher;

\defined('_JEXEC') or die;

/**
 * ComponentDispatcher class for com_eventcalendar.
 *
 * @since  0.0.1
 */
class Dispatcher extends ComponentDispatcher
{
    /**
     * Method to check component access permission.
     *
     * @return  void
     *
     * @since   0.0.1
     */
    protected function checkAccess()
    {
        $user = $this->app->getIdentity();

        if (!$user->authorise('core.admin')) {
            throw new NotAllowed($this->app->getLanguage()->_('JERROR_ALERTNOAUTHOR'), 403);
        }
    }
}
