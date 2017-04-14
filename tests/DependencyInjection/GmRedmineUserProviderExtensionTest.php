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

namespace GMaissa\RedmineUserProviderBundle\Tests\DependencyInjection;

use GMaissa\RedmineUserProviderBundle\DependencyInjection\Configuration;
use GMaissa\RedmineUserProviderBundle\DependencyInjection\GmRedmineUserProviderExtension;
use PHPUnit\Framework\TestCase;
use Symfony\Component\Config\Definition\Processor;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\DependencyInjection\ParameterBag\ParameterBag;
use Symfony\Component\Yaml\Parser;

class GmRedmineUserProviderExtensionTest extends TestCase
{
    private $container;
    private $extension;

    public function setUp()
    {
        $this->container = $this->container = new ContainerBuilder(new ParameterBag());
        $this->extension = new GmRedmineUserProviderExtension();
        $this->container->registerExtension($this->extension);
    }

    /**
     * @dataProvider providerLoad
     */
    public function testLoad($configFile, $expectedResults)
    {
        $parser = new Parser();
        $config = $parser->parse(file_get_contents(__DIR__.'/../fixtures/configuration/' . $configFile));

        $this->extension->load([$config['gm_redmine_user_provider']], $this->container);

        foreach ($expectedResults['parameters'] as $name => $value) {
            $this->assertTrue(
                $this->container->hasParameter($name)
            );
            $this->assertEquals(
                $value,
                $this->container->getParameter($name)
            );
        }
        foreach ($expectedResults['services'] as $name) {
            $this->assertTrue(
                $this->container->has($name)
            );
        }
    }

    public function providerLoad()
    {
        return [
            [
                'minimal.yml',
                [
                    'parameters'    => [
                        'gm_redmine_user_provider.redmine.url' => 'redmine.test.com',
                        'gm_redmine_user_provider.redmine.allowed_domains' => ['test.com']
                    ],
                    'services' => []
                ]
            ],
            [
                'with_doctrine_repository.yml',
                [
                    'parameters' => [
                        'gm_redmine_user_provider.redmine.url' => 'redmine.test.com',
                        'gm_redmine_user_provider.redmine.allowed_domains' => ['test.com'],
                        'gm_redmine_user_provider.user_class' => 'GMaissa\RedmineUserProviderBundle\Model\RedmineUser'
                    ],
                    'services'   => [
                        'gm_redmine_user_provider.repository.user.orm'
                    ]
                ]
            ],
            [
                'with_valid_user_class.yml',
                [
                    'parameters' => [
                        'gm_redmine_user_provider.redmine.url' => 'redmine.test.com',
                        'gm_redmine_user_provider.redmine.allowed_domains' => ['test.com'],
                        'gm_redmine_user_provider.user_class' => 'GMaissa\RedmineUserProviderBundle\Tests\Mock\Entity\ValidUserEntity'
                    ],
                    'services' => []
                ]
            ],
            [
                'with_oauth_bridge.yml',
                [
                    'parameters' => [
                        'gm_redmine_user_provider.redmine.url' => 'redmine.test.com',
                        'gm_redmine_user_provider.redmine.allowed_domains' => ['test.com'],
                        'gm_redmine_user_provider.user_class' => 'GMaissa\RedmineUserProviderBundle\Model\RedmineUser',
                    ],
                    'services' => [
                        'gm_redmine_user_provider.bridge.oauth.storage'
                    ]
                ]
            ]
        ];
    }

    public function testLoadException()
    {
        $this->expectException('\InvalidArgumentException');
        $this->expectExceptionMessage(
            sprintf(
                sprintf(
                    'The user class %s should implement interface "%s"',
                    'GMaissa\RedmineUserProviderBundle\Tests\Mock\Entity\UnvalidUserEntity',
                    '\Symfony\Component\Security\Core\User\UserInterface'
                )
            )
        );

        $parser = new Parser();
        $config = $parser->parse(file_get_contents(__DIR__.'/../fixtures/configuration/with_unvalid_user_class.yml'));

        $this->extension->load([$config['gm_redmine_user_provider']], $this->container);
    }
}

