<?php
/**
 * Abstract Repository.
 *
 * @package WPStarterPlugin
 */

declare(strict_types=1);

namespace Starter\Shared\Abstracts;

use Starter\Shared\Contracts\RepositoryInterface;

/**
 * Class AbstractRepository
 *
 * Base class for all repositories.
 *
 * @template T
 * @implements RepositoryInterface<T>
 */
abstract class AbstractRepository implements RepositoryInterface
{
    /**
     * The database table name (without prefix).
     */
    protected string $table;

    /**
     * Get the full table name with prefix.
     */
    protected function getTable(): string
    {
        global $wpdb;
        return $wpdb->prefix . $this->table;
    }

    /**
     * Get the WordPress database object.
     */
    protected function db(): \wpdb
    {
        global $wpdb;
        return $wpdb;
    }
}
