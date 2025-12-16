<?php
/**
 * Example Shortcode.
 *
 * @package WPStarterPlugin
 */

declare(strict_types=1);

namespace Starter\Features\Example\Frontend;

/**
 * Class ExampleShortcode
 *
 * Handles the [wp_starter_example] shortcode.
 */
final class ExampleShortcode
{
    /**
     * Render the shortcode.
     *
     * @param array<string, mixed>|string $atts    Shortcode attributes.
     * @param string|null                 $content Shortcode content.
     * @return string The shortcode output.
     */
    public static function render(array|string $atts = [], ?string $content = null): string
    {
        $atts = shortcode_atts(
            [
                'id' => 0,
                'title' => '',
                'class' => '',
            ],
            $atts,
            'wp_starter_example'
        );

        $templatePath = WP_STARTER_PLUGIN_DIR . 'templates/example.php';
        $themeTemplatePath = get_stylesheet_directory() . '/wp-starter-plugin/example.php';

        // Allow theme override
        if (file_exists($themeTemplatePath)) {
            $templatePath = $themeTemplatePath;
        }

        ob_start();

        if (file_exists($templatePath)) {
            // Extract attributes for use in template
            extract($atts, EXTR_SKIP); // phpcs:ignore WordPress.PHP.DontExtract.extract_extract
            include $templatePath;
        } else {
            self::renderDefault($atts, $content);
        }

        return ob_get_clean() ?: '';
    }

    /**
     * Render default output if template is not found.
     *
     * @param array<string, mixed> $atts    Shortcode attributes.
     * @param string|null          $content Shortcode content.
     */
    private static function renderDefault(array $atts, ?string $content): void
    {
        $class = ! empty($atts['class']) ? ' ' . esc_attr($atts['class']) : '';
        ?>
        <div class="wp-starter-example<?php echo $class; ?>">
            <?php if (! empty($atts['title'])) : ?>
                <h3><?php echo esc_html($atts['title']); ?></h3>
            <?php endif; ?>
            <?php if ($content) : ?>
                <div class="wp-starter-example-content">
                    <?php echo wp_kses_post($content); ?>
                </div>
            <?php endif; ?>
            <p><?php esc_html_e('This is a placeholder. Create your template at: templates/example.php', 'wp-starter-plugin'); ?></p>
        </div>
        <?php
    }
}
