<?php

/**
 * @package     EventCalendar
 * @subpackage  com_eventcalendar
 * @copyright   Copyright (C) 2025 CMExension
 * @license     GNU General Public License version 2 or later
 */

namespace CMExtension\Component\EventCalendar\Administrator\Helper;

use Joomla\CMS\Helper\ContentHelper;
use Joomla\CMS\Router\Route;

\defined('_JEXEC') or die;

/**
 * Functions for event.
 *
 * @since  0.0.2
 */
class EventHelper
{
    /**
     * Convert PHP object to vkurko/calendar's Event object.
     *
     * @param   object  $event  Event PHP object
     *
     * @return  object  PHP object but with's JavaScript object properties
     *
     * @since   0.0.2
     */
    public static function convertToEventJSObject($event)
    {
        $canDo = ContentHelper::getActions('com_eventcalendar');
        $canEdit = $canDo->get('core.edit');

        if ($event->link_type == 'menu_item') {
            $url = Route::_('index.php?Itemid=' . $event->menu_item_id);
        } elseif ($event->link_type == 'url') {
            $url = $event->url;
        } else {
            $url = null;
        }

        $extendedProps = new \stdClass;
        $extendedProps->url = $url;

        $newObj                     = new \stdClass;
        $newObj->id                 = $event->id;
        $newObj->resourceIds        = $event->resource_ids;
        $newObj->allDay             = $event->all_day;
        $newObj->start              = $event->start_time;
        $newObj->end                = $event->end_time;
        $newObj->title              = $event->name;
        $newObj->startEditable      = $canEdit;
        $newObj->durationEditable   = $canEdit;
        $newObj->backgroundColor    = $event->background_color;
        $newObj->textColor          = $event->text_color;
        $newObj->classNames         = $event->class_names;
        $newObj->styles             = $event->styles;
        $newObj->extendedProps      = $extendedProps;

        return $newObj;
    }
}
