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

use Doctrine\ORM\EntityRepository;
use Symfony\Component\Security\Core\User\UserInterface;

/**
 * User Repository Class
 */
class DoctrineUserRepository extends EntityRepository implements UserRepositoryInterface
{
    /**
     * {@inheritdoc}
     */
    public function delete(UserInterface $user)
    {
        $this->_em->remove($user);
        $this->_em->flush();
    }

    /**
     * {@inheritdoc}
     */
    public function save(UserInterface $user)
    {
        $this->_em->persist($user);
        $this->_em->flush();
    }

    public function getClass(): string
    {
        return $this->_entityName;
    }
}
