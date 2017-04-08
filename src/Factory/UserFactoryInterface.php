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

use Symfony\Component\Security\Core\User\UserInterface;

/**
 * User factory interface
 */
interface UserFactoryInterface
{
    /**
     * Create new User
     *
     * @param array|null $data
     *
     * @return UserInterface
     */
    public function build(array $data = null): UserInterface;

    /**
     * Set the application user class name
     *
     * @param string $class
     */
    public function setUserClass(string $class);

    /**
     * Retrieve the user class
     *
     * @return string
     */
    public function getUserClass(): string;

    /**
     * Refresh the User data
     *
     * @param UserInterface $user
     *
     * @return UserInterface
     */
    public function refreshUser(UserInterface $user): UserInterface;
}
