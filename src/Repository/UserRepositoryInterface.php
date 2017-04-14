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

namespace GMaissa\RedmineUserProviderBundle\Repository;

use Symfony\Component\Security\Core\User\UserInterface;

/**
 * Interface class for User Repository
 */
interface UserRepositoryInterface
{
    /**
     * Retrieve a User matching the criteria
     *
     * @param array      $criteria
     * @param array|null $orderBy
     *
     * @return UserInterface \ null
     */
    public function findOneBy(array $criteria, array $orderBy = null);

    /**
     * Create a user
     *
     * @param UserInterface $user
     */
    public function save(UserInterface $user);
}
