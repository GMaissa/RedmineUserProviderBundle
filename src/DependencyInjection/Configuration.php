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

use Symfony\Component\Config\Definition\Builder\TreeBuilder;
use Symfony\Component\Config\Definition\ConfigurationInterface;

/**
 * Bundle configuration management
 */
class Configuration implements ConfigurationInterface
{
    /**
     * {@inheritdoc}
     */
    public function getConfigTreeBuilder()
    {
        $defaultUserClass = "\\GMaissa\\RedmineUserProviderBundle\\Model\\User";
        $treeBuilder = new TreeBuilder();
        $rootNode = $treeBuilder->root('redmine_user_provider');

        $rootNode
            ->children()
                ->arrayNode('redmine')
                    ->children()
                        ->scalarNode('url')->isRequired()->cannotBeEmpty()->end()
                        ->arrayNode('allowed_domains')->end()
                    ->end()
                ->end()
                ->scalarNode('user_class')->defaultValue($defaultUserClass)->cannotBeEmpty()->end()
                ->scalarNode('user_repository_service')->end()
            ->end();

        return $treeBuilder;
    }
}
