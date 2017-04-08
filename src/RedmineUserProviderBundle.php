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

namespace GMaissa\RedmineUserProviderBundle;

use GMaissa\RedmineUserProviderBundle\DependencyInjection\Compiler\ApiClientCompilerPass;
use GMaissa\RedmineUserProviderBundle\DependencyInjection\Compiler\UserFactoryCompilerPass;
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
        $container->addCompilerPass(new UserFactoryCompilerPass());
        $container->addCompilerPass(new ApiClientCompilerPass());
    }
}
