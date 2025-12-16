<?php
/**
 * Repository Interface.
 *
 * @package WPStarterPlugin
 */

declare(strict_types=1);

namespace Starter\Shared\Contracts;

/**
 * Interface RepositoryInterface
 *
 * Base repository interface for data access.
 *
 * @template T
 */
interface RepositoryInterface
{
    /**
     * Find an entity by its ID.
     *
     * @param int $id The entity ID.
     * @return T|null
     */
    public function find(int $id): mixed;

    /**
     * Find all entities.
     *
     * @param array<string, mixed> $criteria Optional criteria.
     * @return array<T>
     */
    public function findAll(array $criteria = []): array;

    /**
     * Create a new entity.
     *
     * @param array<string, mixed> $data Entity data.
     * @return int The created entity ID.
     */
    public function create(array $data): int;

    /**
     * Update an existing entity.
     *
     * @param int $id The entity ID.
     * @param array<string, mixed> $data Entity data.
     * @return bool Whether the update was successful.
     */
    public function update(int $id, array $data): bool;

    /**
     * Delete an entity.
     *
     * @param int $id The entity ID.
     * @return bool Whether the deletion was successful.
     */
    public function delete(int $id): bool;
}
