# Richie

[![Latest Version on Packagist](https://img.shields.io/packagist/v/awcodes/richie.svg?style=flat-square)](https://packagist.org/packages/awcodes/richie)
[![GitHub Tests Action Status](https://img.shields.io/github/actions/workflow/status/awcodes/richie/run-tests.yml?branch=main&label=tests&style=flat-square)](https://github.com/awcodes/richie/actions?query=workflow%3Arun-tests+branch%3Amain)
[![GitHub Code Style Action Status](https://img.shields.io/github/actions/workflow/status/awcodes/richie/fix-php-code-styling.yml?branch=main&label=code%20style&style=flat-square)](https://github.com/awcodes/richie/actions?query=workflow%3A"Fix+PHP+code+styling"+branch%3Amain)
[![Total Downloads](https://img.shields.io/packagist/dt/awcodes/richie.svg?style=flat-square)](https://packagist.org/packages/awcodes/richie)

Richie is just another rich text editor for Filament PHP.

## Installation

You can install the package via composer:

```bash
composer require awcodes/richie
```

You can publish the config file with:

```bash
php artisan vendor:publish --tag="richie-config"
```

This is the contents of the published config file:

```php
return [
];
```

## Usage

```php
$richie = new Awcodes\Typist();
echo $richie->echoPhrase('Hello, Awcodes!');
```

## Testing

```bash
composer test
```

## Changelog

Please see [CHANGELOG](CHANGELOG.md) for more information on what has changed recently.

## Contributing

Please see [CONTRIBUTING](.github/CONTRIBUTING.md) for details.

## Security Vulnerabilities

Please review [our security policy](../../security/policy) on how to report security vulnerabilities.

## Credits

- [Adam Weston](https://github.com/awcodes)
- [All Contributors](../../contributors)

## License

The MIT License (MIT). Please see [License File](LICENSE.md) for more information.
