# WP Starter Plugin

[![PHP Version](https://img.shields.io/badge/PHP-8.2%2B-blue.svg)](https://php.net)
[![WordPress Version](https://img.shields.io/badge/WordPress-6.0%2B-blue.svg)](https://wordpress.org)
[![License](https://img.shields.io/badge/License-GPL--2.0--or--later-green.svg)](LICENSE)

Enterprise-grade WordPress plugin boilerplate with Feature-Based (DDD) architecture.

## Features

- **Feature-Based Architecture**: Organized by domain features (vertical slicing)
- **DI Container**: PHP-DI for dependency injection
- **PSR-4 Autoloading**: Modern PHP autoloading via Composer
- **PHP 8.2+**: Leverages modern PHP features (readonly classes, enums, typed properties)
- **WordPress Coding Standards**: PHPCS configured with WPCS
- **Static Analysis**: PHPStan with WordPress extensions
- **Unit & Integration Testing**: PHPUnit with WordPress test suite
- **CI/CD Ready**: GitHub Actions workflows included

## Requirements

- PHP 8.2 or higher
- WordPress 6.0 or higher
- Composer 2.x
- Node.js 18+ (for asset building)

## Installation

### 1. Clone or Download

```bash
cd wp-content/plugins/
git clone https://github.com/developer/wp-starter-plugin.git
cd wp-starter-plugin
```

### 2. Install Dependencies

```bash
# Install PHP dependencies
composer install

# Install Node.js dependencies (for asset building)
npm install
```

### 3. Build Assets

```bash
# Development (with watch)
npm run start

# Production build
npm run build
```

### 4. Activate Plugin

Activate the plugin through the WordPress admin panel or via WP-CLI:

```bash
wp plugin activate wp-starter-plugin
```

## Directory Structure

```
wp-starter-plugin/
├── wp-starter-plugin.php    # Entry point
├── uninstall.php            # Cleanup on deletion
├── composer.json            # PHP dependencies
├── package.json             # JS/CSS build tools
│
├── src/
│   ├── Core/                # Plugin bootstrap & infrastructure
│   │   ├── Plugin.php       # Main bootstrapper
│   │   ├── Activator.php    # Activation logic
│   │   ├── Deactivator.php  # Deactivation logic
│   │   └── Kernel.php       # HTTP/CLI kernel
│   │
│   ├── Features/            # Domain features (vertical slices)
│   │   └── Example/         # Example feature
│   │       ├── Admin/
│   │       ├── Api/
│   │       ├── Frontend/
│   │       ├── Models/
│   │       ├── Services/
│   │       ├── Data/
│   │       └── ExampleProvider.php
│   │
│   ├── Shared/              # Cross-cutting concerns
│   │   ├── Contracts/       # Interfaces
│   │   ├── Abstracts/       # Abstract classes
│   │   ├── Traits/          # Reusable traits
│   │   ├── Enums/           # PHP 8.1+ Enums
│   │   ├── Exceptions/      # Custom exceptions
│   │   └── Utils/           # Helpers
│   │
│   └── Infrastructure/      # Technical services
│       ├── Database/        # Migrations, schemas
│       ├── Cache/           # Caching layer
│       ├── Queue/           # Background jobs
│       └── Http/            # HTTP client wrappers
│
├── resources/               # Source files (SCSS, JS source)
├── assets/                  # Compiled CSS/JS (gitignored)
├── templates/               # Theme-overridable templates
├── views/                   # Internal admin views
├── languages/               # i18n files
├── config/                  # DI container, permissions
├── tests/                   # Unit & Integration tests
├── bin/                     # CLI scripts
└── .github/workflows/       # CI/CD
```

## Creating a New Feature

1. Create a new directory under `src/Features/YourFeature/`
2. Create the feature provider: `YourFeatureProvider.php`
3. Add subdirectories as needed: `Admin/`, `Api/`, `Services/`, `Data/`
4. Register the provider in `src/Core/Plugin.php`

### Example Feature Provider

```php
<?php
namespace Starter\Features\YourFeature;

use Starter\Shared\Abstracts\AbstractServiceProvider;

final class YourFeatureProvider extends AbstractServiceProvider
{
    public function register(): void
    {
        $this->addAction('init', [$this, 'init']);
        $this->addAction('rest_api_init', [$this, 'registerRoutes']);
    }
}
```

## Event-Driven Communication

Features communicate via WordPress hooks, not direct service injection:

```php
// In FeatureA - Fire event
do_action('wp_starter_plugin_ticket_created', $dto, $id);

// In FeatureB - Listen to event
add_action('wp_starter_plugin_ticket_created', [$this, 'onTicketCreated'], 10, 2);
```

## Development Commands

```bash
# PHP
composer lint          # Run PHPCS
composer lint:fix      # Fix PHPCS issues
composer test          # Run all tests
composer test:unit     # Run unit tests only
composer analyze       # Run PHPStan

# JavaScript/CSS
npm run start          # Development with watch
npm run build          # Production build
npm run lint:js        # Lint JavaScript
npm run lint:css       # Lint CSS/SCSS
```

## Testing

### Setup WordPress Test Suite

```bash
./bin/install-wp-tests.sh <db-name> <db-user> <db-pass> [db-host] [wp-version]
```

### Run Tests

```bash
composer test              # All tests
composer test:unit         # Unit tests only
composer test:integration  # Integration tests only
```

## Configuration

### DI Container

Edit `config/container.php` to configure dependency injection:

```php
return [
    YourService::class => autowire()
        ->constructorParameter('dependency', get(DependencyClass::class)),
];
```

### Permissions

Edit `config/permissions.php` to configure capabilities:

```php
return [
    'capabilities' => [
        'manage_wp_starter_plugin' => ['administrator'],
    ],
];
```

## Customization

### Renaming the Plugin

1. Rename the plugin directory
2. Update `wp-starter-plugin.php` header
3. Update namespace in `composer.json` (`Starter\` → `YourNamespace\`)
4. Run `composer dump-autoload`
5. Search and replace:
   - `wp-starter-plugin` → `your-plugin-slug`
   - `wp_starter_plugin` → `your_plugin_prefix`
   - `WP_STARTER_PLUGIN` → `YOUR_PLUGIN`
   - `Starter\` → `YourNamespace\`

## Contributing

1. Fork the repository
2. Create a feature branch
3. Make your changes
4. Run tests and linting
5. Submit a pull request

## License

This project is licensed under the GPL-2.0-or-later License.

## Credits

Built with modern WordPress development best practices and inspired by:
- [WordPress Plugin Boilerplate](https://github.com/DevinVinson/WordPress-Plugin-Boilerplate)
- [PHP-DI](https://php-di.org/)
- [WordPress Coding Standards](https://github.com/WordPress/WordPress-Coding-Standards)
