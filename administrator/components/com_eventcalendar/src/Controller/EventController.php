<?php

/**
 * @package     EventCalendar
 * @subpackage  com_eventcalendar
 * @copyright   Copyright (C) 2024 CMExension
 * @license     GNU General Public License version 2 or later
 */

namespace CMExtension\Component\EventCalendar\Administrator\Controller;

use Joomla\CMS\MVC\Controller\FormController;
use Joomla\CMS\Router\Route;

\defined('_JEXEC') or die;

/**
 * Event controller class.
 *
 * @since  0.0.2
 */
class EventController extends FormController
{
    /**
     * The prefix to use with controller messages.
     *
     * @var    string
     * @since  0.0.2
     */
    protected $text_prefix = 'COM_EVENTCALENDAR_EVENT';

    /**
     * Method to run batch operations.
     *
     * @param   string  $model  The model
     *
     * @return  boolean  True on success.
     *
     * @since   0.0.2
     */
    public function batch($model = null)
    {
        $this->checkToken();

        // Set the model.
        $model = $this->getModel('Event', '', []);

        // Preset the redirect.
        $this->setRedirect(Route::_('index.php?option=com_eventcalendar&view=events' . $this->getRedirectToListAppend(), false));

        return parent::batch($model);
    }
}
