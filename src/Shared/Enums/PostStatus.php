<?php
/**
 * Post Status Enum.
 *
 * @package WPStarterPlugin
 */

declare(strict_types=1);

namespace Starter\Shared\Enums;

/**
 * Enum PostStatus
 *
 * WordPress post status values.
 */
enum PostStatus: string
{
    case PUBLISH = 'publish';
    case DRAFT = 'draft';
    case PENDING = 'pending';
    case PRIVATE = 'private';
    case TRASH = 'trash';
    case AUTO_DRAFT = 'auto-draft';
    case INHERIT = 'inherit';
    case FUTURE = 'future';
}
