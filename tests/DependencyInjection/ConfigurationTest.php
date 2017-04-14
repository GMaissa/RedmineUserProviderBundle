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

namespace GMaissa\RedmineUserProviderBundle\Tests\DependencyInjection;

use GMaissa\RedmineUserProviderBundle\DependencyInjection\Configuration;
use PHPUnit\Framework\TestCase;
use Symfony\Component\Config\Definition\Processor;
use Symfony\Component\Yaml\Parser;

class ConfigurationTest extends TestCase
{
    public function testInitializable()
    {
        $this->assertInstanceOf(
            'GMaissa\RedmineUserProviderBundle\DependencyInjection\Configuration',
            new Configuration(false)
        );
    }

    /**
     * @dataProvider providerConfiguration
     */
    public function testConfiguration($configFile, $expectedConfig)
    {
        $parser = new Parser();
        $config = $parser->parse(file_get_contents(__DIR__.'/../fixtures/configuration/' . $configFile));

        $configuration = new Configuration(true);
        $processor = new Processor();

        $processedConfiguration = $processor->processConfiguration($configuration, [$config['gm_redmine_user_provider']]);

        $this->assertEquals($expectedConfig, $processedConfiguration);
    }

    public function providerConfiguration()
    {
        return [
            [
                'minimal.yml',
                [
                    'redmine' => [
                        'url' => 'redmine.test.com',
                        'allowed_domains' => ['test.com']
                    ],
                    'user_class' => '\GMaissa\RedmineUserProviderBundle\Model\RedmineUser'
                ]
            ],
            [
                'with_doctrine_repository.yml',
                [
                    'redmine' => [
                        'url' => 'redmine.test.com',
                        'allowed_domains' => ['test.com']
                    ],
                    'user_class' => '\GMaissa\RedmineUserProviderBundle\Model\RedmineUser',
                    'user_repository_service' => 'gm_redmine_user_provider.repository.user.orm'
                ]
            ]
        ];
    }
}
