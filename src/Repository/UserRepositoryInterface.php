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
