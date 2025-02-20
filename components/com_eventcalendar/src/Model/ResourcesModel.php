<?php

/**
 * @package     EventCalendar
 * @subpackage  com_eventcalendar
 * @copyright   Copyright (C) 2025 CMExtension
 * @license     GNU General Public License version 2 or later
 */

namespace CMExtension\Component\EventCalendar\Site\Model;

use Joomla\CMS\MVC\Model\BaseDatabaseModel;

\defined('_JEXEC') or die;

/**
 * Methods supporting a list of resource records.
 *
 * @since  0.1.0
 */
class ResourcesModel extends BaseDatabaseModel
{
    /**
     * Get published resources. Used in AJAX requests.
     *
     * @param   string  $language
     *
     * @return  array
     *
     * @since   0.1.0
     */
    public function getPublishedResources($language)
    {
        $db = $this->getDatabase();
        $query = $db->getQuery(true)
            ->select($db->quoteName([
                'id',
                'name',
                'event_background_color',
                'event_text_color',
                'language',
                'state'
            ]))
            ->from($db->quoteName('#__eventcalendar_resources'))
            ->where($db->quoteName('state') . ' = ' . $db->quote(1));

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
