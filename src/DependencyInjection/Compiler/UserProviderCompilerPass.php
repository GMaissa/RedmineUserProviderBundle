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

namespace GMaissa\RedmineUserProviderBundle\DependencyInjection\Compiler;

use Symfony\Component\DependencyInjection\Compiler\CompilerPassInterface;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\DependencyInjection\Reference;

/**
 * User provider compiler pass
 *
 * Inject User repository in user provider if declared
 */
class UserProviderCompilerPass implements CompilerPassInterface
{
    /**
     * {@inheritdoc}
     */
    public function process(ContainerBuilder $container)
    {
        $defaultDependencies = [
            [
                'serviceId'        => 'redmine_user_provider.api.client',
                'defaultServiceId' => 'redmine_user_provider.api.client.default'
            ],
            [
                'serviceId'        => 'redmine_user_provider.factory.user',
                'defaultServiceId' => 'redmine_user_provider.factory.user.default'
            ]
        ];
        foreach ($defaultDependencies as $dependency) {
            if (!$container->has($dependency['serviceId'])) {
                $container->setAlias($dependency['serviceId'], $dependency['defaultServiceId']);
            }
        }

        if ($container->hasParameter('gm_redmine_user_provider.user_repository_service')) {
            $serviceId            = $container->getParameter('gm_redmine_user_provider.user_repository_service');
            $repositoryDefinition = $container->getDefinition($serviceId);
            $className            = $container->getParameterBag()->resolveValue($repositoryDefinition->getClass());
            $reflection           = new \ReflectionClass($className);
            $repositoryInterface  = 'GMaissa\RedmineUserProviderBundle\Repository\UserRepositoryInterface';
            if (!$reflection->implementsInterface($repositoryInterface)) {
                throw new \InvalidArgumentException(
                    sprintf(
                        'The user repository %s should implement interface "%s"',
                        $serviceId,
                        'GMaissa\RedmineUserProviderBundle\Repository\UserRepositoryInterface'
                    )
                );
            }

            $definition = $container->getDefinition('redmine_user_provider.provider');
            $definition->addMethodCall('setUserRepository', array(new Reference($serviceId)));
            $container->getParameterBag()->remove('gm_redmine_user_provider.user_repository_service');
        }
    }
}
