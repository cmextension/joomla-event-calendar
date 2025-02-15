<?php

/**
 * @package     EventCalendar
 * @subpackage  com_eventcalendar
 * @copyright   Copyright (C) 2025 CMExension
 * @license     GNU General Public License version 2 or later
 */

namespace CMExtension\Component\EventCalendar\Administrator\Controller;

use Joomla\CMS\MVC\Controller\AdminController;
use Joomla\CMS\Router\Route;

\defined('_JEXEC') or die;

/**
 * Events list controller class.
 *
 * @since  0.0.2
 */
class EventsController extends AdminController
{
    /**
     * The prefix to use with controller messages.
     *
     * @var    string
     * @since  0.0.2
     */
    protected $text_prefix = 'COM_EVENTCALENDAR_EVENTS';

    /**
     * Method to get a model object, loading it if required.
     *
     * @param   string  $name    The model name. Optional.
     * @param   string  $prefix  The class prefix. Optional.
     * @param   array   $config  Configuration array for model. Optional.
     *
     * @return  \Joomla\CMS\MVC\Model\BaseDatabaseModel  The model.
     *
     * @since   0.0.2
     */
    public function getModel($name = 'Event', $prefix = 'Administrator', $config = ['ignore_request' => true])
    {
        return parent::getModel($name, $prefix, $config);
    }

    /**
     * Function that allows child controller access to model data
     * after the item has been deleted.
     *
     * @param   BaseDatabaseModel  $model  The data model object.
     * @param   integer[]          $id     An array of deleted IDs.
     *
     * @return  void
     *
     * @since   0.0.2
     */
    protected function postDeleteHook($model, $id = null)
    {

        if ($this->input->get('layout') === 'modal') {
            $return = 'index.php?option=' . $this->option . '&view=' . $this->view_list . $this->getRedirectToListAppend() . '&layout=modalreturn';

            $this->setRedirect(Route::_($return, false));
            $this->redirect();
        }
    }
}
