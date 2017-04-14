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

namespace GMaissa\RedmineUserProviderBundle\Tests\Bridge\OAuthServer\Security\Storage;

use GMaissa\RedmineUserProviderBundle\Bridge\OAuthServer\Security\Storage\OAuthStorage;
use GMaissa\RedmineUserProviderBundle\Factory\UserFactory;
use GMaissa\RedmineUserProviderBundle\Model\RedmineUser;
use GMaissa\RedmineUserProviderBundle\Security\Provider\RedmineUserProvider;
use GMaissa\RedmineUserProviderBundle\Tester\Mock\ApiClient\RedmineApiClientMock;
use PHPUnit\Framework\TestCase;

class OAuthStorageTest extends TestCase
{
    private $oauthStorage;

    private $client;

    public function setUp()
    {
        $userFactory  = new UserFactory();
        $userFactory->setUserClass('\GMaissa\RedmineUserProviderBundle\Model\RedmineUser');
        $apiClient    = new RedmineApiClientMock('http://redmine.test.com');
        $logger       = $this->createMock('\Psr\Log\LoggerInterface');
        $userProvider = new RedmineUserProvider(['test.com'], $logger);
        $userProvider->setUserFactory($userFactory);
        $userProvider->setApiClient($apiClient);

        $clientManager       = $this->createMock('\FOS\OAuthServerBundle\Model\ClientManagerInterface');
        $accessTokenManager  = $this->createMock('\FOS\OAuthServerBundle\Model\AccessTokenManagerInterface');
        $refreshTokenManager = $this->createMock('\FOS\OAuthServerBundle\Model\RefreshTokenManagerInterface');
        $authCodeManager     = $this->createMock('\FOS\OAuthServerBundle\Model\AuthCodeManagerInterface');

        $this->oauthStorage = new OAuthStorage(
            $clientManager,
            $accessTokenManager,
            $refreshTokenManager,
            $authCodeManager,
            $userProvider
        );
        $this->client       = $this->createMock('\FOS\OAuthServerBundle\Model\Client');
    }

    /**
     * @dataProvider providerCheckUserCredentials
     */
    public function testCheckUserCredentials($username, $password, $expectedResult)
    {
        $this->assertEquals(
            $expectedResult,
            $this->oauthStorage->checkUserCredentials($this->client, $username, $password)
        );
    }

    public function providerCheckUserCredentials()
    {
        return [
            [
                'test',
                'test',
                [
                    'data' => new RedmineUser(
                        [
                            'firstname' => 'Te',
                            'lastname'  => 'St',
                            'email'      => 'test@test.com',
                            'username'  => 'test',
                            'password'  => 'api-key',
                            'enabled'   => true,
                        ]
                    )
                ]
            ],
            [
                'test',
                'password',
                false
            ]
        ];
    }

    public function testCheckUserCredentialsException()
    {
        $client = $this->createMock('\OAuth2\Model\IOAuth2Client');

        $this->expectException('\InvalidArgumentException');
        $this->expectExceptionMessage('Client has to implement the ClientInterface');
        $this->oauthStorage->checkUserCredentials($client, 'test', 'test');
    }
}
