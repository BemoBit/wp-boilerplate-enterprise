<?php
/**
 * Cache Manager.
 *
 * @package WPStarterPlugin
 */

declare(strict_types=1);

namespace Starter\Infrastructure\Cache;

/**
 * Class CacheManager
 *
 * Handles caching operations using WordPress transients.
 */
final class CacheManager
{
    /**
     * Cache key prefix.
     */
    private const PREFIX = 'wp_starter_';

    /**
     * Default cache expiration in seconds (1 hour).
     */
    private const DEFAULT_EXPIRATION = 3600;

    /**
     * Get a cached value.
     *
     * @param string $key The cache key.
     * @return mixed The cached value or false if not found.
     */
    public function get(string $key): mixed
    {
        return get_transient(self::PREFIX . $key);
    }

    /**
     * Set a cached value.
     *
     * @param string $key        The cache key.
     * @param mixed  $value      The value to cache.
     * @param int    $expiration Expiration time in seconds.
     * @return bool Whether the value was set.
     */
    public function set(string $key, mixed $value, int $expiration = self::DEFAULT_EXPIRATION): bool
    {
        return set_transient(self::PREFIX . $key, $value, $expiration);
    }

    /**
     * Delete a cached value.
     *
     * @param string $key The cache key.
     * @return bool Whether the value was deleted.
     */
    public function delete(string $key): bool
    {
        return delete_transient(self::PREFIX . $key);
    }

    /**
     * Check if a cache key exists.
     *
     * @param string $key The cache key.
     * @return bool Whether the key exists.
     */
    public function has(string $key): bool
    {
        return $this->get($key) !== false;
    }

    /**
     * Get or set a cached value.
     *
     * @param string   $key        The cache key.
     * @param callable $callback   Callback to generate the value if not cached.
     * @param int      $expiration Expiration time in seconds.
     * @return mixed The cached or generated value.
     */
    public function remember(string $key, callable $callback, int $expiration = self::DEFAULT_EXPIRATION): mixed
    {
        $value = $this->get($key);

        if ($value !== false) {
            return $value;
        }

        $value = $callback();
        $this->set($key, $value, $expiration);

        return $value;
    }

    /**
     * Clear all plugin cache.
     */
    public function flush(): void
    {
        global $wpdb;

        $wpdb->query(
            $wpdb->prepare(
                "DELETE FROM {$wpdb->options} WHERE option_name LIKE %s",
                '_transient_' . self::PREFIX . '%'
            )
        );

        $wpdb->query(
            $wpdb->prepare(
                "DELETE FROM {$wpdb->options} WHERE option_name LIKE %s",
                '_transient_timeout_' . self::PREFIX . '%'
            )
        );
    }
}
