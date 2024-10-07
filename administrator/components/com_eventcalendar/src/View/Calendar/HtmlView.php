<?php

/**
 * @package     EventCalendar
 * @subpackage  com_eventcalendar
 * @copyright   Copyright (C) 2024 CMExension
 * @license     GNU General Public License version 2 or later
 */

namespace CMExtension\Component\EventCalendar\Administrator\View\Calendar;

use Joomla\CMS\Factory;
use Joomla\CMS\Language\Text;
use Joomla\CMS\MVC\View\HtmlView as BaseHtmlView;
use Joomla\CMS\Toolbar\Toolbar;
use Joomla\CMS\Toolbar\ToolbarHelper;

\defined('_JEXEC') or die;

/**
 * View class for the main calendar.
 *
 * @since  0.0.1
 */
class HtmlView extends BaseHtmlView
{
    /**
     * The global application.
     *
     * @var     CMSApplicationInterface
     *
     * @since 0.0.2
     */
    protected $app = null;

    /**
     * Method to display the view.
     *
     * @param   string  $tpl  A template file to load. [optional]
     *
     * @return  void
     *
     * @since   0.0.1
     *
     * @throws  \Exception
     */
    public function display($tpl = null)
    {
        $this->app = Factory::getApplication();

        $this->addToolbar();

        parent::display($tpl);
    }

    /**
     * Add the page title and toolbar.
     *
     * @return  void
     *
     * @since   3.9.0
     */
    protected function addToolbar()
    {
        ToolbarHelper::title(Text::_('COM_EVENTCALENDAR_MANAGER_CALENDAR'), 'icon-calendar');
        $toolbar = Toolbar::getInstance();
        $toolbar->preferences('com_eventcalendar');
    }
}
