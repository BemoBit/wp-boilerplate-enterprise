<?php
/**
 * Singleton Interface.
 *
 * @package WPStarterPlugin
 */

declare(strict_types=1);

namespace Starter\Shared\Contracts;

/**
 * Interface SingletonInterface
 *
 * Interface for singleton pattern implementation.
 */
interface SingletonInterface
{
    /**
     * Get the singleton instance.
     *
     * @return static
     */
    public static function getInstance(): static;
}
