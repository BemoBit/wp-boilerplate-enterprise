<div align="center">

# 🏗️ WP Boilerplate Enterprise

[![PHP Version](https://img.shields.io/badge/PHP-8.2%2B-777BB4?style=for-the-badge&logo=php&logoColor=white)](https://php.net)
[![WordPress Version](https://img.shields.io/badge/WordPress-6.0%2B-21759B?style=for-the-badge&logo=wordpress&logoColor=white)](https://wordpress.org)
[![License](https://img.shields.io/badge/License-GPL--2.0-green?style=for-the-badge)](LICENSE)
[![PRs Welcome](https://img.shields.io/badge/PRs-welcome-brightgreen.svg?style=for-the-badge)](http://makeapullrequest.com)

**A production-ready WordPress plugin boilerplate with Feature-Based (DDD) architecture.**

*Stop reinventing the wheel. Start building enterprise-grade plugins.*

[Getting Started](#-quick-start) •
[Features](#-features) •
[Documentation](#-directory-structure) •
[Contributing](#-contributing)

</div>

---

## 🎯 Why This Boilerplate?

Building enterprise WordPress plugins shouldn't mean starting from scratch every time. This boilerplate provides:

- **Battle-tested architecture** — Feature-Based (DDD) structure that scales
- **Modern PHP practices** — PHP 8.2+, strict types, dependency injection
- **Developer experience** — Pre-configured linting, testing, and CI/CD
- **WordPress best practices** — Following official coding standards

> 💡 **Perfect for:** Agencies, freelancers, and teams building complex WordPress solutions.

---

## ✨ Features

| Feature | Description |
|---------|-------------|
| 🧩 **Feature-Based Architecture** | Organized by domain features (vertical slicing) for better maintainability |
| 💉 **Dependency Injection** | PHP-DI container for clean, testable code |
| 📦 **PSR-4 Autoloading** | Modern PHP autoloading via Composer |
| ⚡ **PHP 8.2+** | Readonly classes, enums, typed properties, and more |
| 🔍 **Static Analysis** | PHPStan with WordPress extensions |
| ✅ **Testing Ready** | PHPUnit with WordPress test suite integration |
| 🔄 **CI/CD Pipelines** | GitHub Actions for automated testing and releases |
| 📝 **Coding Standards** | PHPCS configured with WordPress Coding Standards |

---

## 📋 Requirements

| Requirement | Version |
|-------------|---------|
| PHP | 8.2 or higher |
| WordPress | 6.0 or higher |
| Composer | 2.x |
| Node.js | 18+ (for asset building) |

---

## 🚀 Quick Start

### 1. Clone the Repository

```bash
cd wp-content/plugins/
git clone https://github.com/BemoBit/wp-boilerplate-enterprise.git
cd wp-boilerplate-enterprise
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

---

## 📁 Directory Structure

```
wp-boilerplate-enterprise/
├── 📄 wp-starter-plugin.php    # Entry point
├── 📄 uninstall.php            # Cleanup on deletion
├── 📄 composer.json            # PHP dependencies
├── 📄 package.json             # JS/CSS build tools
│
├── 📂 src/
│   ├── 📂 Core/                # Plugin bootstrap & infrastructure
│   │   ├── Plugin.php          # Main bootstrapper with DI
│   │   ├── Activator.php       # Activation logic
│   │   ├── Deactivator.php     # Deactivation logic
│   │   └── Kernel.php          # HTTP/CLI kernel
│   │
│   ├── 📂 Features/            # Domain features (vertical slices)
│   │   └── 📂 Example/         # Example feature
│   │       ├── Admin/          # Admin pages
│   │       ├── Api/            # REST API controllers
│   │       ├── Frontend/       # Shortcodes, widgets
│   │       ├── Models/         # Repositories
│   │       ├── Services/       # Business logic
│   │       ├── Data/           # DTOs
│   │       └── ExampleProvider.php
│   │
│   ├── 📂 Shared/              # Cross-cutting concerns
│   │   ├── Contracts/          # Interfaces
│   │   ├── Abstracts/          # Abstract classes
│   │   ├── Traits/             # Reusable traits
│   │   ├── Enums/              # PHP 8.2 Enums
│   │   ├── Exceptions/         # Custom exceptions
│   │   └── Utils/              # Sanitizer, Validator
│   │
│   └── 📂 Infrastructure/      # Technical services
│       ├── Database/           # Migrations, schemas
│       ├── Cache/              # Caching layer (transients)
│       ├── Queue/              # Background jobs (cron)
│       └── Http/               # HTTP client wrappers
│
├── 📂 resources/               # Source SCSS, JS
├── 📂 templates/               # Theme-overridable templates
├── 📂 views/                   # Internal admin views
├── 📂 languages/               # i18n files
├── 📂 config/                  # DI container, permissions
├── 📂 tests/                   # Unit & Integration tests
└── 📂 .github/workflows/       # CI/CD pipelines
```

---

## 🧩 Creating a New Feature

Each feature is a self-contained vertical slice with its own admin, API, frontend, and business logic.

**Steps:**

1. Create a new directory under `src/Features/YourFeature/`
2. Create the feature provider: `YourFeatureProvider.php`
3. Add subdirectories as needed: `Admin/`, `Api/`, `Services/`, `Data/`
4. Register the provider in `src/Core/Plugin.php`

<details>
<summary><strong>📝 Example Feature Provider</strong></summary>

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

</details>

---

## 🔗 Event-Driven Communication

Features communicate via WordPress hooks, not direct service injection. This keeps features decoupled and maintainable.

```php
// In FeatureA - Fire event
do_action('wp_starter_plugin_ticket_created', $dto, $id);

// In FeatureB - Listen to event
add_action('wp_starter_plugin_ticket_created', [$this, 'onTicketCreated'], 10, 2);
```

---

## 🛠️ Development Commands

<table>
<tr><th>Command</th><th>Description</th></tr>
<tr><td><code>composer lint</code></td><td>Run PHP CodeSniffer</td></tr>
<tr><td><code>composer lint:fix</code></td><td>Auto-fix coding standard issues</td></tr>
<tr><td><code>composer test</code></td><td>Run all tests</td></tr>
<tr><td><code>composer test:unit</code></td><td>Run unit tests only</td></tr>
<tr><td><code>composer analyze</code></td><td>Run PHPStan static analysis</td></tr>
<tr><td><code>npm run start</code></td><td>Development with watch</td></tr>
<tr><td><code>npm run build</code></td><td>Production build</td></tr>
<tr><td><code>npm run lint:js</code></td><td>Lint JavaScript</td></tr>
<tr><td><code>npm run lint:css</code></td><td>Lint CSS/SCSS</td></tr>
</table>

---

## 🧪 Testing

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

---

## ⚙️ Configuration

<details>
<summary><strong>DI Container</strong></summary>

Edit `config/container.php` to configure dependency injection:

```php
return [
    YourService::class => autowire()
        ->constructorParameter('dependency', get(DependencyClass::class)),
];
```

</details>

<details>
<summary><strong>Permissions</strong></summary>

Edit `config/permissions.php` to configure capabilities:

```php
return [
    'capabilities' => [
        'manage_wp_starter_plugin' => ['administrator'],
    ],
];
```

</details>

---

## 🎨 Customization

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

---

## 🤝 Contributing

Contributions are welcome! Here's how you can help:

1. Fork the repository
2. Create a feature branch (`git checkout -b feature/amazing-feature`)
3. Make your changes
4. Run tests and linting
5. Commit your changes (`git commit -m 'Add amazing feature'`)
6. Push to the branch (`git push origin feature/amazing-feature`)
7. Open a Pull Request

---

## 📄 License

This project is licensed under the **GPL-2.0-or-later** License. See the [LICENSE](LICENSE) file for details.

---

## 🙏 Credits

Built with modern WordPress development best practices and inspired by:

- [WordPress Plugin Boilerplate](https://github.com/DevinVinson/WordPress-Plugin-Boilerplate)
- [PHP-DI](https://php-di.org/)
- [WordPress Coding Standards](https://github.com/WordPress/WordPress-Coding-Standards)

---

<div align="center">

## ⭐ Support This Project

If this boilerplate saved you time or helped you build something awesome, please consider giving it a **star**!

Your support helps others discover this project and motivates continued development.

[![Star on GitHub](https://img.shields.io/github/stars/BemoBit/wp-boilerplate-enterprise?style=social)](https://github.com/BemoBit/wp-boilerplate-enterprise)

**Made with ❤️ for the WordPress community**

</div>
