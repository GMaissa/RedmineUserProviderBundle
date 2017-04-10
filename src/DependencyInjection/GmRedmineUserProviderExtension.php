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
use Symfony\Component\Config\Resource\FileResource;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\DependencyInjection\Extension\PrependExtensionInterface;
use Symfony\Component\DependencyInjection\Loader\YamlFileLoader;
use Symfony\Component\HttpKernel\DependencyInjection\Extension;
use Symfony\Component\Yaml\Yaml;

/**
 * Infrastructure UserBundle Dependency Injection Class
 */
class GmRedmineUserProviderExtension extends Extension implements PrependExtensionInterface
{
    /**
     * {@inheritdoc}
     */
    public function load(array $configs, ContainerBuilder $container)
    {
        $processor     = new Processor();
        $configuration = new Configuration();
        $config        = $processor->processConfiguration($configuration, $configs);
        $container->setParameter('gm_redmine_user_provider.redmine.url', $config['redmine']['url']);
        $container->setParameter(
            'gm_redmine_user_provider.redmine.allowed_domains',
            $config['redmine']['allowed_domains']
        );
        $container->setParameter('gm_redmine_user_provider.user_class', $config['user_class']);
        if (isset($config['user_repository_service'])) {
            $container->setParameter(
                'gm_redmine_user_provider.user_repository_service',
                $config['user_repository_service']
            );
        }

        $loader = new YamlFileLoader($container, new FileLocator(__DIR__ . '/../Resources/config'));
        $loader->load('services.yml');
    }

    /**
     * {@inheritdoc}
     */
    public function prepend(ContainerBuilder $container)
    {
        $persistenceConfigFile = __DIR__ . '/../Resources/config/persistence.yml';
        $config                = Yaml::parse(file_get_contents($persistenceConfigFile));
        $container->prependExtensionConfig('doctrine', $config);
        $container->addResource(new FileResource($persistenceConfigFile));
    }
}
