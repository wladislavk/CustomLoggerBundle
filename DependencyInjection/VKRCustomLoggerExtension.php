<?php
namespace VKR\CustomLoggerBundle\DependencyInjection;

use Symfony\Component\Config\FileLocator;
use Symfony\Component\DependencyInjection\Loader\YamlFileLoader;
use Symfony\Component\HttpKernel\DependencyInjection\Extension;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\Yaml\Yaml;
use VKR\CustomLoggerBundle\Tests\Command\CreateLogsCommandTest;

class VKRCustomLoggerExtension extends Extension
{
    public function load(array $configs, ContainerBuilder $container)
    {
        $loader = new YamlFileLoader(
            $container,
            new FileLocator(__DIR__.'/../Resources/config')
        );
        $loader->load('services.yml');
        $configuration = new Configuration();
        $processedConfiguration = $this->processConfiguration($configuration, $configs);
        $container->setParameter('vkr_custom_logger.manifest', $processedConfiguration['manifest']);
        $container->setParameter('vkr_custom_logger.logs_dir', $container->getParameter('kernel.logs_dir'));
        if ($container->getParameter('kernel.environment') == 'test') {
            $parameters = Yaml::parse(CreateLogsCommandTest::LOG_DIR . '/parameters.yml');
            $container->setParameter('vkr_custom_logger.manifest', $parameters['vkr_custom_logger.manifest']);
            $container->setParameter('vkr_custom_logger.logs_dir', CreateLogsCommandTest::LOG_DIR);
        }
    }
}
