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
 * Resource table.
 *
 * @since  0.1.0
 */
class ResourceTable extends Table
{
    /**
     * Constructor.
     *
     * @param   DatabaseDriver        $db          Database connector object
     * @param   ?DispatcherInterface  $dispatcher  Event dispatcher for this table
     *
     * @since   0.1.0
     */
    public function __construct(DatabaseDriver $db, DispatcherInterface $dispatcher = null)
    {
        parent::__construct('#__eventcalendar_resources', 'id', $db, $dispatcher);

        $this->created = Factory::getDate()->toSql();
        $this->setColumnAlias('published', 'state');
    }

    /**
     * Overloaded check function.
     *
     * @return  boolean
     *
     * @see     Table::check
     * @since   0.1.0
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

        // Set created date if not set.
        if (!(int) $this->created) {
            $this->created = Factory::getDate()->toSql();
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
     * @since   0.1.0
     */
    public function store($updateNulls = true)
    {
        // Store the new row.
        parent::store($updateNulls);

        return \count($this->getErrors()) == 0;
    }
}
