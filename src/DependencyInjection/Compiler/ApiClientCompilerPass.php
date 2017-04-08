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
 * Api client service compiler pass
 */
class ApiClientCompilerPass extends AbstractCompilerPass
{
    private $serviceTag = 'redmine_user_provider.api_client';
    private $defaultServiceId = 'redmine_user_provider.api.client.default';
    private $serviceName = 'redmine_user_provider.api.client';
    private $serviceInterface = '\\GMaissa\\RedmineUserProviderBundle\\ApiClient\\RedmineApiClientInterface';
    private $serviceType = 'redmine api client';
}
