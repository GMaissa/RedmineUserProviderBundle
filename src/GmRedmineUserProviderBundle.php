<?php
/**
 * File part of the Redmine User Provider bundle
 *
 * @category  SymfonyBundle
 * @package   GMaissa.RedmineUserProviderBundle
 * @author    Guillaume Maïssa <pro.g@maissa.fr>
 * @copyright 2017 Guillaume Maïssa
 * @license   https://opensource.org/licenses/MIT The MIT License (MIT)
 */

namespace GMaissa\RedmineUserProviderBundle;

use GMaissa\RedmineUserProviderBundle\DependencyInjection\Compiler\UserProviderCompilerPass;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\HttpKernel\Bundle\Bundle;

/**
 * Infrastructure UserBundle definition class
 */
class GmRedmineUserProviderBundle extends Bundle
{
    /**
     * {@inheritdoc}
     */
    public function build(ContainerBuilder $container)
    {
        parent::build($container);

        $container->addCompilerPass(new UserProviderCompilerPass());
    }
}
