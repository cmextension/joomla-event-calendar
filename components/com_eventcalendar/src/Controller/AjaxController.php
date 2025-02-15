<?php

/**
 * @package     EventCalendar
 * @subpackage  com_eventcalendar
 * @copyright   Copyright (C) 2025 CMExension
 * @license     GNU General Public License version 2 or later
 */

namespace CMExtension\Component\EventCalendar\Site\Controller;

use CMExtension\Component\EventCalendar\Administrator\Helper\EventHelper;
use CMExtension\Component\EventCalendar\Administrator\Helper\ResourceHelper;
use CMExtension\Component\EventCalendar\Site\Event\PrepareCalendarEventEvent;
use CMExtension\Component\EventCalendar\Site\Event\PrepareCalendarResourceEvent;
use Joomla\CMS\Factory;
use Joomla\CMS\MVC\Controller\BaseController;
use Joomla\CMS\Response\JsonResponse;
use Joomla\CMS\Language\Text;
use Joomla\CMS\Plugin\PluginHelper;
use Joomla\CMS\Session\Session;
use Joomla\Event\DispatcherInterface;

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

        $dispatcher = Factory::getContainer()->get(DispatcherInterface::class);

        PluginHelper::importPlugin('eventcalendar', null, true, $dispatcher);

        /** @var EventsModel $eventsModel */
        $eventsModel = $this->getModel('Events', 'Site');

        $events = $eventsModel->getPublishedEvents($startTime, $endTime, $language);

        if ($events) {
            foreach ($events as &$event) {
                $event = EventHelper::convertToEventJSObject($event);

                $dispatcher->dispatch(
                    'onEventCalendarPrepareEvent',
                    new PrepareCalendarEventEvent('onEventCalendarPrepareEvent', ['subject' => $event])
                );
            }
        }

        /** @var ResourcesModel $resourcesModel */
        $resourcesModel = $this->getModel('Resources', 'Site');

        $resources = $resourcesModel->getPublishedResources($language);

        if ($resources) {
            foreach ($resources as &$resource) {
                $resource = ResourceHelper::convertToResourceJSObject($resource);

                $dispatcher->dispatch(
                    'onEventCalendarPrepareResource',
                    new PrepareCalendarResourceEvent('onEventCalendarPrepareResource', ['subject' => $resource])
                );
            }
        }

        echo new JsonResponse(['events' => $events, 'resources' => $resources]);
    }
}
