<?php

/**
 * @package     EventCalendar
 * @subpackage  com_eventcalendar
 * @copyright   Copyright (C) 2025 CMExension
 * @license     GNU General Public License version 2 or later
 */

namespace CMExtension\Component\EventCalendar\Administrator\Model;

use Joomla\CMS\MVC\Model\BaseDatabaseModel;
use Joomla\Database\ParameterType;

\defined('_JEXEC') or die;

/**
 * Model for event and resource's relationship.
 *
 * @since  0.1.0
 */
class EventResourceModel extends BaseDatabaseModel
{
    /**
     * Save relationship.
     *
     * @param   integer     $eventId        Event ID.
     * @param   integer     $resourceId     Resourde ID.
     *
     * @return  void
     *
     * @since   0.1.0
     */
    public function save($eventId, $resourceId)
    {
        $db = $this->getDatabase();
        $query = $db->getQuery(true)
            ->select('COUNT(' . $db->quoteName('id') . ')')
            ->from($db->quoteName('#__eventcalendar_event_resource'))
            ->where($db->quoteName('event_id') . ' = :event_id')
            ->where($db->quoteName('resource_id') . ' = :resource_id')
            ->bind(':event_id', $eventId, ParameterType::INTEGER)
            ->bind(':resource_id', $resourceId, ParameterType::INTEGER);

        $count = $db->setQuery($query)->loadResult();

        if ($count) {
            return;
        }

        $query->clear()
            ->insert($db->quoteName('#__eventcalendar_event_resource'))
            ->columns(
                [
                    $db->quoteName('event_id'),
                    $db->quoteName('resource_id'),
                ]
            )
            ->values(':event_id, :resource_id')
            ->bind(':event_id', $eventId, ParameterType::INTEGER)
            ->bind(':resource_id', $resourceId, ParameterType::INTEGER);

        $db->setQuery($query)->execute();
    }

    /**
     * Delete relationship.
     *
     * @param   integer     $eventId        Event ID.
     * @param   integer     $resourceId     Resourde ID.
     *
     * @return  void
     *
     * @since   0.1.0
     */
    public function delete($eventId, $resourceId)
    {
        $db = $this->getDatabase();
        $query = $db->getQuery(true)
            ->delete($db->quoteName('#__eventcalendar_event_resource'))
            ->where($db->quoteName('event_id') . ' = :event_id')
            ->where($db->quoteName('resource_id') . ' = :resource_id')
            ->bind(':event_id', $eventId, ParameterType::INTEGER)
            ->bind(':resource_id', $resourceId, ParameterType::INTEGER);

        $db->setQuery($query)->execute();
    }

    /**
     * Delete relationship by event ID.
     *
     * @param   integer     $eventId        Event ID.
     *
     * @return  void
     *
     * @since   0.1.0
     */
    public function deleteByEventId($eventId)
    {
        $db = $this->getDatabase();
        $query = $db->getQuery(true)
            ->delete($db->quoteName('#__eventcalendar_event_resource'))
            ->where($db->quoteName('event_id') . ' = :event_id')
            ->bind(':event_id', $eventId, ParameterType::INTEGER);

        $db->setQuery($query)->execute();
    }

    /**
     * Delete relationship by resource ID.
     *
     * @param   integer     $resourceId     Resourde ID.
     *
     * @return  void
     *
     * @since   0.1.0
     */
    public function deleteByResourceId($resourceId)
    {
        $db = $this->getDatabase();
        $query = $db->getQuery(true)
            ->delete($db->quoteName('#__eventcalendar_event_resource'))
            ->where($db->quoteName('resource_id') . ' = :resource_id')
            ->bind(':resource_id', $resourceId, ParameterType::INTEGER);

        $db->setQuery($query)->execute();
    }

    /**
     * Get event's resources.
     *
     * @param   integer     $eventId    Event ID.
     *
     * @return  void
     *
     * @since   0.1.0
     */
    public function getResourceIdsByEventId($eventId)
    {
        $db = $this->getDatabase();
        $query = $db->getQuery(true)
            ->select($db->quoteName('resource_id'))
            ->from($db->quoteName('#__eventcalendar_event_resource'))
            ->where($db->quoteName('event_id') . ' = :event_id')
            ->bind(':event_id', $eventId, ParameterType::INTEGER);

        return $db->setQuery($query)->loadColumn();
    }

    /**
     * Get resource's events
     *
     * @param   integer     $resourceId     Resource ID.
     *
     * @return  void
     *
     * @since   0.1.0
     */
    public function getEventIdsByResourceId($resourceId)
    {
        $db = $this->getDatabase();
        $query = $db->getQuery(true)
            ->select($db->quoteName('event_id'))
            ->from($db->quoteName('#__eventcalendar_event_resource'))
            ->where($db->quoteName('resource_id') . ' = :resource_id')
            ->bind(':resource_id', $resourceId, ParameterType::INTEGER);

        return $db->setQuery($query)->loadColumn();
    }
}
