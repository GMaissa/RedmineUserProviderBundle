<?php
/**
 * File part of the Redmine User Provider bundle
 *
 * @category  SymfonyBundle
 * @package   GMaissa.RedmineUserProviderBundle
 * @author    Guillaume Maïssa <pro.g@maissa.fr>
 * @copyright 2017 Guillaume Maïssa
 * @license   http://opensource.org/licenses/osl-3.0.php  Open Software License (OSL 3.0)
 */

namespace GMaissa\RedmineUserProviderBundle\DependencyInjection;

use Symfony\Component\Config\Definition\Processor;
use Symfony\Component\Config\FileLocator;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\DependencyInjection\Loader\YamlFileLoader;
use Symfony\Component\HttpKernel\DependencyInjection\Extension;

/**
 * Infrastructure UserBundle Dependency Injection Class
 */
class GmRedmineUserProviderExtension extends Extension
{
    /**
     * {@inheritdoc}
     */
    public function load(array $configs, ContainerBuilder $container)
    {
        $processor     = new Processor();
        $configuration = new Configuration();
        $config        = $processor->processConfiguration($configuration, $configs);

        $this->registerParameters($container, $config);

        $this->loadServices($container, $config);
    }

    /**
     * Set parameters from bundle configuration
     *
     * @param ContainerBuilder $container
     * @param array            $config
     */
    private function registerParameters(ContainerBuilder $container, array $config)
    {
        $container->setParameter('gm_redmine_user_provider.redmine.url', $config['redmine']['url']);
        $container->setParameter(
            'gm_redmine_user_provider.redmine.allowed_domains',
            $config['redmine']['allowed_domains']
        );
        if (isset($config['user_class'])) {
            $reflection    = new \ReflectionClass($config['user_class']);
            $userInterface = '\Symfony\Component\Security\Core\User\UserInterface';
            if (!$reflection->implementsInterface($userInterface)) {
                throw new \InvalidArgumentException(
                    sprintf(
                        'The user class %s should implement interface "%s"',
                        $config['user_class'],
                        $userInterface
                    )
                );
            }

            $container->setParameter('gm_redmine_user_provider.user_class', $config['user_class']);
        }
    }

    /**
     * Load default and persistence services
     *
     * @param ContainerBuilder $container
     * @param array            $config
     */
    private function loadServices(ContainerBuilder $container, array $config)
    {
        $loader = new YamlFileLoader($container, new FileLocator(__DIR__ . '/../Resources/config'));

        // Load default services
        $loader->load('services.yml');

        // Load persistence services
        if (isset($config['persistence_driver']) && !is_null($config['persistence_driver'])) {
            $loader->load(sprintf('%s.yml', $config['persistence_driver']));
        }
    }
}
