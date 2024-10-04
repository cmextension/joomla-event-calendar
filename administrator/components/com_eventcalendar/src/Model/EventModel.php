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
use Joomla\CMS\Table\Table;
use Joomla\CMS\Event\Model;
use Joomla\CMS\Language\Text;

\defined('_JEXEC') or die;

/**
 * Event model.
 *
 * @since  0.0.2
 */
class EventModel extends AdminModel
{
    /**
     * The prefix to use with controller messages.
     *
     * @var    string
     * @since  0.0.2
     */
    protected $text_prefix = 'COM_EVENTCALENDAR_EVENT';

    /**
     * Allowed batch commands.
     *
     * @var    array
     * @since  0.0.2
     */
    protected $batch_commands = [
        'language_id'   => 'batchLanguage',
        'start_time'    => 'batchStartTime',
        'end_time'      => 'batchEndTime'
    ];

    /**
     * Method to get the record form.
     *
     * @param   array    $data      Data for the form. [optional]
     * @param   boolean  $loadData  True if the form is to load its own data (default case), false if not. [optional]
     *
     * @return  Form|boolean  A Form object on success, false on failure
     *
     * @since   0.0.2
     */
    public function getForm($data = [], $loadData = true)
    {
        // Get the form.
        $form = $this->loadForm('com_eventcalendar.event', 'event', ['control' => 'jform', 'load_data' => $loadData]);

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
                $form->setFieldAttribute('start_time', 'disabled', 'true');
                $form->setFieldAttribute('end_time', 'disabled', 'true');
                $form->setFieldAttribute('state', 'disabled', 'true');

                // Disable fields while saving.
                // The controller has already verified this is a record you can edit.
                $form->setFieldAttribute('start_time', 'filter', 'unset');
                $form->setFieldAttribute('end_time', 'filter', 'unset');
                $form->setFieldAttribute('state', 'filter', 'unset');
            }
        }

        // Don't allow to change the created_by user if not allowed to access com_users.
        if (!$this->getCurrentUser()->authorise('core.manage', 'com_users')) {
            $form->setFieldAttribute('created_by', 'filter', 'unset');
        }

        return $form;
    }

    /**
     * Method to get the data that should be injected in the form.
     *
     * @return  mixed  The data for the form.
     *
     * @since   0.0.2
     */
    protected function loadFormData()
    {
        // Check the session for previously entered form data.
        /** @var CMSApplicationInterface $app */
        $app  = Factory::getApplication();
        $data = $app->getUserState('com_eventcalendar.edit.event.data', []);

        if (empty($data)) {
            $data = $this->getItem();
        }

        $this->preprocessData('com_eventcalendar.event', $data);

        return $data;
    }

    /**
     * Prepare and sanitise the table prior to saving.
     *
     * @param   Table  $table  A Table object.
     *
     * @return  void
     *
     * @since   0.0.2
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
     * @since   0.0.2
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
     * Batch start time changes for a group of rows.
     *
     * @param   string  $value     The new start time.
     * @param   array   $pks       An array of row IDs.
     * @param   array   $contexts  An array of item contexts.
     *
     * @return  boolean  True if successful, false otherwise and internal error is set.
     *
     * @since   0.0.2
     */
    protected function batchStartTime($value, $pks, $contexts)
    {
        // Initialize re-usable member properties, and re-usable local variables.
        $this->initBatch();

        $dispatcher = $this->getDispatcher();

        foreach ($pks as $pk) {
            if ($this->user->authorise('core.edit', $contexts[$pk])) {
                $this->table->reset();
                $this->table->load($pk);
                $this->table->start_time = $value;

                $event = new Model\BeforeBatchEvent(
                    $this->event_before_batch,
                    ['src' => $this->table, 'type' => 'start_time']
                );
                $dispatcher->dispatch($event->getName(), $event);

                try {
                    $this->table->check();
                    $this->table->store();
                } catch (\RuntimeException $e) {
                    throw new \Exception($e->getMessage(), 500, $e);
                }
            } else {
                throw new \Exception(Text::_('JLIB_APPLICATION_ERROR_BATCH_CANNOT_EDIT'));
            }
        }

        // Clean the cache.
        $this->cleanCache();

        return true;
    }

    /**
     * Batch start end changes for a group of rows.
     *
     * @param   string  $value     The new start time.
     * @param   array   $pks       An array of row IDs.
     * @param   array   $contexts  An array of item contexts.
     *
     * @return  boolean  True if successful, false otherwise and internal error is set.
     *
     * @since   0.0.2
     */
    protected function batchEndTime($value, $pks, $contexts)
    {
        // Initialize re-usable member properties, and re-usable local variables.
        $this->initBatch();

        $dispatcher = $this->getDispatcher();

        foreach ($pks as $pk) {
            if ($this->user->authorise('core.edit', $contexts[$pk])) {
                $this->table->reset();
                $this->table->load($pk);
                $this->table->end_time = $value;

                $event = new Model\BeforeBatchEvent(
                    $this->event_before_batch,
                    ['src' => $this->table, 'type' => 'end_time']
                );
                $dispatcher->dispatch($event->getName(), $event);

                try {
                    $this->table->check();
                    $this->table->store();
                } catch (\RuntimeException $e) {
                    throw new \Exception($e->getMessage(), 500, $e);
                }
            } else {
                throw new \Exception(Text::_('JLIB_APPLICATION_ERROR_BATCH_CANNOT_EDIT'));
            }
        }

        // Clean the cache.
        $this->cleanCache();

        return true;
    }

    public function updateEventTime($id, $startTime, $endTime)
    {
        if (!$id) {
            return;
        }

        $db = $this->getDatabase();
        $query = $db->getQuery(true)
            ->update($db->quoteNAme('#__eventcalendar_events'))
            ->set($db->quoteName('start_time') . ' = ' . $db->quote($startTime))
            ->set($db->quoteName('end_time') . ' = ' . $db->quote($endTime))
            ->where($db->quoteName('id') . ' = ' . $db->quote($id));

        $db->setQuery($query)->execute();
    }
}
