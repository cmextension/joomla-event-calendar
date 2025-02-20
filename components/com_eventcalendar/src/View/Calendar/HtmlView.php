<?php

/**
 * @package     EventCalendar
 * @subpackage  com_eventcalendar
 * @copyright   Copyright (C) 2025 CMExtension
 * @license     GNU General Public License version 2 or later
 */

namespace CMExtension\Component\EventCalendar\Site\View\Calendar;

use Joomla\CMS\Factory;
use Joomla\CMS\Language\Text;
use Joomla\CMS\MVC\View\HtmlView as BaseHtmlView;

\defined('_JEXEC') or die;

/**
 * View for the main calendar.
 *
 * @since  0.0.2
 */
class HtmlView extends BaseHtmlView
{
    /**
     * The page parameters.
     *
     * @var    \Joomla\Registry\Registry|null
     *
     * @since  0.0.2
     */
    protected $params = null;

    /**
     * The page class suffix.
     *
     * @var    string
     *
     * @since  0.0.2
     */
    protected $pageclass_sfx = '';

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
     * @param   string  $tpl  The name of the template file to parse; automatically searches through the template paths.
     *
     * @return  void
     *
     * @since   0.0.2
     */
    public function display($tpl = null)
    {
        $this->app = Factory::getApplication();
        $this->params = $this->app->getParams();

        // Escape strings for HTML output.
        $this->pageclass_sfx = htmlspecialchars($this->params->get('pageclass_sfx', ''), ENT_COMPAT, 'UTF-8');

        $this->_prepareDocument();

        parent::display($tpl);
    }

    /**
     * Prepare the document.
     *
     * @return  void
     *
     * @since   0.0.2
     */
    protected function _prepareDocument()
    {
        // Because the application sets a default page title,
        // we need to get it from the menu item itself.
        $menu = $this->app->getMenu()->getActive();

        if ($menu) {
            $this->params->def('page_heading', $this->params->get('page_title', $menu->title));
        } else {
            $this->params->def('page_heading', Text::_('COM_EVENTCALENDAR_VIEW_CALENDAR_DEFAULT_PAGE_TITLE'));
        }

        $this->setDocumentTitle($this->params->get('page_title', ''));

        if ($this->params->get('menu-meta_description')) {
            $this->getDocument()->setDescription($this->params->get('menu-meta_description'));
        }

        if ($this->params->get('robots')) {
            $this->getDocument()->setMetaData('robots', $this->params->get('robots'));
        }
    }
}
