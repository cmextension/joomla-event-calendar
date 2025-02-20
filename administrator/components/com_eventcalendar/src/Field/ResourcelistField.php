<?php

/**
 * @package     EventCalendar
 * @subpackage  com_eventcalendar
 * @copyright   Copyright (C) 2025 CMExtension
 * @license     GNU General Public License version 2 or later
 */

namespace CMExtension\Component\EventCalendar\Administrator\Field;

use Joomla\CMS\Form\Field\ListField;

\defined('_JEXEC') or die;

/**
 * Field for selecting resources.
 *
 * @since  0.1.0
 */
class ResourcelistField extends ListField
{
    /**
     * The form field type.
     *
     * @var    string
     * @since  0.1.0
     */
    protected $type = 'Resourcelist';

    /**
     * Method to get the options to populate list
     *
     * @return  array  The field option objects.
     *
     * @since   0.1.0
     */
    public function getOptions()
    {
        $db = $this->getDatabase();
        $query = $db->getQuery(true)
            ->select($db->quoteName('id') . ' AS value')
            ->select($db->quoteName('name') . ' AS text')
            ->from($db->quoteName('#__eventcalendar_resources'))
            ->order($db->quoteName('name') . ' ASC');

        $options = $db->setQuery($query)->loadAssocList();

        return array_merge(parent::getOptions(), $options);
    }
}
