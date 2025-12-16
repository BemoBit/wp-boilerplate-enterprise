<?php
/**
 * Example Service.
 *
 * @package WPStarterPlugin
 */

declare(strict_types=1);

namespace Starter\Features\Example\Services;

use Starter\Features\Example\Data\ExampleDTO;
use Starter\Infrastructure\Cache\CacheManager;

/**
 * Class ExampleService
 *
 * Business logic for the Example feature.
 */
final class ExampleService
{
    /**
     * Constructor.
     *
     * @param CacheManager $cache The cache manager.
     */
    public function __construct(
        private readonly CacheManager $cache,
    ) {}

    /**
     * Get all examples.
     *
     * @param array<string, mixed> $args Query arguments.
     * @return array<ExampleDTO>
     */
    public function getAll(array $args = []): array
    {
        // Example implementation - customize as needed
        return [];
    }

    /**
     * Get a single example by ID.
     *
     * @param int $id The example ID.
     * @return ExampleDTO|null
     */
    public function get(int $id): ?ExampleDTO
    {
        $cacheKey = 'example_' . $id;

        return $this->cache->remember($cacheKey, function () use ($id): ?ExampleDTO {
            // Example implementation - fetch from database
            return null;
        });
    }

    /**
     * Create a new example.
     *
     * @param ExampleDTO $dto The example data.
     * @return int The created example ID.
     */
    public function create(ExampleDTO $dto): int
    {
        // Example implementation - save to database
        $id = 0;

        // Fire event for other features to react
        do_action('wp_starter_plugin_example_created', $dto, $id);

        return $id;
    }

    /**
     * Update an existing example.
     *
     * @param int        $id  The example ID.
     * @param ExampleDTO $dto The example data.
     * @return bool Whether the update was successful.
     */
    public function update(int $id, ExampleDTO $dto): bool
    {
        // Example implementation - update in database
        $success = false;

        if ($success) {
            $this->cache->delete('example_' . $id);
            do_action('wp_starter_plugin_example_updated', $dto, $id);
        }

        return $success;
    }

    /**
     * Delete an example.
     *
     * @param int $id The example ID.
     * @return bool Whether the deletion was successful.
     */
    public function delete(int $id): bool
    {
        // Example implementation - delete from database
        $success = false;

        if ($success) {
            $this->cache->delete('example_' . $id);
            do_action('wp_starter_plugin_example_deleted', $id);
        }

        return $success;
    }
}
