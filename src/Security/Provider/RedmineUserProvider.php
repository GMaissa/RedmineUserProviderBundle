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

namespace GMaissa\RedmineUserProviderBundle\Security\Provider;

use GMaissa\RedmineUserProviderBundle\ApiClient\RedmineApiClientInterface;
use GMaissa\RedmineUserProviderBundle\Factory\UserFactoryInterface;
use GMaissa\RedmineUserProviderBundle\Model\RedmineUser;
use GMaissa\RedmineUserProviderBundle\Repository\UserRepositoryInterface;
use Psr\Log\LoggerInterface;
use Symfony\Component\Security\Core\Exception\AuthenticationException;
use Symfony\Component\Security\Core\Exception\UsernameNotFoundException;
use Symfony\Component\Security\Core\User\UserInterface;
use Symfony\Component\Security\Core\User\UserProviderInterface;

/**
 * Redmine User provider class
 */
class RedmineUserProvider implements UserProviderInterface, CredentialsUserProviderInterface
{
    /**
     * @var UserFactoryInterface
     */
    protected $userFactory;

    /**
     * @var RedmineApiClientInterface
     */
    protected $apiClient;

    /**
     * @var UserRepositoryInterface
     */
    protected $userRepository = false;

    /**
     * @var array
     */
    protected $allowedDomains;

    /**
     * @var LoggerInterface
     */
    protected $logger;

    /**
     * Class constructor
     *
     * @param array           $allowedDomains
     * @param LoggerInterface $logger
     */
    public function __construct(array $allowedDomains, LoggerInterface $logger)
    {
        $this->allowedDomains = $allowedDomains;
        $this->logger         = $logger;
    }

    /**
     * Set User object factory
     *
     * @param UserFactoryInterface $userFactory
     */
    public function setUserFactory(UserFactoryInterface $userFactory)
    {
        $this->userFactory = $userFactory;
    }

    /**
     * Set the Redmine API client
     *
     * @param RedmineApiClientInterface $apiClient
     */
    public function setApiClient(RedmineApiClientInterface $apiClient)
    {
        $this->apiClient = $apiClient;
    }

    /**
     * Set the User repository
     *
     * @param UserRepositoryInterface $userRepository
     */
    public function setUserRepository(UserRepositoryInterface $userRepository)
    {
        $this->userRepository = $userRepository;
    }

    /**
     * {@inheritdoc}
     */
    public function loadUserByCredentials(string $username, string $password): UserInterface
    {
        try {
            $this->apiClient->connect($username, $password);
            $data = $this->apiClient->api('user')->getCurrentUser();

            if (!$data || !count($data)) {
                $this->logger->debug(sprintf('Invalid credentials %s / ****', $username));
                throw new AuthenticationException('Invalid credentials');
            }

            $this->checkUserDomain($data);

            $user = $this->getUser($data);
        } catch (AuthenticationException $e) {
            throw new AuthenticationException($e);
        } catch (\Exception $e) {
            $this->logger->debug(
                sprintf('Invalid credentials')
            );
            throw new AuthenticationException('Invalid credentials');
        }

        return $user;
    }

    /**
     * {@inheritdoc}
     */
    public function loadUserByUsername($username)
    {
        throw new UsernameNotFoundException(
            sprintf('Username "%s" does not exist.', $username)
        );
    }

    /**
     * {@inheritdoc}
     */
    public function refreshUser(UserInterface $user)
    {
        $user = $this->userFactory->refreshUser($user);

        return $user;
    }

    /**
     * {@inheritdoc}
     */
    public function supportsClass($class)
    {
        $obj = new \ReflectionClass($class);

        return (
            $obj->getName() == $this->userFactory->getUserClass() ||
            $obj->isSubclassOf($this->userFactory->getUserClass())
        );
    }

    /**
     * Control if the user's email address domain is allowed
     *
     * @param array $data
     */
    private function checkUserDomain(array $data)
    {
        if (count($this->allowedDomains)) {
            $userDomainAllowed = false;
            foreach ($this->allowedDomains as $allowedDomain) {
                if (strpos($data['user']['mail'], '@' . $allowedDomain)) {
                    $userDomainAllowed = true;
                }
            }
            if (!$userDomainAllowed) {
                $this->logger->debug(
                    sprintf('User email %s from unauthorized domain', $data['user']['mail'])
                );
                throw new AuthenticationException('User not from allowed domain');
            }
        }
    }

    /**
     * Retrieve the user or create it
     *
     * @param array $data
     *
     * @return RedmineUser
     */
    private function getUser(array $data): RedmineUser
    {
        $user = null;
        $userData = [
            'email'     => $data['user']['mail'],
            'username'  => $data['user']['login'],
            'password'  => $data['user']['api_key'],
            'enabled'   => true,
            'firstname' => $data['user']['firstname'],
            'lastname'  => $data['user']['lastname'],
        ];

        if ($this->userRepository) {
            $user = $this->userRepository->findOneBy(['username' => $data['user']['login']]);
        }

        if (is_null($user)) {
            $user = $this->userFactory->build($userData);
        }

        if ($this->userRepository) {
            $this->userRepository->save($user);
        }

        return $user;
    }
}
