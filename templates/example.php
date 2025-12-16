<?php
/**
 * Example Template.
 *
 * This template can be overridden by copying it to yourtheme/wp-starter-plugin/example.php.
 *
 * @package WPStarterPlugin
 * @var int    $id    The example ID.
 * @var string $title The example title.
 * @var string $class Additional CSS classes.
 */

defined('ABSPATH') || exit;
?>

<div class="wp-starter-example<?php echo ! empty($class) ? ' ' . esc_attr($class) : ''; ?>">
    <?php if (! empty($title)) : ?>
        <h3 class="wp-starter-example__title"><?php echo esc_html($title); ?></h3>
    <?php endif; ?>

    <div class="wp-starter-example__content">
        <!-- Your content here -->
        <p><?php esc_html_e('This is an example template. Customize it as needed.', 'wp-starter-plugin'); ?></p>
    </div>
</div>
