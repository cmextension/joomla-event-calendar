<?php

/**
 * @package     EventCalendar
 * @subpackage  com_eventcalendar
 * @copyright   Copyright (C) 2024 CMExension
 * @license     GNU General Public License version 2 or later
 */

namespace CMExtension\Component\EventCalendar\Administrator\View\Resources;

use Joomla\CMS\Form\Form;
use Joomla\CMS\Language\Text;
use Joomla\CMS\MVC\View\GenericDataException;
use Joomla\CMS\MVC\View\HtmlView as BaseHtmlView;
use Joomla\CMS\Pagination\Pagination;
use Joomla\CMS\Toolbar\Toolbar;
use Joomla\CMS\Toolbar\ToolbarHelper;
use Joomla\CMS\Helper\ContentHelper;

\defined('_JEXEC') or die;
// phpcs:enable .Files.SideEffects

/**
 * View class for a list of resources.
 *
 * @since  0.1.0
 */
class HtmlView extends BaseHtmlView
{
    /**
     * An array of items.
     *
     * @var    array
     * @since  0.1.0
     */
    protected $items;

    /**
     * The model state.
     *
     * @var    Registry
     * @since  0.1.0
     */
    protected $state;

    /**
     * The pagination object.
     *
     * @var    Pagination
     * @since  0.1.0
     */
    protected $pagination;

    /**
     * Form object for search filters.
     *
     * @var    Form
     * @since  0.1.0
     */
    public $filterForm;

    /**
     * The active search filters.
     *
     * @var    array
     * @since  3.9.0
     */
    public $activeFilters;

    /**
     * Is this view an Empty State
     *
     * @var  boolean
     * @since 4.0.0
     */
    private $isEmptyState = false;

    /**
     * Method to display the view.
     *
     * @param   string  $tpl  A template file to load. [optional]
     *
     * @return  void
     *
     * @since   0.1.0
     *
     * @throws  \Exception
     */
    public function display($tpl = null)
    {
        /** @var ResourcesModel $model */
        $model               = $this->getModel();
        $this->items         = $model->getItems();
        $this->state         = $model->getState();
        $this->pagination    = $model->getPagination();
        $this->filterForm    = $model->getFilterForm();
        $this->activeFilters = $model->getActiveFilters();

        if ($this->getLayout() != 'modalreturn' && !\count($this->items) && $this->isEmptyState = $this->get('IsEmptyState')) {
            $this->setLayout('emptystate');
        }

        if (\count($errors = $model->getErrors())) {
            throw new GenericDataException(implode("\n", $errors), 500);
        }

        if ($this->getLayout() !== 'modalreturn') {
            $this->addToolbar();
        }

        parent::display($tpl);
    }

    /**
     * Add the page title and toolbar.
     *
     * @return  void
     *
     * @since   0.1.0
     */
    protected function addToolbar()
    {
        $canDo   = ContentHelper::getActions('com_eventcalendar');
        $user    = $this->getCurrentUser();
        $toolbar = Toolbar::getInstance();

        ToolbarHelper::title(Text::_('COM_EVENTCALENDAR_MANAGER_RESOURCES'), 'icon-file-alt');

        if ($canDo->get('core.create') || \count($user->getAuthorisedCategories('com_eventcalendar', 'core.create')) > 0) {
            $toolbar->addNew('resource.add');
        }

        if (!$this->isEmptyState && ($canDo->get('core.edit.state') || ($this->state->get('filter.published') == -2 && $canDo->get('core.delete')))) {
            /** @var  DropdownButton $dropdown */
            $dropdown = $toolbar->dropdownButton('status-group', 'JTOOLBAR_CHANGE_STATUS')
                ->toggleSplit(false)
                ->icon('icon-ellipsis-h')
                ->buttonClass('btn btn-action')
                ->listCheck(true);

            $childBar = $dropdown->getChildToolbar();

            if ($canDo->get('core.edit.state')) {
                if ($this->state->get('filter.published') != 2) {
                    $childBar->publish('resources.publish')->listCheck(true);

                    $childBar->unpublish('resources.unpublish')->listCheck(true);
                }

                if ($this->state->get('filter.published') != -1) {
                    if ($this->state->get('filter.published') != 2) {
                        $childBar->archive('resources.archive')->listCheck(true);
                    } elseif ($this->state->get('filter.published') == 2) {
                        $childBar->publish('publish')->task('resources.publish')->listCheck(true);
                    }
                }

                $childBar->checkin('resources.checkin');

                if ($this->state->get('filter.published') != -2) {
                    $childBar->trash('resources.trash')->listCheck(true);
                }
            }

            if ($this->state->get('filter.published') == -2 && $canDo->get('core.delete')) {
                $toolbar->delete('resources.delete', 'JTOOLBAR_EMPTY_TRASH')
                    ->message('JGLOBAL_CONFIRM_DELETE')
                    ->listCheck(true);
            }

            if (
                $user->authorise('core.create', 'com_eventcalendar')
                && $user->authorise('core.edit', 'com_eventcalendar')
                && $user->authorise('core.edit.state', 'com_eventcalendar')
            ) {
                $childBar->popupButton('batch', 'JTOOLBAR_BATCH')
                    ->popupType('inline')
                    ->textHeader(Text::_('COM_EVENTCALENDAR_RESOURCE_BATCH_OPTIONS'))
                    ->url('#joomla-dialog-batch')
                    ->modalWidth('800px')
                    ->modalHeight('fit-content')
                    ->listCheck(true);
            }
        }

        if ($user->authorise('core.admin', 'com_eventcalendar') || $user->authorise('core.options', 'com_eventcalendar')) {
            $toolbar->preferences('com_eventcalendar');
        }
    }
}
