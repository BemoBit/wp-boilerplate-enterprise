<?php
/**
 * Abstract Service Provider.
 *
 * @package WPStarterPlugin
 */

declare(strict_types=1);

namespace Starter\Shared\Abstracts;

use Starter\Shared\Contracts\ServiceProviderInterface;

/**
 * Class AbstractServiceProvider
 *
 * Base class for all service providers.
 */
abstract class AbstractServiceProvider implements ServiceProviderInterface
{
    /**
     * Register the service provider.
     */
    abstract public function register(): void;

    /**
     * Add an action hook.
     *
     * @param string   $hook     The hook name.
     * @param callable $callback The callback function.
     * @param int      $priority The priority.
     * @param int      $args     Number of arguments.
     */
    protected function addAction(string $hook, callable $callback, int $priority = 10, int $args = 1): void
    {
        add_action($hook, $callback, $priority, $args);
    }

    /**
     * Add a filter hook.
     *
     * @param string   $hook     The hook name.
     * @param callable $callback The callback function.
     * @param int      $priority The priority.
     * @param int      $args     Number of arguments.
     */
    protected function addFilter(string $hook, callable $callback, int $priority = 10, int $args = 1): void
    {
        add_filter($hook, $callback, $priority, $args);
    }
}
