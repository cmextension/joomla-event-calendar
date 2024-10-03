<?php

/**
 * @package     EventCalendar
 * @subpackage  com_eventcalendar
 * @copyright   Copyright (C) 2024 CMExension
 * @license     GNU General Public License version 2 or later
 */

namespace CMExtension\Component\EventCalendar\Administrator\View\Event;

use Joomla\CMS\Factory;
use Joomla\CMS\Form\Form;
use Joomla\CMS\Helper\ContentHelper;
use Joomla\CMS\Language\Text;
use Joomla\CMS\MVC\View\GenericDataException;
use Joomla\CMS\MVC\View\HtmlView as BaseHtmlView;
use Joomla\CMS\Toolbar\Toolbar;
use Joomla\CMS\Toolbar\ToolbarHelper;

\defined('_JEXEC') or die;

/**
 * View to edit an event.
 *
 * @since  0.0.2
 */
class HtmlView extends BaseHtmlView
{
    /**
     * The Form object
     *
     * @var    Form
     * @since  0.0.2
     */
    protected $form;

    /**
     * The active item
     *
     * @var    object
     * @since  0.0.2
     */
    protected $item;

    /**
     * The model state
     *
     * @var    object
     * @since  0.0.2
     */
    protected $state;

    /**
     * Display the view.
     *
     * @param   string  $tpl  The name of the template file to parse; automatically searches through the template paths.
     *
     * @return  void
     *
     * @since   0.0.2
     *
     * @throws  \Exception
     */
    public function display($tpl = null): void
    {
        /** @var EventModel $model */
        $model       = $this->getModel();
        $this->form  = $model->getForm();
        $this->item  = $model->getItem();
        $this->state = $model->getState();

        // Check for errors.
        if (\count($errors = $this->get('Errors'))) {
            throw new GenericDataException(implode("\n", $errors), 500);
        }

        if ($this->getLayout() !== 'modal') {
            $this->addToolbar();
        } else {
            $this->addModalToolbar();
        }

        parent::display($tpl);
    }

    /**
     * Add the page title and toolbar.
     *
     * @return  void
     *
     * @since   0.0.2
     * @throws  \Exception
     */
    protected function addToolbar(): void
    {
        Factory::getApplication()->getInput()->set('hidemainmenu', true);

        $user       = $this->getCurrentUser();
        $userId     = $user->id;
        $isNew      = ($this->item->id == 0);
        $checkedOut = !(\is_null($this->item->checked_out) || $this->item->checked_out == $userId);
        $toolbar    = Toolbar::getInstance();

        $canDo = ContentHelper::getActions('com_eventcalendar');

        ToolbarHelper::title($isNew ? Text::_('COM_EVENTCALENDAR_MANAGER_EVENT_NEW') : Text::_('COM_EVENTCALENDAR_MANAGER_EVENT_EDIT'), 'calendar');

        // If not checked out, can save the item.
        if (!$checkedOut && ($canDo->get('core.edit') || $canDo->get('core.create'))) {
            $toolbar->apply('event.apply');
        }

        $saveGroup = $toolbar->dropdownButton('save-group');

        $saveGroup->configure(
            function (Toolbar $childBar) use ($checkedOut, $canDo, $isNew) {
                // If not checked out, can save the item.
                if (!$checkedOut && ($canDo->get('core.edit') || $canDo->get('core.create'))) {
                    $childBar->save('event.save');

                    if ($canDo->get('core.create')) {
                        $childBar->save2new('event.save2new');
                    }
                }

                // If an existing item, can save to a copy.
                if (!$isNew && $canDo->get('core.create')) {
                    $childBar->save2copy('event.save2copy');
                }
            }
        );

        if (empty($this->item->id)) {
            $toolbar->cancel('event.cancel', 'JTOOLBAR_CANCEL');
        } else {
            $toolbar->cancel('event.cancel');
        }
    }

    /**
     * Add the modal toolbar.
     *
     * @return  void
     *
     * @since   0.0.2
     *
     * @throws  \Exception
     */
    protected function addModalToolbar()
    {
        $user       = $this->getCurrentUser();
        $userId     = $user->id;
        $isNew      = ($this->item->id == 0);
        $checkedOut = !(\is_null($this->item->checked_out) || $this->item->checked_out == $userId);
        $toolbar    = Toolbar::getInstance();

        // Build the actions for new and existing records.
        $canDo = ContentHelper::getActions('com_eventcalendar');

        ToolbarHelper::title($isNew ? Text::_('COM_EVENTCALENDAR_MANAGER_EVENT_NEW') : Text::_('COM_EVENTCALENDAR_MANAGER_EVENT_EDIT'), 'calendar');

        ToolbarHelper::title(
            Text::_('COM_EVENTCALENDAR_MANAGER_EVENT_' . ($checkedOut ? 'VIEW' : ($isNew ? 'NEW' : 'EDIT'))),
            'pencil-alt article-add'
        );

        $canCreate = $isNew && $canDo->get('core.create');
        $canEdit   = $canDo->get('core.edit') || ($canDo->get('core.edit.own') && $this->item->created_by == $userId);

        // For new records, check the create permission.
        if ($canCreate || $canEdit) {
            $toolbar->apply('event.apply');
            $toolbar->save('event.save');
        }

        $toolbar->cancel('event.cancel');
    }
}
