# Export and import translations with ease

[![Latest Version on Packagist](https://img.shields.io/packagist/v/gnahotelsolutions/laravel-i18n-manager.svg?style=flat-square)](https://packagist.org/packages/gnahotelsolutions/laravel-i18n-manager)
[![Build Status](https://img.shields.io/travis/gnahotelsolutions/laravel-i18n-manager/master.svg?style=flat-square)](https://travis-ci.org/gnahotelsolutions/laravel-i18n-manager)
[![Quality Score](https://img.shields.io/scrutinizer/g/gnahotelsolutions/laravel-i18n-manager.svg?style=flat-square)](https://scrutinizer-ci.com/g/gnahotelsolutions/laravel-i18n-manager)
[![Total Downloads](https://img.shields.io/packagist/dt/gnahotelsolutions/laravel-i18n-manager.svg?style=flat-square)](https://packagist.org/packages/gnahotelsolutions/laravel-i18n-manager)

Use this package to export your translations into an easy-to-use CSV format. Also you can export only those texts that are not yet translated in the rest of your locales.

## Installation

You can install the package via composer:

```bash
composer require gnahotelsolutions/laravel-i18n-manager
```

## Usage

Export all translations for all locales based on English:

``` php
php artisan i18n:export --from=en
```

Export only missing translations in Catalan based on English:

```php
php artisan i18n:export --missing --from=en --to=ca
```

Import translations for all locales:

```php
php artisan i18n:import --file=path/to/file.zip
```

> ⚠️ The ZIP file must be in the same format as the exported one. It should contain a folder for each locale and the individual files inside just like the regular `resources/lang` laravel folder

### Testing

``` bash
composer test
```

### Changelog

Please see [CHANGELOG](CHANGELOG.md) for more information what has changed recently.

## Contributing

Please see [CONTRIBUTING](CONTRIBUTING.md) for details.

### Security

If you discover any security related issues, please email dllop@gnahs.com instead of using the issue tracker.

## Credits

- [David Llop](https://github.com/gnahotelsolutions)
- [All Contributors](../../contributors)

## License

The MIT License (MIT). Please see [License File](LICENSE.md) for more information.