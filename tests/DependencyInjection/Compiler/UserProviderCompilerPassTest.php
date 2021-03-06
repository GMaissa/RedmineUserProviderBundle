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

namespace GMaissa\RedmineUserProviderBundle\Tests\DependencyInjection\Compiler;

use GMaissa\RedmineUserProviderBundle\DependencyInjection\Compiler\UserProviderCompilerPass;
use GMaissa\RedmineUserProviderBundle\DependencyInjection\GmRedmineUserProviderExtension;
use PHPUnit\Framework\TestCase;
use Symfony\Component\Config\FileLocator;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\DependencyInjection\Loader\YamlFileLoader;
use Symfony\Component\DependencyInjection\ParameterBag\ParameterBag;
use Symfony\Component\Yaml\Parser;

class UserProviderCompilerPassTest extends TestCase
{
    /**
     * @dataProvider providerProcess
     */
    public function testProcess($configFile, $servicesConf, $expectedResults)
    {
        $parser    = new Parser();
        $config    = $parser->parse(file_get_contents(__DIR__ . '/../../fixtures/configuration/' . $configFile));
        $container = $this->getContainer($config);

        if (!is_null($servicesConf)) {
            $loader = new YamlFileLoader($container, new FileLocator(__DIR__ . '/../../fixtures/services/'));
            $loader->load($servicesConf);
        }

        $compiler = new UserProviderCompilerPass();
        $compiler->process($container);

        $apiClient = $container->get('gm_redmine_user_provider.api.client');
        $this->assertTrue(
            $apiClient instanceof $expectedResults['apiClient']
        );

        $factory = $container->get('gm_redmine_user_provider.factory.user');
        $this->assertTrue(
            $factory instanceof $expectedResults['factory']
        );

        $providerDefinition  = $container->getDefinition('gm_redmine_user_provider.provider');
        $providerMethodCalls = $providerDefinition->getMethodCalls();
        if (isset($expectedResults['userRepository'])) {
            $this->assertEquals(3, count($providerMethodCalls));
            $this->assertEquals('setUserRepository', $providerMethodCalls[2][0]);
        } else {
            $this->assertEquals(2, count($providerMethodCalls));
        }
    }

    public function providerProcess()
    {
        return [
            [
                'minimal.yml',
                null,
                [
                    'apiClient' => '\GMaissa\RedmineUserProviderBundle\ApiClient\Adapter\RedmineClientAdapter',
                    'factory'   => '\GMaissa\RedmineUserProviderBundle\Factory\UserFactory',
                ]
            ],
            [
                'with_doctrine_repository.yml',
                null,
                [
                    'apiClient'      => '\GMaissa\RedmineUserProviderBundle\ApiClient\Adapter\RedmineClientAdapter',
                    'factory'        => '\GMaissa\RedmineUserProviderBundle\Factory\UserFactory',
                    'userRepository' => 'gm_redmine_user_provider.repository.user.orm'
                ]
            ],
            [
                'minimal.yml',
                'valid_services.yml',
                [
                    'apiClient'      => '\GMaissa\RedmineUserProviderBundle\ApiClient\Adapter\RedmineClientAdapter',
                    'factory'        => '\GMaissa\RedmineUserProviderBundle\Factory\UserFactory',
                    'userRepository' => 'gm_redmine_user_provider.repository.user.orm'
                ]
            ]
        ];
    }

    private function getContainer($config)
    {
        $container = new ContainerBuilder(new ParameterBag());
        $extension = new GmRedmineUserProviderExtension();
        $container->registerExtension($extension);

        $extension->load($config, $container);

        return $container;
    }

    /**
     * @dataProvider providerProcessException
     */
    public function testProcessException($configFile, $servicesConf, $expectedException)
    {
        $this->expectException($expectedException);
        $parser    = new Parser();
        $config    = $parser->parse(file_get_contents(__DIR__ . '/../../fixtures/configuration/' . $configFile));
        $container = $this->getContainer($config);

        if (!is_null($servicesConf)) {
            $loader = new YamlFileLoader($container, new FileLocator(__DIR__ . '/../../fixtures/services/'));
            $loader->load($servicesConf);
        }

        $compiler = new UserProviderCompilerPass();
        $compiler->process($container);
    }

    public function providerProcessException()
    {
        return [
            [
                'minimal.yml',
                'unvalid_services.yml',
                '\InvalidArgumentException'
            ],
            [
                'minimal.yml',
                'multiple_repositories_services.yml',
                '\InvalidArgumentException'
            ]
        ];
    }
}
