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
        if ($container->hasParameter('redmine_user_provider.user_repository_service')) {
            $userRepositoryServiceId = $container->getParameter('redmine_user_provider.user_repository_service');
            $repositoryDefinition    = $container->getDefinition($userRepositoryServiceId);
            $className               = $container->getParameterBag()->resolveValue($repositoryDefinition->getClass());
            $reflection              = new \ReflectionClass($className);
            if (!$reflection->implementsInterface(
                'GMaissa\RedmineUserProviderBundle\Repository\UserRepositoryInterface'
            )
            ) {
                throw new \InvalidArgumentException(
                    sprintf(
                        'The user repository %s should implement interface "%s"',
                        $userRepositoryServiceId,
                        'GMaissa\RedmineUserProviderBundle\Repository\UserRepositoryInterface'
                    )
                );
            }

            $providerDefinition = $container->getDefinition('redmine_user_provider.provider');
            $providerDefinition->addMethodCall(
                'setUserRepository',
                [$container->get($userRepositoryServiceId)]
            );
        }
    }
}
