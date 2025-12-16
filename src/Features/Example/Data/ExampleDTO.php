<?php
/**
 * Example Data Transfer Object.
 *
 * @package WPStarterPlugin
 */

declare(strict_types=1);

namespace Starter\Features\Example\Data;

use Starter\Shared\Abstracts\AbstractDTO;

/**
 * Class ExampleDTO
 *
 * Data Transfer Object for Example entities.
 */
readonly class ExampleDTO extends AbstractDTO
{
    /**
     * Constructor.
     *
     * @param int         $id        The example ID.
     * @param string      $title     The example title.
     * @param string      $content   The example content.
     * @param string      $status    The example status.
     * @param string|null $createdAt The creation date.
     * @param string|null $updatedAt The last update date.
     */
    public function __construct(
        public int $id,
        public string $title,
        public string $content = '',
        public string $status = 'active',
        public ?string $createdAt = null,
        public ?string $updatedAt = null,
    ) {}

    /**
     * Create a DTO from an array.
     *
     * @param array<string, mixed> $data The data array.
     * @return static
     */
    public static function fromArray(array $data): static
    {
        return new self(
            id: (int) ($data['id'] ?? 0),
            title: sanitize_text_field($data['title'] ?? ''),
            content: wp_kses_post($data['content'] ?? ''),
            status: sanitize_key($data['status'] ?? 'active'),
            createdAt: $data['created_at'] ?? null,
            updatedAt: $data['updated_at'] ?? null,
        );
    }

    /**
     * Create a DTO from a WordPress post.
     *
     * @param \WP_Post $post The WordPress post.
     * @return static
     */
    public static function fromPost(\WP_Post $post): static
    {
        return new self(
            id: $post->ID,
            title: $post->post_title,
            content: $post->post_content,
            status: $post->post_status,
            createdAt: $post->post_date,
            updatedAt: $post->post_modified,
        );
    }
}
