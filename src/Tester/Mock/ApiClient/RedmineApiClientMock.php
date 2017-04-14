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

namespace GMaissa\RedmineUserProviderBundle\Tester\Mock\ApiClient;

use GMaissa\RedmineUserProviderBundle\ApiClient\RedmineApiClientInterface;

/**
 * Mock class for User provider testing
 * @codeCoverageIgnore
 */
class RedmineApiClientMock implements RedmineApiClientInterface
{
    /**
     * @var string
     */
    private $connectedUser = false;

    private $usersData = [
        'test' => [
            'user' => [
                'mail' => 'test@test.com',
                'login' => 'test',
                'api_key' => 'api-key',
                'password' => 'test',
                'firstname' => 'Te',
                'lastname' => 'St'
            ]
        ],
        'test-unauthorized' => [
            'user' => [
                'mail' => 'test@test.org',
                'login' => 'test-unauthorized',
                'api_key' => 'api-key2',
                'password' => 'test',
                'firstname' => 'Te',
                'lastname' => 'St 2'
            ]
        ]
    ];

    /**
     * {@inheritdoc}
     */
    public function __construct(string $url)
    {
        // No need to store the parameter which will not be used
        unset($url);
    }

    /**
     * Set the Redmine client Class
     *
     * @param string $clientClass
     */
    public function setClientClass(string $clientClass)
    {
        // Do nothing
        unset($clientClass);
    }

    /**
     * {@inheritdoc}
     */
    public function connect(string $login, string $password)
    {
        if (isset($this->usersData[$login]) && $password == $this->usersData[$login]['user']['password']) {
            $this->connectedUser = $login;
        } else {
            throw new \Exception('Invalid credentials');
        }
    }

    /**
     * {@inheritdoc}
     */
    public function api(string $apiName)
    {
        if (!$this->connectedUser) {
            throw new \Exception('Connection to server not instantiated');
        }
        $this->apiName = $apiName;

        return $this;
    }

    /**
     * Retrieve current connected user
     *
     * @return mixed|null
     */
    public function getCurrentUser()
    {
        return (isset($this->usersData[$this->connectedUser])) ? $this->usersData[$this->connectedUser] : null;
    }
}
