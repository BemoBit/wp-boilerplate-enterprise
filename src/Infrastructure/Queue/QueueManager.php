<?php
/**
 * Queue Manager.
 *
 * @package WPStarterPlugin
 */

declare(strict_types=1);

namespace Starter\Infrastructure\Queue;

/**
 * Class QueueManager
 *
 * Handles background job processing using WordPress cron.
 */
final class QueueManager
{
    /**
     * Queue option name.
     */
    private const QUEUE_OPTION = 'wp_starter_plugin_queue';

    /**
     * Cron hook name.
     */
    private const CRON_HOOK = 'wp_starter_plugin_process_queue';

    /**
     * Initialize the queue manager.
     */
    public function init(): void
    {
        add_action(self::CRON_HOOK, [$this, 'processQueue']);
    }

    /**
     * Push a job to the queue.
     *
     * @param string               $handler The job handler class.
     * @param array<string, mixed> $data    The job data.
     */
    public function push(string $handler, array $data = []): void
    {
        $queue = $this->getQueue();

        $queue[] = [
            'id' => wp_generate_uuid4(),
            'handler' => $handler,
            'data' => $data,
            'created_at' => current_time('mysql'),
        ];

        $this->saveQueue($queue);
        $this->scheduleProcessing();
    }

    /**
     * Process queued jobs.
     */
    public function processQueue(): void
    {
        $queue = $this->getQueue();

        if (empty($queue)) {
            return;
        }

        $job = array_shift($queue);
        $this->saveQueue($queue);

        if ($job && class_exists($job['handler'])) {
            $handler = new $job['handler']();
            if (method_exists($handler, 'handle')) {
                $handler->handle($job['data']);
            }
        }

        if (! empty($queue)) {
            $this->scheduleProcessing();
        }
    }

    /**
     * Get the current queue.
     *
     * @return array<int, array<string, mixed>>
     */
    private function getQueue(): array
    {
        return (array) get_option(self::QUEUE_OPTION, []);
    }

    /**
     * Save the queue.
     *
     * @param array<int, array<string, mixed>> $queue The queue data.
     */
    private function saveQueue(array $queue): void
    {
        update_option(self::QUEUE_OPTION, $queue);
    }

    /**
     * Schedule queue processing.
     */
    private function scheduleProcessing(): void
    {
        if (! wp_next_scheduled(self::CRON_HOOK)) {
            wp_schedule_single_event(time() + 60, self::CRON_HOOK);
        }
    }

    /**
     * Clear the queue.
     */
    public function clear(): void
    {
        delete_option(self::QUEUE_OPTION);
        wp_clear_scheduled_hook(self::CRON_HOOK);
    }
}
