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

namespace GMaissa\RedmineUserProviderBundle\Tests\ApiClient\Adapter;

use PHPUnit\Framework\TestCase;

class RedmineClientAdapterTest extends TestCase
{
    private $client;

    public function setUp()
    {
        parent::setUp();
        $this->client = $this->getMockBuilder('\StdClass')
            ->setMethods(
                [
                    ''
                ]
            );
    }
}
