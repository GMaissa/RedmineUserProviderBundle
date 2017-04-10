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

namespace GMaissa\RedmineUserProviderBundle\Tests\Mock\Repository;

use GMaissa\RedmineUserProviderBundle\Repository\UserRepositoryInterface;
use Symfony\Component\Security\Core\User\UserInterface;

class ValidCustomUserRepository implements UserRepositoryInterface
{
    public function findOneBy(array $criteria, array $orderBy = null)
    {
    }

    public function delete(UserInterface $user)
    {
    }

    public function save(UserInterface $user)
    {
    }

    public function getClass(): string
    {
        return 'Ok';
    }
}
