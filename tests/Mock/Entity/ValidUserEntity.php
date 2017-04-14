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

namespace GMaissa\RedmineUserProviderBundle\Tests\Mock\Entity;

use Doctrine\ORM\Mapping as ORM;
use GMaissa\RedmineUserProviderBundle\Model\RedmineUser;

/**
 * Class ValidUserEntity
 *
 * @ORM\Entity
 * @ORM\Table(name="users")
 */
class ValidUserEntity extends RedmineUser
{
    /**
     * @ORM\Id
     * @ORM\Column(type="integer")
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    protected $id;
}
