<?php

/**
 * @package     EventCalendar
 * @subpackage  com_eventcalendar
 * @copyright   Copyright (C) 2024 CMExension
 * @license     GNU General Public License version 2 or later
 */

namespace CMExtension\Component\EventCalendar\Site\Model;

use Joomla\CMS\MVC\Model\BaseDatabaseModel;

\defined('_JEXEC') or die;

/**
 * Methods supporting a list of event records.
 *
 * @since  0.0.2
 */
class EventsModel extends BaseDatabaseModel
{
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
            $query->where($db->quoteName('start_time') . ' >= :startTime')
                ->bind(':startTime', $startTime);
        }

        if ($endTime) {
            $query->where($db->quoteName('end_time') . ' <= :endTime')
                ->bind(':endTime', $endTime);
        }

        if ($language) {
            $allLanguages = '*';

            $query->where('(' .
                $db->quoteName('language') . ' = :language1 OR ' .
                $db->quoteName('language') . ' = :language2' .
                ')')
                ->bind(':language1', $language)
                ->bind(':language2', $allLanguages);
        }

        return $db->setQuery($query)->loadObjectList();
    }
}
