<?php
/**
 * Validator Utility.
 *
 * @package WPStarterPlugin
 */

declare(strict_types=1);

namespace Starter\Shared\Utils;

/**
 * Class Validator
 *
 * Provides validation helper methods.
 */
final class Validator
{
    /**
     * Validate that a value is not empty.
     *
     * @param mixed $value The value to validate.
     * @return bool Whether the value is not empty.
     */
    public static function required(mixed $value): bool
    {
        if (is_string($value)) {
            return trim($value) !== '';
        }

        return ! empty($value);
    }

    /**
     * Validate an email address.
     *
     * @param string $value The value to validate.
     * @return bool Whether the value is a valid email.
     */
    public static function email(string $value): bool
    {
        return is_email($value) !== false;
    }

    /**
     * Validate a URL.
     *
     * @param string $value The value to validate.
     * @return bool Whether the value is a valid URL.
     */
    public static function url(string $value): bool
    {
        return filter_var($value, FILTER_VALIDATE_URL) !== false;
    }

    /**
     * Validate a numeric value.
     *
     * @param mixed $value The value to validate.
     * @return bool Whether the value is numeric.
     */
    public static function numeric(mixed $value): bool
    {
        return is_numeric($value);
    }

    /**
     * Validate a positive integer.
     *
     * @param mixed $value The value to validate.
     * @return bool Whether the value is a positive integer.
     */
    public static function positiveInt(mixed $value): bool
    {
        return is_numeric($value) && (int) $value > 0;
    }

    /**
     * Validate a string length.
     *
     * @param string   $value The value to validate.
     * @param int      $min   Minimum length.
     * @param int|null $max   Maximum length (optional).
     * @return bool Whether the value length is within range.
     */
    public static function length(string $value, int $min, ?int $max = null): bool
    {
        $length = mb_strlen($value);

        if ($length < $min) {
            return false;
        }

        if ($max !== null && $length > $max) {
            return false;
        }

        return true;
    }

    /**
     * Validate that a value is in an array.
     *
     * @param mixed        $value   The value to validate.
     * @param array<mixed> $allowed The allowed values.
     * @return bool Whether the value is in the array.
     */
    public static function inArray(mixed $value, array $allowed): bool
    {
        return in_array($value, $allowed, true);
    }

    /**
     * Validate a date string.
     *
     * @param string $value  The value to validate.
     * @param string $format The expected format.
     * @return bool Whether the value is a valid date.
     */
    public static function date(string $value, string $format = 'Y-m-d'): bool
    {
        $date = \DateTime::createFromFormat($format, $value);
        return $date && $date->format($format) === $value;
    }

    /**
     * Validate a regex pattern.
     *
     * @param string $value   The value to validate.
     * @param string $pattern The regex pattern.
     * @return bool Whether the value matches the pattern.
     */
    public static function regex(string $value, string $pattern): bool
    {
        return preg_match($pattern, $value) === 1;
    }

    /**
     * Validate a slug.
     *
     * @param string $value The value to validate.
     * @return bool Whether the value is a valid slug.
     */
    public static function slug(string $value): bool
    {
        return self::regex($value, '/^[a-z0-9]+(?:-[a-z0-9]+)*$/');
    }
}
