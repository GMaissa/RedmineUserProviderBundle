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

namespace GMaissa\RedmineUserProviderBundle\Factory;

use Symfony\Component\Security\Core\Exception\UnsupportedUserException;
use Symfony\Component\Security\Core\User\UserInterface;

/**
 * User factory class
 */
class UserFactory implements UserFactoryInterface
{
    /**
     * @var string
     */
    protected $userClass;

    /**
     * {@inheritdoc}
     */
    public function setUserClass(string $class)
    {
        $this->userClass = $class;
    }

    /**
     * {@inheritdoc}
     */
    public function build(array $data = null): UserInterface
    {
        return new $this->userClass($data);
    }

    /**
     * {@inheritdoc}
     */
    public function getUserClass(): string
    {
        return $this->userClass;
    }

    /**
     * {@inheritdoc}
     */
    public function refreshUser(UserInterface $user): UserInterface
    {
        if (!$user instanceof $this->userClass) {
            throw new UnsupportedUserException(
                sprintf('Instances of "%s" are not supported.', get_class($user))
            );
        }

        return $user;
    }
}
