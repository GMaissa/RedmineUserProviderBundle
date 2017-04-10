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

namespace GMaissa\RedmineUserProviderBundle\Tests\Model;

use Doctrine\Common\Collections\ArrayCollection;
use GMaissa\RedmineUserProviderBundle\Model\RedmineUser;
use PHPUnit\Framework\TestCase;

class RedmineUserTest extends TestCase
{
    /**
     * @dataProvider provider
     */
    public function test($userProperties, $expectedResults)
    {
        $user = new RedmineUser($userProperties);
        foreach ($expectedResults as $method => $expectedResult) {
            $this->assertEquals($expectedResult, $user->{$method}());
        }

        $user->eraseCredentials();
        $this->assertEquals(null, $user->getPassword());
    }

    public function provider()
    {
        return [
            [
                [
                    'firstname' => 'Te',
                    'lastname' => 'St',
                    'email' => 'test@test.com',
                    'username' => 'test',
                    'password' => 'password',
                    'salt' => 'test',
                    'roles' => new ArrayCollection([RedmineUser::ROLE_DEFAULT])
                ],
                [
                    'getUsername' => 'test',
                    'getPassword' => 'password',
                    'getRoles' => [RedmineUser::ROLE_DEFAULT],
                    'getSalt' => 'test'
                ]
            ],
            [
                [
                    'firstname' => 'Te',
                    'lastname' => 'St',
                    'email' => 'test@test.com',
                    'username' => 'test',
                    'password' => 'password',
                    'salt' => 'test',
                ],
                [
                    'getUsername' => 'test',
                    'getPassword' => 'password',
                    'getRoles' => [RedmineUser::ROLE_DEFAULT],
                    'getSalt' => 'test'
                ]
            ]
        ];
    }

}
