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
 * Api client service compiler pass
 */
abstract class AbstractCompilerPass implements CompilerPassInterface
{
    private $serviceTag;
    private $defaultServiceId;
    private $serviceName;
    private $serviceInterface;
    private $serviceType;

    /**
     * {@inheritdoc}
     */
    public function process(ContainerBuilder $container)
    {
        if (!$container->has($this->serviceName)) {
            $id         = $this->defaultServiceId;
            $serviceTag = $this->serviceTag;
            $serviceIds = [];

            foreach (array_keys($container->findTaggedServiceIds($serviceTag)) as $id) {
                $serviceIds[] = $id;
            }

            if (count($serviceIds) > 1) {
                throw new \Exception(
                    sprintf(
                        'There should be only one service tagged as %s, %s found.',
                        $serviceTag,
                        count($serviceIds)
                    )
                );
            } elseif (count($serviceIds) == 1) {
                $id         = $serviceIds[0];
                $definition = $container->getDefinition($id);
                $className  = $container->getParameterBag()->resolveValue($definition->getClass());
                $reflection = new \ReflectionClass($className);
                if (!$reflection->implementsInterface($this->serviceInterface)
                ) {
                    throw new \InvalidArgumentException(
                        sprintf(
                            'The %s "%s" is not valid',
                            $this->serviceType,
                            $id
                        )
                    );
                }
            }

            $container->setAlias($this->serviceName, $id);
        }
    }
}
