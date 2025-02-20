<?php

/**
 * @package     EventCalendar
 * @subpackage  plg_eventcalendar_sample
 * @copyright   Copyright (C) 2025 CMExtension
 * @license     GNU General Public License version 2 or later
 */

namespace CMExtension\Plugin\EventCalendar\Sample\Extension;

use CMExtension\Component\EventCalendar\Site\Event\PrepareCalendarEventEvent;
use CMExtension\Component\EventCalendar\Site\Event\PrepareCalendarResourceEvent;
use Joomla\CMS\Form\Form;
use Joomla\CMS\Plugin\CMSPlugin;
use Joomla\CMS\Event\Model\PrepareFormEvent;
use Joomla\Event\SubscriberInterface;

\defined('_JEXEC') or die;

/**
 * A sample plugin to demonstrate com_eventcalendar's event hook.
 *
 * @since  0.0.1
 */
final class Sample extends CMSPlugin implements SubscriberInterface
{
    /**
     * Affects constructor behavior. If true, language files will be loaded automatically.
     *
     * @var    boolean
     * @since  0.0.1
     */
    protected $autoloadLanguage = true;

    /**
     * Returns an array of events this subscriber will listen to.
     *
     * @return  array
     *
     * @since   0.0.1
     */
    public static function getSubscribedEvents(): array
    {
        return [
            'onContentPrepareForm'              => 'prepareForm',
            'onEventCalendarPrepareEvent'       => 'prepareEvent',
            'onEventCalendarPrepareResource'    => 'prepareResource',
        ];
    }

    /**
     * The form event.
     *
     * @param   PrepareFormEvent    $event
     *
     * @return  void
     *
     * @since   0.0.1
     */
    public function prepareForm(PrepareFormEvent $event)
    {
        /** @var Form $form */
        $form = $event->getForm();

        /** @var array|object $data */
        // $data = $event->getData();

        $context = $form->getName();

        if ($context != 'com_eventcalendar.event') {
            return;
        }

        $_form = new \SimpleXMLElement('<form />');
        $fields = $_form->addChild('fields');
        $fields->addAttribute('name', 'sample');
        $fieldset = $fields->addChild('fieldset');
        $fieldset->addAttribute('name', 'sample');
        $fieldset->addAttribute('label', 'Sample');

        $field = $fieldset->addChild('field');
        $field->addAttribute('name', 'sample');
        $field->addAttribute('type', 'text');
        $field->addAttribute('language', 'en_GB');
        $field->addAttribute('label', 'Sample');

        $form->load($_form, false);
    }

    /**
     * Modify the event.
     *
     * @param   PrepareCalendarEventEvent   $event
     *
     * @return  void
     *
     * @since   0.0.2
     */
    public function prepareEvent(PrepareCalendarEventEvent $event)
    {
        $calendarEvent = $event->getCalendarEvent();
        $extendedProps = $calendarEvent->extendedProps ?? new \stdClass;
        $extendedProps->sample = 'Sample';

        $calendarEvent->extendedProps = $extendedProps;
    }

    /**
     * Modify the resource.
     *
     * @param   PrepareCalendarResourceEvent   $event
     *
     * @return  void
     *
     * @since   0.0.2
     */
    public function prepareResource(PrepareCalendarResourceEvent $event)
    {
        $calendarResource = $event->getCalendarResource();
        $extendedProps = $calendarResource->extendedProps ?? new \stdClass;
        $extendedProps->sample = 'Sample';

        $calendarResource->extendedProps = $extendedProps;
    }
}
