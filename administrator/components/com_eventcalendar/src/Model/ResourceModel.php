<?php

/**
 * @package     EventCalendar
 * @subpackage  com_eventcalendar
 * @copyright   Copyright (C) 2024 CMExension
 * @license     GNU General Public License version 2 or later
 */

namespace CMExtension\Component\EventCalendar\Administrator\Model;

use Joomla\CMS\Factory;
use Joomla\CMS\Form\Form;
use Joomla\CMS\MVC\Model\AdminModel;
use Joomla\CMS\MVC\Model\BaseDatabaseModel;
use Joomla\CMS\Table\Table;
use Joomla\Utilities\ArrayHelper;

\defined('_JEXEC') or die;

/**
 * Resouce model.
 *
 * @since  0.1.0
 */
class ResourceModel extends AdminModel
{
    /**
     * The prefix to use with controller messages.
     *
     * @var    string
     * @since  0.1.0
     */
    protected $text_prefix = 'COM_EVENTCALENDAR_RESOURCE';

    /**
     * Allowed batch commands.
     *
     * @var    array
     * @since  0.1.0
     */
    protected $batch_commands = [
        'language_id' => 'batchLanguage',
    ];

    /**
     * Method to get the record form.
     *
     * @param   array    $data      Data for the form. [optional]
     * @param   boolean  $loadData  True if the form is to load its own data (default case), false if not. [optional]
     *
     * @return  Form|boolean  A Form object on success, false on failure
     *
     * @since   0.1.0
     */
    public function getForm($data = [], $loadData = true)
    {
        // Load the data later.
        $loadData = false;

        $options = ['control' => 'jform', 'load_data' => $loadData];
        $name = 'com_eventcalendar.event';
        $source = 'event';

        // Get the form.
        $form = $this->loadForm($name, $source, $options);

        if (empty($form)) {
            return false;
        }

        $canEdit = $this->getCurrentUser()->authorise('core.edit', $this->option);

        // If user is not allowed to edit, we disable all fields to avoid confusion.
        if (!$canEdit) {
            foreach ($form->getFieldsets() as $fieldset) {
                foreach ($form->getFieldset($fieldset->name) as $field) {
                    $form->setFieldAttribute($field->fieldname, 'disabled', 'true');
                }
            }
        } else {
            // Modify the form based on access controls.
            if (!$this->canEditState((object) $data)) {
                // Disable fields for display.
                $form->setFieldAttribute('state', 'disabled', 'true');

                // Disable fields while saving.
                // The controller has already verified this is a record you can edit.
                $form->setFieldAttribute('state', 'filter', 'unset');
            }
        }

        // Don't allow to change the created_by user if not allowed to access com_users.
        if (!$this->getCurrentUser()->authorise('core.manage', 'com_users')) {
            $form->setFieldAttribute('created_by', 'filter', 'unset');
        }

        // Load the data and run preprocessForm again to allow the plugins to modify the form.,
        // but this time the plugin group is "eventcalendar" instead of "content".
        $data = $this->loadFormData();
        $this->preprocessForm($form, $data, 'eventcalendar');
        $form->bind($data);

        // Store the form for later use again.
        $options['control'] = ArrayHelper::getValue((array) $options, 'control', false);

        $sigOptions = $options;
        unset($sigOptions['load_data']);

        $hash = md5($source . serialize($sigOptions));

        $this->_forms[$hash] = $form;

        return $form;
    }

    /**
     * Method to get the data that should be injected in the form.
     *
     * @return  mixed  The data for the form.
     *
     * @since   0.1.0
     */
    protected function loadFormData()
    {
        // Check the session for previously entered form data.
        /** @var CMSApplicationInterface $app */
        $app  = Factory::getApplication();
        $data = $app->getUserState('com_eventcalendar.edit.resource.data', []);

        if (empty($data)) {
            $data = $this->getItem();
        }

        $this->preprocessData('com_eventcalendar.resource', $data, 'eventcalendar');

        return $data;
    }

    /**
     * Prepare and sanitise the table prior to saving.
     *
     * @param   Table  $table  A Table object.
     *
     * @return  void
     *
     * @since   0.1.0
     */
    protected function prepareTable($table)
    {
        $date = Factory::getDate();
        $user = $this->getCurrentUser();

        if (empty($table->id)) {
            // Set the values
            $table->created    = $date->toSql();
            $table->created_by = $user->id;
        } else {
            // Set the values
            $table->modified    = $date->toSql();
            $table->modified_by = $user->id;
        }
    }

    /**
     * Method to save the form data.
     *
     * @param   array  $data  The form data.
     *
     * @return  boolean  True on success.
     *
     * @since   0.1.0
     */
    public function save($data)
    {
        $input = Factory::getApplication()->getInput();

        if ($input->get('task') == 'save2copy') {
            $data['state'] = 0;
        }

        return parent::save($data);
    }

    /**
     * Method to get a single record.
     *
     * @param   integer  $pk  The id of the primary key.
     *
     * @return  \stdClass|false  Object on success, false on failure.
     *
     * @since   0.1.0
     */
    public function getItem($pk = null)
    {
        $item = parent::getItem($pk);

        if (!$item) {
            return $item;
        }

        /** @var EventResourceModel $eventResourceModel */
        $eventResourceModel = BaseDatabaseModel::getInstance('EventResource', 'EventCalendarModel');
        $item->event_ids = $eventResourceModel->getEventIdsByResourceId($item->id);

        return $item;
    }
}
