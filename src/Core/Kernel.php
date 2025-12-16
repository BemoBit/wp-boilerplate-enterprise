<?php
/**
 * Application Kernel.
 *
 * @package WPStarterPlugin
 */

declare(strict_types=1);

namespace Starter\Core;

/**
 * Class Kernel
 *
 * HTTP/CLI kernel for handling requests.
 */
final class Kernel
{
    /**
     * Determine if the current request is an admin request.
     */
    public static function isAdmin(): bool
    {
        return is_admin() && ! wp_doing_ajax();
    }

    /**
     * Determine if the current request is an AJAX request.
     */
    public static function isAjax(): bool
    {
        return wp_doing_ajax();
    }

    /**
     * Determine if the current request is a REST API request.
     */
    public static function isRest(): bool
    {
        if (defined('REST_REQUEST') && REST_REQUEST) {
            return true;
        }

        $prefix = rest_get_url_prefix();
        $requestUri = $_SERVER['REQUEST_URI'] ?? '';

        return str_contains($requestUri, '/' . $prefix . '/');
    }

    /**
     * Determine if the current request is a CLI request.
     */
    public static function isCli(): bool
    {
        return defined('WP_CLI') && WP_CLI;
    }

    /**
     * Determine if the current request is a cron request.
     */
    public static function isCron(): bool
    {
        return defined('DOING_CRON') && DOING_CRON;
    }

    /**
     * Determine if the current request is a frontend request.
     */
    public static function isFrontend(): bool
    {
        return ! self::isAdmin() && ! self::isAjax() && ! self::isRest() && ! self::isCli() && ! self::isCron();
    }
}
