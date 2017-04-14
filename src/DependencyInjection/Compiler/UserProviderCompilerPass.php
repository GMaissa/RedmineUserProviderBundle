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
                'serviceId'        => 'gm_redmine_user_provider.api.client',
                'defaultServiceId' => 'gm_redmine_user_provider.api.client.default'
            ],
            [
                'serviceId'        => 'gm_redmine_user_provider.factory.user',
                'defaultServiceId' => 'gm_redmine_user_provider.factory.user.default'
            ]
        ];
        foreach ($defaultDependencies as $dependency) {
            if (!$container->has($dependency['serviceId'])) {
                $container->setAlias($dependency['serviceId'], $dependency['defaultServiceId']);
            }
        }

        $this->processRepository($container);
    }

    /**
     * Look for configured user repository, to inject it into user provider service
     *
     * @param ContainerBuilder $container
     */
    private function processRepository(ContainerBuilder $container): void
    {
        $repositories = [];
        foreach (array_keys($container->findTaggedServiceIds('gm_redmine_user_provider.user_repository')) as $id) {
            $repositories[] = $id;
        }
        if (count($repositories) > 1) {
            throw new \InvalidArgumentException(
                sprintf(
                    'You cannot have multiple services tagged as "%s"',
                    'gm_redmine_user_provider.user_repository'
                )
            );
        }
        if (count($repositories) == 1) {
            $repositoryId = $repositories[0];
            $this->checkRepositoryValidity($container, $repositoryId);

            $definition = $container->getDefinition('gm_redmine_user_provider.provider');
            $definition->addMethodCall('setUserRepository', array(new Reference($repositoryId)));
        }
    }

    /**
     * Control if tagged repository is valid
     *
     * @param ContainerBuilder $container
     * @param string           $repositoryId
     */
    private function checkRepositoryValidity(ContainerBuilder $container, string $repositoryId): void
    {
        $repositoryDefinition = $container->getDefinition($repositoryId);
        $className            = $container->getParameterBag()->resolveValue($repositoryDefinition->getClass());
        $reflection           = new \ReflectionClass($className);
        $repositoryInterface  = 'GMaissa\RedmineUserProviderBundle\Repository\UserRepositoryInterface';
        if (!$reflection->implementsInterface($repositoryInterface)) {
            throw new \InvalidArgumentException(
                sprintf(
                    'The user repository %s should implement interface "%s"',
                    $repositoryId,
                    'GMaissa\RedmineUserProviderBundle\Repository\UserRepositoryInterface'
                )
            );
        }
    }
}
