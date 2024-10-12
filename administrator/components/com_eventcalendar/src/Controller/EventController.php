<?php

/**
 * @package     EventCalendar
 * @subpackage  com_eventcalendar
 * @copyright   Copyright (C) 2024 CMExension
 * @license     GNU General Public License version 2 or later
 */

namespace CMExtension\Component\EventCalendar\Administrator\Controller;

use Joomla\CMS\MVC\Controller\FormController;
use Joomla\CMS\MVC\Model\BaseDatabaseModel;
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

    /**
     * Method to cancel an edit.
     *
     * @param   string  $key  The name of the primary key of the URL variable.
     *
     * @return  boolean  True if access level checks pass, false otherwise.
     *
     * @since   0.0.2
     */
    public function cancel($key = null)
    {
        $result = parent::cancel($key);

        // When editing in modal then redirect to modalreturn layout
        if ($result && $this->input->get('layout') === 'modal') {
            $id     = $this->input->get('id');
            $return = 'index.php?option=' . $this->option . '&view=' . $this->view_item . $this->getRedirectToItemAppend($id)
                . '&layout=modalreturn&from-task=cancel';

            $this->setRedirect(Route::_($return, false));
        }

        return $result;
    }

    /**
     * Function that allows child controller access to model data
     * after the data has been saved.
     *
     * @param   BaseDatabaseModel  $model      The data model object.
     * @param   array              $validData  The validated data.
     *
     * @return  void
     *
     * @since   0.0.2
     */
    protected function postSaveHook($model, $validData = [])
    {
        /** @var EventModel $model */
        $event = $model->getItem();

        /** @var EventResourceModel $eventResourceModel */
        $eventResourceModel = $this->getModel('EventResource', 'Administrator');
        $eventResourceModel->deleteByEventId($event->id);

        $resourceIds = $validData['resource_ids'] ?? [];

        if ($resourceIds) {
            foreach ($resourceIds as $resourceId) {
                $eventResourceModel->save($event->id, $resourceId);
            }
        }

        if ($this->input->get('layout') === 'modal' && $this->task === 'save') {
            // When editing in modal then redirect to modalreturn layout.
            $id     = $model->getState('event.id', '');
            $return = 'index.php?option=' . $this->option . '&view=' . $this->view_item . $this->getRedirectToItemAppend($id)
                . '&layout=modalreturn&from-task=save';

            $this->setRedirect(Route::_($return, false));
        }
    }
}
