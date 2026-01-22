# CSRF Refresh for Laravel

Automatic CSRF token refresh for Laravel applications to prevent token expiration on long-lived pages.

## Installation

Install the package via Composer:

```bash
composer require brunoabpinto/csrf-refresh
```

The package will automatically register its service provider via Laravel's package auto-discovery.

## Usage

Add the `@csrfRefresh` Blade directive to your layout file, typically in the `<head>` section or before the closing `</body>` tag:

```blade
<!DOCTYPE html>
<html>
<head>
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <!-- other head elements -->
</head>
<body>
    <!-- your content -->

    @csrfRefresh
</body>
</html>
```

## How It Works

1. The package registers a route at `/csrf-token/refresh` that returns a fresh CSRF token
2. A JavaScript file is automatically published to `public/vendor/csrf-refresh/`
3. The script periodically fetches a new CSRF token and updates the `<meta name="csrf-token">` element
4. The refresh interval is calculated based on your session lifetime configuration (refreshes 50 seconds before expiration)

## Publishing Assets

The JavaScript file is automatically published on first boot. To manually update the assets after a package update:

```bash
php artisan vendor:publish --tag=csrf-refresh-assets --force
```

## Requirements

- PHP 8.1 or higher
- Laravel 10.x or 11.x

## License

The MIT License (MIT). Please see [License File](LICENSE) for more information.
