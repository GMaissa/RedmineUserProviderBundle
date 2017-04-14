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

namespace GMaissa\RedmineUserProviderBundle\Model;

use Symfony\Component\Security\Core\User\UserInterface;

/**
 * User Model Class
 */
class RedmineUser implements UserInterface
{
    const ROLE_DEFAULT     = 'ROLE_USER';

    /**
     * @var integer
     */
    protected $id;

    /**
     * @var string
     */
    protected $firstname;

    /**
     * @var string
     */
    protected $lastname;

    /**
     * @var string
     */
    protected $email;

    /**
     * @var string
     */
    protected $username;

    /**
     * @var string
     */
    protected $password;

    /**
     * @var array
     */
    protected $roles;

    /**
     * @var bool
     */
    protected $enabled;

    /**
     * @var string
     */
    protected $salt;

    /**
     * User constructor
     *
     * @param array $properties
     */
    public function __construct($properties = array())
    {
        $this->roles = [];
        foreach ($properties as $key => $value) {
            $this->$key = $value;
        }
    }

    /**
     * {@inheritdoc}
     */
    public function getRoles()
    {
        $roles = $this->roles;

        if (count($roles) == 0) {
            $roles[] = self::ROLE_DEFAULT;
        }

        return $roles;
    }

    /**
     * {@inheritdoc}
     */
    public function getSalt()
    {
        return $this->salt;
    }

    /**
     * {@inheritdoc}
     */
    public function getPassword()
    {
        return $this->password;
    }

    /**
     * {@inheritdoc}
     */
    public function getUsername()
    {
        return $this->username;
    }

    /**
     * {@inheritdoc}
     */
    public function eraseCredentials()
    {
        $this->password = null;
    }
}
