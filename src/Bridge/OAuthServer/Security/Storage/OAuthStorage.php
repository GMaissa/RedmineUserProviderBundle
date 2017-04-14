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

namespace GMaissa\RedmineUserProviderBundle\Bridge\OAuthServer\Security\Storage;

use FOS\OAuthServerBundle\Model\ClientInterface;
use FOS\OAuthServerBundle\Storage\OAuthStorage as BaseOAuthStorage;
use GMaissa\RedmineUserProviderBundle\Security\Provider\CredentialsUserProviderInterface;
use OAuth2\Model\IOAuth2Client;
use Symfony\Component\Security\Core\Exception\AuthenticationException;

/**
 * OAuth server storage class retrieving user from given credentials
 */
class OAuthStorage extends BaseOAuthStorage
{
    /**
     * @var CredentialsUserProviderInterface
     */
    protected $userProvider;

    /**
     * {@inheritdoc}
     */
    public function checkUserCredentials(IOAuth2Client $client, $username, $password)
    {
        if (!$client instanceof ClientInterface) {
            throw new \InvalidArgumentException('Client has to implement the ClientInterface');
        }

        $result = false;
        try {
            $user = $this->userProvider->loadUserByCredentials($username, $password);

            if (null !== $user) {
                $result = array(
                    'data' => $user,
                );
            }
        } catch (AuthenticationException $e) {
            $result = false;
        }

        return $result;
    }
}
