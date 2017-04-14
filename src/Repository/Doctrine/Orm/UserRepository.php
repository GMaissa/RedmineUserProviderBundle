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

namespace GMaissa\RedmineUserProviderBundle\Repository\Doctrine\Orm;

use Doctrine\ORM\EntityRepository;
use GMaissa\RedmineUserProviderBundle\Repository\UserRepositoryInterface;
use Symfony\Component\Security\Core\User\UserInterface;

/**
 * Doctrine ORM User Repository Class
 */
class UserRepository extends EntityRepository implements UserRepositoryInterface
{
    /**
     * {@inheritdoc}
     */
    public function save(UserInterface $user)
    {
        $this->_em->persist($user);
        $this->_em->flush();
    }
}
