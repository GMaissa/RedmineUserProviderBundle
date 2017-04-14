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
        $defaultUserClass = 'GMaissa\RedmineUserProviderBundle\Model\RedmineUser';
        $treeBuilder      = new TreeBuilder();
        $rootNode         = $treeBuilder->root('gm_redmine_user_provider');

        $rootNode
            ->children()
                ->arrayNode('redmine')
                    ->children()
                        ->scalarNode('url')->isRequired()->cannotBeEmpty()->end()
                        ->arrayNode('allowed_domains')
                            ->prototype('scalar')->end()
                        ->end()
                    ->end()
                ->end()
                ->scalarNode('user_class')->defaultValue($defaultUserClass)->cannotBeEmpty()->end()
                ->enumNode('persistence_driver')->values(array('orm'))->end()
                ->booleanNode('oauthserver_bridge')->defaultFalse()->end()
            ->end();

        return $treeBuilder;
    }
}
