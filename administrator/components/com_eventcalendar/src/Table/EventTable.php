<?php

/**
 * @package     EventCalendar
 * @subpackage  com_eventcalendar
 * @copyright   Copyright (C) 2024 CMExension
 * @license     GNU General Public License version 2 or later
 */

namespace CMExtension\Component\EventCalendar\Administrator\Table;

use Joomla\CMS\Factory;
use Joomla\CMS\Language\Text;
use Joomla\CMS\Table\Table;
use Joomla\Database\DatabaseDriver;
use Joomla\Event\DispatcherInterface;

\defined('_JEXEC') or die;

/**
 * Event table.
 *
 * @since  0.0.2
 */
class EventTable extends Table
{
    /**
     * Constructor.
     *
     * @param   DatabaseDriver        $db          Database connector object
     * @param   ?DispatcherInterface  $dispatcher  Event dispatcher for this table
     *
     * @since   0.0.2
     */
    public function __construct(DatabaseDriver $db, DispatcherInterface $dispatcher = null)
    {
        parent::__construct('#__eventcalendar_events', 'id', $db, $dispatcher);

        $this->created = Factory::getDate()->toSql();
        $this->setColumnAlias('published', 'state');
    }

    /**
     * Overloaded check function.
     *
     * @return  boolean
     *
     * @see     Table::check
     * @since   0.0.2
     */
    public function check()
    {
        try {
            parent::check();
        } catch (\Exception $e) {
            $this->setError($e->getMessage());

            return false;
        }

        // Check for valid name.
        if (trim($this->name) === '') {
            $this->setError(Text::_('COM_EVENTCALENDAR_ERROR_PROVIDE_VALID_NAME'));

            return false;
        }

        // Check for date and time.
        if (trim($this->start_time) === '') {
            $this->setError(Text::_('COM_EVENTCALENDAR_ERROR_PROVIDE_VALID_START_TIME'));

            return false;
        }

        if (trim($this->end_time) === '') {
            $this->setError(Text::_('COM_EVENTCALENDAR_ERROR_PROVIDE_VALID_END_TIME'));

            return false;
        }


        // Set created date if not set.
        if (!(int) $this->created) {
            $this->created = Factory::getDate()->toSql();
        }

        // Check the ending date time is not earlier than starting date time.
        if (!\is_null($this->end_time) && !\is_null($this->start_time) && $this->end_date < $this->start_date) {
            $this->setError(Text::_('JGLOBAL_START_PUBLISH_AFTER_FINISH'));

            return false;
        }

        // Set modified to created if not set.
        if (!$this->modified) {
            $this->modified = $this->created;
        }

        // Set modified_by to created_by if not set.
        if (empty($this->modified_by)) {
            $this->modified_by = $this->created_by;
        }

        return true;
    }

    /**
     * Method to store a row.
     *
     * @param   boolean  $updateNulls  True to update fields even if they are null.
     *
     * @return  boolean  True on success, false on failure.
     *
     * @since   0.0.2
     */
    public function store($updateNulls = true)
    {
        // Store the new row.
        parent::store($updateNulls);

        return \count($this->getErrors()) == 0;
    }
}
