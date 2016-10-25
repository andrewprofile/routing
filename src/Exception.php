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

/**
 * Generic package exception.
 *
 * @author Andrzej Kostrzewa <andkos11@gmail.com>
 */
class Exception extends \Exception
{
    /**
     * A route name was not found.
     *
     * @param  string $name
     *
     * @return Exception\RouteNotFound
     * @throws Exception\RouteNotFound
     */
    public static function RouteNotFound(string $name)
    {
        throw new Exception\RouteNotFound(
            sprintf('No route found for "%s".', $name)
        );
    }

    /**
     * The rule was not allowed.
     *
     * @param  string $path
     * @param  string $class
     * @param  string $route
     *
     * @return Exception\RuleNotAllowed
     * @throws Exception\RuleNotAllowed
     */
    public static function RuleNotAllowed(string $path, string $class, string $route)
    {
        throw new Exception\RuleNotAllowed(
            sprintf(
                '%s FAILED %s ON %s',
                $path,
                $class,
                $route
            )
        );
    }

    /**
     * The rule was not found.
     *
     * @param  string $rule
     * @param  string $key
     *
     * @return Exception\RuleNotFound
     * @throws Exception\RuleNotFound
     */
    public static function RuleNotFound(string $rule, string $key)
    {
        throw new Exception\RuleNotFound(
            sprintf(
                'Expected Rule, got %s for key %s',
                $rule,
                $key
            )
        );
    }

    /**
     * The resource was not found.
     *
     * @param  string $path
     *
     * @return Exception\ResourceNotFound
     * @throws Exception\ResourceNotFound
     */
    public static function ResourceNotFound(string $path)
    {
        throw new Exception\ResourceNotFound(
            sprintf('No routes found for "%s".', $path)
        );
    }

    /**
     * A route requirement was not be string.
     *
     * @param  string $name
     *
     * @return Exception\RequirementNotString
     * @throws Exception\RequirementNotString
     */
    public static function RequirementNotString(string $name)
    {
        throw new Exception\RequirementNotString(
            sprintf('Routing requirement for "%s" must be a string.', $name)
        );
    }

    /**
     * A route requirement was not be empty.
     *
     * @param  string $name
     *
     * @return Exception\RequirementIsEmpty
     * @throws Exception\RequirementIsEmpty
     */
    public static function RequirementIsEmpty(string $name)
    {
        throw new Exception\RequirementIsEmpty(
            sprintf('Routing requirement for "%s" cannot be empty.', $name)
        );
    }
}
