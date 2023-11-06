<?php
\defined('_JEXEC') or die;

use Joomla\CMS\Extension\PluginInterface;

use Joomla\CMS\Factory;
use Joomla\CMS\Plugin\PluginHelper;
#use Joomla\CMS\User\UserFactoryInterface;
#use Joomla\Database\DatabaseInterface;
use Joomla\DI\Container;
use Joomla\DI\ServiceProviderInterface;
use Joomla\Event\DispatcherInterface;
use GHSVS\Plugin\System\SlScrolltotopGhsvs\Extension\SlScrolltotopGhsvs;
#use GHSVS\Plugin\System\OnUserGhsvs\Helper\OnUserGhsvsHelper;

return new class () implements ServiceProviderInterface
{
	public function register(Container $container): void
	{
		$container->set(
			PluginInterface::class,
			function (Container $container)
			{
				$dispatcher = $container->get(DispatcherInterface::class);
				$plugin = new SlScrolltotopGhsvs(
					$dispatcher,
					(array) PluginHelper::getPlugin('system', 'slscrolltotopghsvs'),
				);
				$plugin->setApplication(Factory::getApplication());

				return $plugin;
			}
		);
	}
};
