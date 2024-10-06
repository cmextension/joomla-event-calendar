<?php

/**
 * @package     EventCalendar
 * @subpackage  com_eventcalendar
 * @copyright   Copyright (C) 2024 CMExension
 * @license     GNU General Public License version 2 or later
 */

namespace CMExtension\Component\EventCalendar\Site\Controller;

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
        $model = $this->getModel('Events', 'Site');

        $events = $model->getPublishedEvents($startTime, $endTime, $language);

        if ($events) {
            foreach ($events as &$event) {
                $event = EventHelper::convertToJSObject($event);
            }
        }

        echo new JsonResponse($events);
    }
}