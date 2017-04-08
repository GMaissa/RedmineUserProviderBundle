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

namespace GMaissa\RedmineUserProviderBundle\ApiClient\Adapter;

use GMaissa\RedmineUserProviderBundle\ApiClient\RedmineApiClientInterface;

/**
 * AWS Ec2 client adapter class
 */
class RedmineClientAdapter implements RedmineApiClientInterface
{
    /**
     * Redmine API Client Class
     * @var string
     */
    private $clientClass;

    /**
     * @var Object
     */
    private $client;

    /**
     * @var string
     */
    private $url;

    /**
     * Class constructor.
     *
     * @param string $url
     */
    public function __construct(string $url)
    {
        $this->url = $url;
    }

    /**
     * Set the Redmine client Class
     *
     * @param string $clientClass
     */
    public function setClientClass(string $clientClass)
    {
        $this->clientClass = $clientClass;
    }

    /**
     * Connect to Redmine API
     *
     * @param string $login
     * @param string $password
     */
    public function connect(string $login, string $password)
    {
        $this->client = new $this->clientClass($this->url, $login, $password);
    }

    /**
     * Retrieve a specific API
     *
     * @param string $apiName
     *
     * @return Object
     * @throws \Exception if not connected
     */
    public function api(string $apiName)
    {
        if (!$this->client instanceof $this->clientClass) {
            throw new \Exception('Connection to server not instantiated');
        }

        return $this->client->api($apiName);
    }
}
