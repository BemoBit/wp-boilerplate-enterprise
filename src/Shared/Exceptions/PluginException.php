<?php
/**
 * Plugin Exception.
 *
 * @package WPStarterPlugin
 */

declare(strict_types=1);

namespace Starter\Shared\Exceptions;

use Exception;

/**
 * Class PluginException
 *
 * Base exception for all plugin exceptions.
 */
class PluginException extends Exception
{
    /**
     * Create a new exception instance.
     *
     * @param string          $message  The exception message.
     * @param int             $code     The exception code.
     * @param \Throwable|null $previous The previous exception.
     */
    public function __construct(
        string $message = '',
        int $code = 0,
        ?\Throwable $previous = null
    ) {
        parent::__construct($message, $code, $previous);
    }

    /**
     * Create a not found exception.
     *
     * @param string $resource The resource name.
     * @param int    $id       The resource ID.
     * @return static
     */
    public static function notFound(string $resource, int $id): static
    {
        return new static(
            sprintf('%s with ID %d not found.', $resource, $id),
            404
        );
    }

    /**
     * Create a validation exception.
     *
     * @param string $message The validation message.
     * @return static
     */
    public static function validation(string $message): static
    {
        return new static($message, 422);
    }

    /**
     * Create an unauthorized exception.
     *
     * @param string $message The message.
     * @return static
     */
    public static function unauthorized(string $message = 'Unauthorized'): static
    {
        return new static($message, 401);
    }
}
