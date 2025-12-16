<?php
/**
 * Example Model.
 *
 * @package WPStarterPlugin
 */

declare(strict_types=1);

namespace Starter\Features\Example\Models;

use Starter\Features\Example\Data\ExampleDTO;
use Starter\Shared\Abstracts\AbstractRepository;

/**
 * Class ExampleModel
 *
 * Repository for Example entities.
 */
final class ExampleModel extends AbstractRepository
{
    /**
     * The database table name (without prefix).
     *
     * @var string
     */
    protected string $table = 'starter_examples';

    /**
     * Find an example by ID.
     *
     * @param int $id The example ID.
     * @return ExampleDTO|null
     */
    public function find(int $id): ?ExampleDTO
    {
        $row = $this->db()->get_row(
            $this->db()->prepare(
                "SELECT * FROM {$this->getTable()} WHERE id = %d",
                $id
            ),
            ARRAY_A
        );

        return $row ? ExampleDTO::fromArray($row) : null;
    }

    /**
     * Find all examples.
     *
     * @param array<string, mixed> $criteria Query criteria.
     * @return array<ExampleDTO>
     */
    public function findAll(array $criteria = []): array
    {
        $limit = (int) ($criteria['limit'] ?? 10);
        $offset = (int) ($criteria['offset'] ?? 0);
        $status = $criteria['status'] ?? null;

        $sql = "SELECT * FROM {$this->getTable()}";

        if ($status) {
            $sql .= $this->db()->prepare(' WHERE status = %s', $status);
        }

        $sql .= $this->db()->prepare(' ORDER BY id DESC LIMIT %d OFFSET %d', $limit, $offset);

        $rows = $this->db()->get_results($sql, ARRAY_A);

        return array_map(fn(array $row) => ExampleDTO::fromArray($row), $rows ?: []);
    }

    /**
     * Create a new example.
     *
     * @param array<string, mixed> $data Example data.
     * @return int The created example ID.
     */
    public function create(array $data): int
    {
        $this->db()->insert(
            $this->getTable(),
            [
                'title' => $data['title'] ?? '',
                'content' => $data['content'] ?? '',
                'status' => $data['status'] ?? 'active',
                'created_at' => current_time('mysql'),
                'updated_at' => current_time('mysql'),
            ],
            ['%s', '%s', '%s', '%s', '%s']
        );

        return (int) $this->db()->insert_id;
    }

    /**
     * Update an existing example.
     *
     * @param int                  $id   The example ID.
     * @param array<string, mixed> $data Example data.
     * @return bool Whether the update was successful.
     */
    public function update(int $id, array $data): bool
    {
        $data['updated_at'] = current_time('mysql');

        $result = $this->db()->update(
            $this->getTable(),
            $data,
            ['id' => $id],
            ['%s', '%s', '%s', '%s'],
            ['%d']
        );

        return $result !== false;
    }

    /**
     * Delete an example.
     *
     * @param int $id The example ID.
     * @return bool Whether the deletion was successful.
     */
    public function delete(int $id): bool
    {
        $result = $this->db()->delete(
            $this->getTable(),
            ['id' => $id],
            ['%d']
        );

        return $result !== false;
    }

    /**
     * Count examples.
     *
     * @param string|null $status Optional status filter.
     * @return int The count.
     */
    public function count(?string $status = null): int
    {
        $sql = "SELECT COUNT(*) FROM {$this->getTable()}";

        if ($status) {
            $sql .= $this->db()->prepare(' WHERE status = %s', $status);
        }

        return (int) $this->db()->get_var($sql);
    }
}
