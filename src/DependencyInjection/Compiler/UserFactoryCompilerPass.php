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

/**
 * User factory service compiler pass
 */
class UserFactoryCompilerPass extends AbstractCompilerPass
{
    private $serviceTag = 'redmine_user_provider.user_factory';
    private $defaultServiceId = 'redmine_user_provider.factory.user.default';
    private $serviceName = 'redmine_user_provider.factory.user';
    private $serviceInterface = '\\GMaissa\\RedmineUserProviderBundle\\Factory\\UserFactoryInterface';
    private $serviceType = 'user factory';
}
