<?php

/**
 * @package     EventCalendar
 * @subpackage  com_eventcalendar
 * @copyright   Copyright (C) 2024 CMExension
 * @license     GNU General Public License version 2 or later
 */

namespace CMExtension\Component\EventCalendar\Administrator\Model;

use Joomla\CMS\Component\ComponentHelper;
use Joomla\CMS\MVC\Model\ListModel;
use Joomla\CMS\Table\Table;
use Joomla\Database\ParameterType;

\defined('_JEXEC') or die;

/**
 * Methods supporting a list of event records.
 *
 * @since  0.0.2
 */
class EventsModel extends ListModel
{
    /**
     * Constructor.
     *
     * @param   array  $config  An optional associative array of configuration settings.
     *
     * @since   0.0.2
     */
    public function __construct($config = [])
    {
        if (empty($config['filter_fields'])) {
            $config['filter_fields'] = [
                'id',
                'a.id',
                'name',
                'a.name',
                'name',
                'a.name',
                'start_time',
                'a.start_time',
                'end_time',
                'a.end_time',
                'all_day',
                'a.all_day',
                'state',
                'a.state',
                'language',
                'a.language',
                'checked_out',
                'a.checked_out',
                'checked_out_time',
                'a.checked_out_time',
                'created',
                'a.created',
            ];
        }

        parent::__construct($config);
    }

    /**
     * Build an SQL query to load the list data.
     *
     * @return  \Joomla\Database\DatabaseQuery
     *
     * @since   0.0.2
     */
    protected function getListQuery()
    {
        $db    = $this->getDatabase();
        $query = $db->getQuery(true);

        $query->select(
            $this->getState(
                'list.select',
                [
                    $db->quoteName('a.id'),
                    $db->quoteName('a.name'),
                    $db->quoteName('a.start_time'),
                    $db->quoteName('a.end_time'),
                    $db->quoteName('a.all_day'),
                    $db->quoteName('a.state'),
                    $db->quoteName('a.language'),
                ]
            )
        )
            ->select(
                [
                    $db->quoteName('l.title', 'language_title'),
                    $db->quoteName('uc.name', 'editor'),
                ]
            )
            ->from($db->quoteName('#__eventcalendar_events', 'a'))
            ->join('LEFT', $db->quoteName('#__languages', 'l'), $db->quoteName('l.lang_code') . ' = ' . $db->quoteName('a.language'))
            ->join('LEFT', $db->quoteName('#__users', 'uc'), $db->quoteName('uc.id') . ' = ' . $db->quoteName('a.checked_out'));

        // Filter by published state.
        $published = (string) $this->getState('filter.published');

        if (is_numeric($published)) {
            $published = (int) $published;
            $query->where($db->quoteName('a.state') . ' = :published')
                ->bind(':published', $published, ParameterType::INTEGER);
        } elseif ($published === '') {
            $query->where($db->quoteName('a.state') . ' IN (0, 1)');
        }

        // Filter by search in event name.
        if ($search = $this->getState('filter.search')) {
            if (stripos($search, 'id:') === 0) {
                $search = (int) substr($search, 3);
                $query->where($db->quoteName('a.id') . ' = :search')
                    ->bind(':search', $search, ParameterType::INTEGER);
            } else {
                $search = '%' . str_replace(' ', '%', trim($search)) . '%';
                $query->where($db->quoteName('a.name') . ' LIKE :search')
                    ->bind([':search', ':search'], $search);
            }
        }

        // Filter on the language.
        if ($language = $this->getState('filter.language')) {
            $query->where($db->quoteName('a.language') . ' = :language')
                ->bind(':language', $language);
        }

        // Filter by all-day state.
        $allDay = (string) $this->getState('filter.all_day');

        if (is_numeric($allDay)) {
            $allDay = (int) $allDay;
            $query->where($db->quoteName('a.all_day') . ' = :all_day')
                ->bind(':all_day', $allDay, ParameterType::INTEGER);
        } elseif ($allDay === '') {
            $query->where($db->quoteName('a.all_day') . ' IN (0, 1)');
        }

        // Add the list ordering clause.
        $orderCol  = $this->state->get('list.ordering', 'a.start_time');
        $orderDirn = $this->state->get('list.direction', 'DESC');

        $ordering = $db->escape($orderCol) . ' ' . $db->escape($orderDirn);

        $query->order($ordering);

        return $query;
    }

    /**
     * Method to get a store id based on model configuration state.
     *
     * This is necessary because the model is used by the component and
     * different modules that might need different sets of data or different
     * ordering requirements.
     *
     * @param   string  $id  A prefix for the store id.
     *
     * @return  string  A store id.
     *
     * @since   0.0.2
     */
    protected function getStoreId($id = '')
    {
        // Compile the store id.
        $id .= ':' . $this->getState('filter.search');
        $id .= ':' . $this->getState('filter.all_day');
        $id .= ':' . $this->getState('filter.start_time');
        $id .= ':' . $this->getState('filter.end_time');
        $id .= ':' . $this->getState('filter.published');
        $id .= ':' . $this->getState('filter.language');

        return parent::getStoreId($id);
    }

    /**
     * Returns a reference to the a Table object, always creating it.
     *
     * @param   string  $type    The table type to instantiate
     * @param   string  $prefix  A prefix for the table class name. Optional.
     * @param   array   $config  Configuration array for model. Optional.
     *
     * @return  Table  A Table object
     *
     * @since   0.0.2
     */
    public function getTable($type = 'Event', $prefix = 'Administrator', $config = [])
    {
        return parent::getTable($type, $prefix, $config);
    }

    /**
     * Method to auto-populate the model state.
     *
     * Note. Calling getState in this method will result in recursion.
     *
     * @param   string  $ordering   An optional ordering field.
     * @param   string  $direction  An optional direction (asc|desc).
     *
     * @return  void
     *
     * @since   0.0.2
     */
    protected function populateState($ordering = 'a.start_time', $direction = 'desc')
    {
        // Load the parameters.
        $this->setState('params', ComponentHelper::getParams('com_eventcalendar'));

        // List state information.
        parent::populateState($ordering, $direction);
    }

    /**
     * Get published events based on start time, end time and language. Used in AJAX requests.
     *
     * @param   string  $startTime
     * @param   string  $endTime
     * @param   string  $language
     *
     * @return  array
     *
     * @since   0.0.2
     */
    public function getPublishedEvents($startTime, $endTime, $language)
    {
        $db = $this->getDatabase();
        $query = $db->getQuery(true)
            ->select($db->quoteName([
                'id',
                'name',
                'start_time',
                'end_time',
                'all_day',
                'background_color',
                'text_color',
                'class_names',
                'styles',
                'language',
                'state'
            ]))
            ->from($db->quoteName('#__eventcalendar_events'));

        if ($startTime) {
            $query->where($db->quoteName('start_time') . ' >= ' . $db->quote($startTime));
        }

        if ($endTime) {
            $query->where($db->quoteName('end_time') . ' >= ' . $db->quote($endTime));
        }

        if ($language) {
            $query->where($db->quoteName('language') . ' = ' . $db->quote($language));
        }

        return $db->setQuery($query)->loadObjectList();
    }
}
