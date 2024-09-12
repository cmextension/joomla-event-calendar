<?php

/**
 * @package     EventCalendar
 * @subpackage  com_eventcalendar
 * @copyright   Copyright (C) 2024 CMExension
 * @license     GNU General Public License version 2 or later
 */

\defined('_JEXEC') or die;

use Joomla\CMS\Dispatcher\ComponentDispatcherFactoryInterface;
use Joomla\CMS\Extension\ComponentInterface;
use Joomla\CMS\Extension\MVCComponent;
use Joomla\CMS\Extension\Service\Provider\ComponentDispatcherFactory;
use Joomla\CMS\Extension\Service\Provider\MVCFactory;
use Joomla\CMS\MVC\Factory\MVCFactoryInterface;
use Joomla\DI\Container;
use Joomla\DI\ServiceProviderInterface;

/**
 * The service provider for com_eventcalendar.
 *
 * @since  0.0.1
 */
return new class() implements ServiceProviderInterface {
    /**
     * Registers the service provider with a DI container.
     *
     * @param   Container  $container  The DI container.
     *
     * @return  void
     *
     * @since   0.0.1
     */
    public function register(Container $container)
    {
        $container->registerServiceProvider(new MVCFactory('\\CMExtension\\Component\\EventCalendar'));
        $container->registerServiceProvider(new ComponentDispatcherFactory('\\CMExtension\\Component\\EventCalendar'));

        $container->set(
            ComponentInterface::class,
            function (Container $container) {
                $component = new MVCComponent($container->get(ComponentDispatcherFactoryInterface::class));

                $component->setMVCFactory($container->get(MVCFactoryInterface::class));

                return $component;
            }
        );
    }
};
