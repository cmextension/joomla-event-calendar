<?php

/**
 * @package     EventCalendar
 * @subpackage  com_eventcalendar
 * @copyright   Copyright (C) 2025 CMExension
 * @license     GNU General Public License version 2 or later
 */

namespace CMExtension\Component\EventCalendar\Administrator\Controller;

use Joomla\CMS\MVC\Controller\FormController;
use Joomla\CMS\Router\Route;

\defined('_JEXEC') or die;

/**
 * Resource controller class.
 *
 * @since  0.1.0
 */
class ResourceController extends FormController
{
    /**
     * The prefix to use with controller messages.
     *
     * @var    string
     * @since  0.1.0
     */
    protected $text_prefix = 'COM_EVENTCALENDAR_RESOURCE';

    /**
     * Method to run batch operations.
     *
     * @param   string  $model  The model
     *
     * @return  boolean  True on success.
     *
     * @since   0.1.0
     */
    public function batch($model = null)
    {
        $this->checkToken();

        // Set the model.
        $model = $this->getModel('Resource', '', []);

        // Preset the redirect.
        $this->setRedirect(Route::_('index.php?option=com_eventcalendar&view=resources' . $this->getRedirectToListAppend(), false));

        return parent::batch($model);
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
     * @since   0.1.0
     */
    protected function postSaveHook($model, $validData = [])
    {
        /** @var ResourceModel $model */
        $resource = $model->getItem();

        /** @var EventResourceModel $eventResourceModel */
        $eventResourceModel = $this->getModel('EventResource', 'Administrator');
        $eventResourceModel->deleteByResourceId($resource->id);

        $eventIds = $validData['event_ids'] ?? [];

        if ($eventIds) {
            foreach ($eventIds as $eventId) {
                $eventResourceModel->save($eventId, $resource->id);
            }
        }
    }
}
