<?php

/**
 * @package     EventCalendar
 * @subpackage  com_eventcalendar
 * @copyright   Copyright (C) 2025 CMExension
 * @license     GNU General Public License version 2 or later
 */

namespace CMExtension\Component\EventCalendar\Administrator\Field;

use Joomla\CMS\Form\FormField;

\defined('_JEXEC') or die;

/**
 * Field to show the values to setup calendar toolbar.
 *
 * @since  0.1.0
 */
class ToolbarhelpField extends FormField
{
    /**
     * The form field type.
     *
     * @var    string
     * @since  0.1.0
     */
    protected $type = 'Toolbarhelp';

    /**
     * Layout used to render.
     *
     * @var  string
     */
    protected $layout = 'com_eventcalendar.toolbarhelp';

    /**
     * Allow to override renderer include paths in child fields
     *
     * @return  array
     *
     * @since   3.5
     */
    protected function getLayoutPaths()
    {
        $paths = parent::getLayoutPaths();

        $paths[] = JPATH_ADMINISTRATOR . '/components/com_eventcalendar/layouts';

        return $paths;
    }
}
