<?php
/**
 * This file is part of the Stack package.
 *
 * (c) Andrzej Kostrzewa <andkos11@gmail.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

declare(strict_types=1);

namespace Stack\Routing;

class RouteTest extends \PHPUnit_Framework_TestCase
{
    public function testConstructor()
    {
        $callable =  function () {
            return 'foo';
        };

        $route = new Route('foo', '/{foo}', $callable);
        $this->assertEquals('foo', $route->name(), '__construct() takes a path as its first argument');
        $this->assertEquals('/{foo}', $route->path(), '__construct() takes a path as its second argument');
        $this->assertEquals($callable, $route->handler(), '__construct() takes requirements as its third argument');

        $route = Route::createWithOptional(
            'bar',
            '/{bar}',
            $callable,
            ['foo' => 'bar'],
            ['foo' => '\d+'],
            '{locale}.example.com'
        );
        $this->assertEquals('bar', $route->name(), '__construct() takes a path as its first argument');
        $this->assertEquals('/{bar}', $route->path(), '__construct() takes a path as its second argument');
        $this->assertEquals($callable, $route->handler(), '__construct() takes requirements as its third argument');
        $this->assertEquals(['foo' => 'bar'], $route->defaults(), '__construct() takes defaults as its fourth argument');
        $this->assertEquals(['foo' => '\d+'], $route->requirements(), '__construct() takes requirements as its fifth argument');
        $this->assertEquals('{locale}.example.com', $route->host(), '__construct() takes a host pattern as its sixth argument');

        $route = Route::createWithOptional(
            'home',
            '/',
            'foobar',
            [],
            [],
            '',
            ['application/json'],
            ['POST', 'put'],
            ['foo' => 'bar'],
            ['isAdmin' => true],
            true,
            ['card' => ['foo']]
        );

        $this->assertEquals('foobar', $route->handler(), '__construct() takes requirements as its third argument');
        $this->assertEquals(['application/json'], $route->accepts(), '__construct() takes requirements as its seventh argument');
        $this->assertEquals(['POST', 'PUT'], $route->allows(), '__construct() takes methods as its eighth argument and uppercases it');
        $this->assertEquals(['foo' => 'bar'], $route->attributes(), '__construct() takes requirements as its ninth argument');
        $this->assertEquals(['isAdmin' => true], $route->auth(), '__construct() takes requirements as its tenth argument');
        $this->assertEquals(true, $route->secure(), '__construct() takes requirements as its eleventh argument');
        $this->assertEquals(['card' => ['foo']], $route->wildcard(), '__construct() takes requirements as its twelfth argument');
        $this->assertEquals(true, $route->isRoutable(), '__construct() takes requirements as its thirteenth argument');
    }

    public function testPath()
    {
        $route = new Route('foo', '/{foo}', null);
        $this->assertEquals('/{foo}', $route->path(), '__construct() sets the path');
        $route = new Route('foo', '', null);
        $this->assertEquals('/', $route->path(), '-__construct() adds a / at the beginning of the path if needed');
        $route = new Route('foo', 'bar', null);
        $this->assertEquals('/bar', $route->path(), '__construct() adds a / at the beginning of the path if needed');
        $route = new Route('foo', '//path', null);
        $this->assertEquals('/path', $route->path(), '__construct() does not allow two slashes "//" at the beginning of the path as it would be confused with a network path when generating the path from the route');
    }

    public function testRequirements()
    {
        $route = Route::createWithOptional('foo', '/{foo}', null, [], ['foo' => '\d+']);
        $this->assertEquals(['foo' => '\d+'], $route->requirements(), '__construct() sets the requirements');
        $this->assertEquals('\d+', $route->requirement('foo'), '->requirement() returns a requirement');
        $this->assertEmpty($route->requirement('bar'), '->requirement() returns "" if a requirement is not defined');
        $route = Route::createWithOptional('foo', '/{foo}', null, [], ['foo' => '^\d+$']);
        $this->assertEquals('\d+', $route->requirement('foo'), '->requirement() removes ^ and $ from the path');
    }

    public function testRequirement()
    {
        $route = Route::createWithOptional('foo', '/{foo}', null, [], ['foo' => '^\d+$']);
        $this->assertEquals('\d+', $route->requirement('foo'), '__construct() removes ^ and $ from the path');
    }

    /**
     * @dataProvider invalidRequirements
     * @expectedException \InvalidArgumentException
     * @param $requirement
     */
    public function testSetInvalidRequirement($requirement)
    {
        Route::createWithOptional('foo', '/{foo}', null, [], ['foo' => $requirement]);
    }

    public function invalidRequirements()
    {
        return [
            [''],
            [[]],
            ['^$'],
            ['^'],
            ['$'],
        ];
    }

    public function testSerialize()
    {
        $route = Route::createWithOptional('foo', '/{foo}', null, [], ['foo' => '^\d+$']);
        $serialized = serialize($route);
        $unserialized = unserialize($serialized);
        $this->assertEquals($route, $unserialized);
        $this->assertNotSame($route, $unserialized);
    }
}
