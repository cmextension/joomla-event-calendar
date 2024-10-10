<?php

/**
 * @package     EventCalendar
 * @subpackage  com_eventcalendar
 * @copyright   Copyright (C) 2024 CMExension
 * @license     GNU General Public License version 2 or later
 */

namespace CMExtension\Component\EventCalendar\Administrator\Controller;

use CMExtension\Component\EventCalendar\Administrator\Helper\EventHelper;
use Joomla\CMS\Factory;
use Joomla\CMS\MVC\Controller\BaseController;
use Joomla\CMS\Response\JsonResponse;
use Joomla\CMS\Language\Text;
use Joomla\CMS\Session\Session;

\defined('_JEXEC') or die;

/**
 * Controller for AJAX requests.
 *
 * @since  0.0.2
 */
class AjaxController extends BaseController
{
    /**
     * Get published events, filtered by start time, end time and language.
     *
     * @return  void
     *
     * @since   0.0.2
     */
    public function getPublishedEvents()
    {
        if (!Session::checkToken('get')) {
            throw new \Exception(Text::_('JINVALID_TOKEN'), 403);
        }

        $startTime = $this->input->getString('start_time');
        $endTime = $this->input->getString('end_time');
        $language = $this->input->getString('language', Factory::getApplication()->getLanguage()->getTag());

        /** @var EventsModel $model */
        $model = $this->getModel('Events', 'Administrator');

        $events = $model->getPublishedEvents($startTime, $endTime, $language);
        $resources = [];

        if ($events) {
            foreach ($events as &$event) {
                $event = EventHelper::convertToEventJSObject($event);
                $resources[] = EventHelper::convertToResourceJSObject($event);
            }
        }

        echo new JsonResponse(['events' => $events, 'resources' => $resources]);
    }

    /**
     * Update start and end time of an event.
     *
     * @return  void
     *
     * @since   0.0.2
     */
    public function updateEventTime()
    {
        if (!Session::checkToken('get')) {
            throw new \Exception(Text::_('JINVALID_TOKEN'), 403);
        }

        $id = $this->input->getUint('id', 0);
        $startTime = $this->input->getString('start_time');
        $endTime = $this->input->getString('end_time');

        if (!$id) {
            throw new \Exception(Text::_('COM_EVENTCALENDAR_ERROR_EVENT_NOT_FOUND'), 404);
        }

        if (!$startTime) {
            throw new \Exception(Text::_('COM_EVENTCALENDAR_ERROR_START_TIME_REQUIRED'), 400);
        }

        if (!$endTime) {
            throw new \Exception(Text::_('COM_EVENTCALENDAR_ERROR_END_TIME_REQUIRED'), 400);
        }

        /** @var EventModel $model */
        $model = $this->getModel('Event', 'Administrator');

        $model->updateEventTime($id, $startTime, $endTime);

        echo new JsonResponse;
    }
}
