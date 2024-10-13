<?php

/**
 * @package     EventCalendar
 * @subpackage  com_eventcalendar
 * @copyright   Copyright (C) 2024 CMExension
 * @license     GNU General Public License version 2 or later
 */

namespace CMExtension\Component\EventCalendar\Administrator\Helper;

\defined('_JEXEC') or die;

/**
 * Functions for resource.
 *
 * @since  0.1.0
 */
class ResourceHelper
{
    /**
     * Convert PHP object to vkurko/calendar's Resource object.
     *
     * @param   object  $resource   Resource PHP object
     *
     * @return  object  PHP object but with's JavaScript object properties
     *
     * @since   0.1.0
     */
    public static function convertToResourceJSObject($resource)
    {
        $newObj                         = new \stdClass;
        $newObj->id                     = $resource->id;
        $newObj->title                  = $resource->name;
        $newObj->eventBackgroundColor   = $resource->backgroundColor;
        $newObj->eventTextColor         = $resource->textColor;

        return $newObj;
    }
}
