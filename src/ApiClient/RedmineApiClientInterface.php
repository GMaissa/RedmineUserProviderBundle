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

namespace GMaissa\RedmineUserProviderBundle\ApiClient;

/**
 * AWS Ec2 client adapter class
 */
interface RedmineApiClientInterface
{
    /**
     * Class constructor.
     *
     * @param string $url
     */
    public function __construct(string $url);

    /**
     * Connect to Redmine API
     *
     * @param string $login
     * @param string $password
     */
    public function connect(string $login, string $password);

    /**
     * Retrieve a specific API
     *
     * @param string $apiName
     *
     * @return Object
     * @throws \Exception if not connected
     */
    public function api(string $apiName);
}
