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
