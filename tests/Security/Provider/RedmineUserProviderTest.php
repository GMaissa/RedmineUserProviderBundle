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

namespace GMaissa\RedmineUserProviderBundle\Tests\Security\Provider;

use GMaissa\RedmineUserProviderBundle\Model\RedmineUser;
use GMaissa\RedmineUserProviderBundle\Security\Provider\RedmineUserProvider;
use GMaissa\RedmineUserProviderBundle\Tester\Mock\ApiClient\RedmineApiClientMock;
use PHPUnit\Framework\TestCase;

class RedmineUserProviderTest extends TestCase
{
    private $factory;

    private $apiClient;

    private $userRepository;

    private $logger;

    public function setUp()
    {
        $this->factory = $this->getMockBuilder('\GMaissa\RedmineUserProviderBundle\Factory\UserFactoryInterface')
            ->disableOriginalConstructor()
            ->setMethods(['build', 'getUserClass', 'refreshUser', 'setUserClass'])
            ->getMock();

        $this->apiClient = new RedmineApiClientMock('http://redmine.test.com');

        $this->userRepository = $this->getMockBuilder('\GMaissa\RedmineUserProviderBundle\Repository\UserRepositoryInterface')
            ->setMethods(['findOneBy', 'save'])
            ->getMock();

        $this->logger = $this->createMock('\Psr\Log\LoggerInterface');
    }

    /**
     * @dataProvider providerLoadUserByCredentials
     */
    public function testLoadUserByCredentials($username, $password, $expectedResult)
    {
        $this->factory->expects($this->once())->method('build')->willReturn($expectedResult);

        $provider = new RedmineUserProvider(['test.com'], $this->logger);
        $provider->setUserFactory($this->factory);
        $provider->setApiClient($this->apiClient);

        $this->assertEquals($expectedResult, $provider->loadUserByCredentials($username, $password));
    }

    /**
     * @dataProvider providerLoadUserByCredentials
     */
    public function testLoadUserByCredentialsWithRepository($username, $password, $expectedResult)
    {
        $this->factory->expects($this->once())->method('build')->willReturn($expectedResult);
        $this->userRepository->expects($this->once())->method('findOneBy')->willReturn(null);
        $this->userRepository->expects($this->once())->method('save')->willReturn($expectedResult);

        $provider = new RedmineUserProvider(['test.com'], $this->logger);
        $provider->setUserFactory($this->factory);
        $provider->setApiClient($this->apiClient);
        $provider->setUserRepository($this->userRepository);

        $this->assertEquals($expectedResult, $provider->loadUserByCredentials($username, $password));
    }

    public function providerLoadUserByCredentials()
    {
        return [
            [
                'test',
                'test',
                new RedmineUser(
                    [
                        'firstname' => 'Te',
                        'lastname'  => 'St',
                        'mail'      => 'test@test.com',
                        'username'  => 'test',
                        'password'  => 'api-key',
                        'enabled'   => true,
                    ]
                )
            ]
        ];
    }

    /**
     * @dataProvider providerWrongCredentialsException
     */
    public function testWrongCredentialsException($username, $password, $expectedException)
    {
        $this->expectException($expectedException);

        $provider = new RedmineUserProvider(['test.com'], $this->logger);
        $provider->setUserFactory($this->factory);
        $provider->setApiClient($this->apiClient);

        $provider->loadUserByCredentials($username, $password);
    }

    public function providerWrongCredentialsException()
    {
        return [
            [
                'test',
                'test2',
                '\Exception'
            ],
            [
                'test-unauthorized',
                'test',
                //'\Exception'
                '\Symfony\Component\Security\Core\Exception\AuthenticationException'
            ]
        ];
    }

    public function testLoadUserByUsername()
    {
        $this->expectException('\Symfony\Component\Security\Core\Exception\UsernameNotFoundException');

        $provider = new RedmineUserProvider(['test.com'], $this->logger);
        $provider->setUserFactory($this->factory);
        $provider->setApiClient($this->apiClient);

        $provider->loadUserByUsername('test');
    }

    public function testRefreshUser()
    {
        $user = new RedmineUser(
            [
                'firstname' => 'Te',
                'lastname'  => 'St',
                'mail'      => 'test@test.com',
                'username'  => 'test',
                'password'  => 'api-key',
                'enabled'   => true,
            ]
        );
        $this->factory->expects($this->once())->method('refreshUser')->willReturn($user);

        $provider = new RedmineUserProvider(['test.com'], $this->logger);
        $provider->setUserFactory($this->factory);
        $provider->setApiClient($this->apiClient);

        $this->assertEquals($user, $provider->refreshUser($user));

    }

    /**
     * @dataProvider providerSupportsClass
     */
    public function testSupportsClass($class, $expectedResult)
    {
        $this->factory->method('getUserClass')->willReturn('GMaissa\RedmineUserProviderBundle\Model\RedmineUser');
        $provider = new RedmineUserProvider(['test.com'], $this->logger);
        $provider->setUserFactory($this->factory);
        $provider->setApiClient($this->apiClient);

        $this->assertEquals($expectedResult, $provider->supportsClass($class));
    }

    public function providerSupportsClass()
    {
        return [
            [
                'GMaissa\RedmineUserProviderBundle\Model\RedmineUser',
                true
            ],
            [
                self::class,
                false
            ],
        ];
    }
}
