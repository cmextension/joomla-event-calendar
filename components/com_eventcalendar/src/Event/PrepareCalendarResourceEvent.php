<?php

/**
 * @package     EventCalendar
 * @subpackage  com_eventcalendar
 * @copyright   Copyright (C) 2025 CMExension
 * @license     GNU General Public License version 2 or later
 */

namespace CMExtension\Component\EventCalendar\Site\Event;

use Joomla\CMS\Event\AbstractImmutableEvent;

\defined('_JEXEC') or die;

/**
 * Prepare calendar resource for sending it to client.
 *
 * @since  0.2.0
 */
class PrepareCalendarResourceEvent extends AbstractImmutableEvent
{
    /**
     * Constructor.
     *
     * @param   string  $name       The event name.
     * @param   array   $arguments  The event arguments.
     *
     * @throws  \BadMethodCallException
     *
     * @since   0.2.0
     */
    public function __construct($name, array $arguments = [])
    {
        parent::__construct($name, $arguments);

        if (!\array_key_exists('subject', $this->arguments)) {
            throw new \BadMethodCallException("Argument 'subject' of event {$name} is required but has not been provided");
        }
    }

    /**
     * Setter for the subject argument.
     *
     * @param   object  $value  The event object
     *
     * @return  object
     *
     * @since  0.2.0
     */
    protected function onSetSubject($value)
    {
        return $value;
    }

    /**
     * Getter for the resource.
     *
     * @return  object
     *
     * @since  0.2.0
     */
    public function getCalendarResource()
    {
        return $this->arguments['subject'];
    }
}
