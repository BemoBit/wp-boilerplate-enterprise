<?php
/**
 * Abstract Data Transfer Object.
 *
 * @package WPStarterPlugin
 */

declare(strict_types=1);

namespace Starter\Shared\Abstracts;

/**
 * Class AbstractDTO
 *
 * Base class for all Data Transfer Objects.
 */
abstract readonly class AbstractDTO
{
    /**
     * Create a DTO from an array.
     *
     * @param array<string, mixed> $data The data array.
     * @return static
     */
    abstract public static function fromArray(array $data): static;

    /**
     * Convert the DTO to an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(): array
    {
        return get_object_vars($this);
    }

    /**
     * Convert the DTO to JSON.
     *
     * @return string
     */
    public function toJson(): string
    {
        return (string) json_encode($this->toArray(), JSON_THROW_ON_ERROR);
    }
}
