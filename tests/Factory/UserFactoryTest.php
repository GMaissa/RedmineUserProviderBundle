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

namespace GMaissa\RedmineUserProviderBundle\Tests\Factory;

use GMaissa\RedmineUserProviderBundle\Factory\UserFactory;
use GMaissa\RedmineUserProviderBundle\Model\RedmineUser;
use PHPUnit\Framework\TestCase;
use Symfony\Component\Security\Core\User\UserInterface;

class UserFactoryTest extends TestCase
{
    /**
     * @dataProvider provider
     */
    public function test($userProperties, $expectedUserClass, $expectedResults)
    {
        $userFactory = new UserFactory();
        $userFactory->setUserClass($expectedUserClass);
        $this->assertEquals($expectedUserClass, $userFactory->getUserClass());

        $user = $userFactory->build($userProperties);

        $this->assertInstanceOf($expectedUserClass, $user);
        foreach ($expectedResults as $method => $expectedResult) {
            $this->assertEquals($expectedResult, $user->{$method}());
        }

        $userUpdated = $userFactory->refreshUser($user);
        $this->assertEquals($user, $userUpdated);

        $this->expectException('\Symfony\Component\Security\Core\Exception\UnsupportedUserException');
        $userFactory->refreshUser($this->createMock('\Symfony\Component\Security\Core\User\UserInterface'));
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
                ],
                '\GMaissa\RedmineUserProviderBundle\Model\RedmineUser',
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
                '\GMaissa\RedmineUserProviderBundle\Model\RedmineUser',
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
