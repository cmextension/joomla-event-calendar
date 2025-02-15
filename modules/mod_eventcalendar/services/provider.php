<?php

/**
 * @package     EventCalendar
 * @subpackage  mod_eventcalendar
 * @copyright   Copyright (C) 2025 CMExension
 * @license     GNU General Public License version 2 or later
 */

// phpcs:disable PSR1.Files.SideEffects
\defined('_JEXEC') or die;
// phpcs:enable PSR1.Files.SideEffects

use Joomla\CMS\Extension\Service\Provider\Module;
use Joomla\CMS\Extension\Service\Provider\ModuleDispatcherFactory;
use Joomla\DI\Container;
use Joomla\DI\ServiceProviderInterface;

/**
 * The module Event Calendar service provider.
 *
 * @since  0.3.0
 */
return new class() implements ServiceProviderInterface {
    /**
     * Registers the service provider with a DI container.
     *
     * @param   Container  $container  The DI container.
     *
     * @return  void
     *
     * @since   0.3.0
     */
    public function register(Container $container): void
    {
        $container->registerServiceProvider(new ModuleDispatcherFactory('\\CMExtension\\Module\\EventCalendar'));
        $container->registerServiceProvider(new Module());
    }
};
