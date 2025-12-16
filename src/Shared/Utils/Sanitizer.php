<?php
/**
 * Sanitizer Utility.
 *
 * @package WPStarterPlugin
 */

declare(strict_types=1);

namespace Starter\Shared\Utils;

/**
 * Class Sanitizer
 *
 * Provides sanitization helper methods.
 */
final class Sanitizer
{
    /**
     * Sanitize a string.
     *
     * @param mixed $value The value to sanitize.
     * @return string The sanitized string.
     */
    public static function string(mixed $value): string
    {
        return sanitize_text_field((string) $value);
    }

    /**
     * Sanitize an email.
     *
     * @param mixed $value The value to sanitize.
     * @return string The sanitized email.
     */
    public static function email(mixed $value): string
    {
        return sanitize_email((string) $value);
    }

    /**
     * Sanitize a URL.
     *
     * @param mixed $value The value to sanitize.
     * @return string The sanitized URL.
     */
    public static function url(mixed $value): string
    {
        return esc_url_raw((string) $value);
    }

    /**
     * Sanitize an integer.
     *
     * @param mixed $value The value to sanitize.
     * @return int The sanitized integer.
     */
    public static function int(mixed $value): int
    {
        return absint($value);
    }

    /**
     * Sanitize a float.
     *
     * @param mixed $value The value to sanitize.
     * @return float The sanitized float.
     */
    public static function float(mixed $value): float
    {
        return (float) filter_var($value, FILTER_SANITIZE_NUMBER_FLOAT, FILTER_FLAG_ALLOW_FRACTION);
    }

    /**
     * Sanitize a boolean.
     *
     * @param mixed $value The value to sanitize.
     * @return bool The sanitized boolean.
     */
    public static function bool(mixed $value): bool
    {
        return filter_var($value, FILTER_VALIDATE_BOOLEAN);
    }

    /**
     * Sanitize HTML content.
     *
     * @param mixed $value The value to sanitize.
     * @return string The sanitized HTML.
     */
    public static function html(mixed $value): string
    {
        return wp_kses_post((string) $value);
    }

    /**
     * Sanitize a key/slug.
     *
     * @param mixed $value The value to sanitize.
     * @return string The sanitized key.
     */
    public static function key(mixed $value): string
    {
        return sanitize_key((string) $value);
    }

    /**
     * Sanitize a filename.
     *
     * @param mixed $value The value to sanitize.
     * @return string The sanitized filename.
     */
    public static function filename(mixed $value): string
    {
        return sanitize_file_name((string) $value);
    }

    /**
     * Sanitize an array of values.
     *
     * @param array<mixed>         $values   The values to sanitize.
     * @param callable|string|null $callback The sanitization callback or method name.
     * @return array<mixed> The sanitized array.
     */
    public static function array(array $values, callable|string|null $callback = null): array
    {
        if ($callback === null) {
            $callback = [self::class, 'string'];
        } elseif (is_string($callback) && method_exists(self::class, $callback)) {
            $callback = [self::class, $callback];
        }

        return array_map($callback, $values);
    }
}
