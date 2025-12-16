<?php
/**
 * Singleton Trait.
 *
 * @package WPStarterPlugin
 */

declare(strict_types=1);

namespace Starter\Shared\Traits;

/**
 * Trait SingletonTrait
 *
 * Provides singleton pattern implementation.
 */
trait SingletonTrait
{
    /**
     * The singleton instance.
     *
     * @var static|null
     */
    private static ?self $instance = null;

    /**
     * Get the singleton instance.
     *
     * @return static
     */
    public static function getInstance(): static
    {
        if (self::$instance === null) {
            self::$instance = new static();
        }

        return self::$instance;
    }

    /**
     * Prevent cloning.
     */
    private function __clone(): void
    {
    }

    /**
     * Prevent unserialization.
     *
     * @throws \Exception Always throws exception.
     */
    public function __wakeup(): void
    {
        throw new \Exception('Cannot unserialize singleton');
    }
}
