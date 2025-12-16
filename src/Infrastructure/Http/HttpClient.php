<?php
/**
 * HTTP Client.
 *
 * @package WPStarterPlugin
 */

declare(strict_types=1);

namespace Starter\Infrastructure\Http;

use WP_Error;

/**
 * Class HttpClient
 *
 * Wrapper for WordPress HTTP API.
 */
final class HttpClient
{
    /**
     * Default request timeout in seconds.
     */
    private const DEFAULT_TIMEOUT = 30;

    /**
     * User agent string.
     */
    private const USER_AGENT = 'WP-Starter-Plugin/' . WP_STARTER_PLUGIN_VERSION;

    /**
     * Send a GET request.
     *
     * @param string               $url     The URL to request.
     * @param array<string, mixed> $args    Optional request arguments.
     * @return array<string, mixed>|WP_Error The response or error.
     */
    public function get(string $url, array $args = []): array|WP_Error
    {
        return $this->request('GET', $url, $args);
    }

    /**
     * Send a POST request.
     *
     * @param string               $url     The URL to request.
     * @param array<string, mixed> $data    The data to send.
     * @param array<string, mixed> $args    Optional request arguments.
     * @return array<string, mixed>|WP_Error The response or error.
     */
    public function post(string $url, array $data = [], array $args = []): array|WP_Error
    {
        $args['body'] = $data;
        return $this->request('POST', $url, $args);
    }

    /**
     * Send a PUT request.
     *
     * @param string               $url     The URL to request.
     * @param array<string, mixed> $data    The data to send.
     * @param array<string, mixed> $args    Optional request arguments.
     * @return array<string, mixed>|WP_Error The response or error.
     */
    public function put(string $url, array $data = [], array $args = []): array|WP_Error
    {
        $args['body'] = $data;
        return $this->request('PUT', $url, $args);
    }

    /**
     * Send a DELETE request.
     *
     * @param string               $url     The URL to request.
     * @param array<string, mixed> $args    Optional request arguments.
     * @return array<string, mixed>|WP_Error The response or error.
     */
    public function delete(string $url, array $args = []): array|WP_Error
    {
        return $this->request('DELETE', $url, $args);
    }

    /**
     * Send an HTTP request.
     *
     * @param string               $method  The HTTP method.
     * @param string               $url     The URL to request.
     * @param array<string, mixed> $args    Optional request arguments.
     * @return array<string, mixed>|WP_Error The response or error.
     */
    private function request(string $method, string $url, array $args = []): array|WP_Error
    {
        $defaults = [
            'method' => $method,
            'timeout' => self::DEFAULT_TIMEOUT,
            'user-agent' => self::USER_AGENT,
            'headers' => [
                'Accept' => 'application/json',
            ],
        ];

        $args = wp_parse_args($args, $defaults);

        return wp_remote_request($url, $args);
    }

    /**
     * Get the response body as JSON.
     *
     * @param array<string, mixed>|WP_Error $response The response.
     * @return array<string, mixed>|null The decoded JSON or null on error.
     */
    public function getJson(array|WP_Error $response): ?array
    {
        if (is_wp_error($response)) {
            return null;
        }

        $body = wp_remote_retrieve_body($response);
        $decoded = json_decode($body, true);

        return is_array($decoded) ? $decoded : null;
    }

    /**
     * Get the response status code.
     *
     * @param array<string, mixed>|WP_Error $response The response.
     * @return int The status code or 0 on error.
     */
    public function getStatusCode(array|WP_Error $response): int
    {
        if (is_wp_error($response)) {
            return 0;
        }

        return (int) wp_remote_retrieve_response_code($response);
    }

    /**
     * Check if the response was successful.
     *
     * @param array<string, mixed>|WP_Error $response The response.
     * @return bool Whether the response was successful.
     */
    public function isSuccess(array|WP_Error $response): bool
    {
        $code = $this->getStatusCode($response);
        return $code >= 200 && $code < 300;
    }
}
