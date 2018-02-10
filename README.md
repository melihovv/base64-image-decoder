# Base64 Image Decoder

[![Build Status](https://travis-ci.org/melihovv/base64-image-decoder.svg?branch=master)](https://travis-ci.org/melihovv/base64-image-decoder)
[![styleci](https://styleci.io/repos/CHANGEME/shield)](https://styleci.io/repos/CHANGEME)
[![Scrutinizer Code Quality](https://scrutinizer-ci.com/g/melihovv/base64-image-decoder/badges/quality-score.png?b=master)](https://scrutinizer-ci.com/g/melihovv/base64-image-decoder/?branch=master)
[![SensioLabsInsight](https://insight.sensiolabs.com/projects/CHANGEME/mini.png)](https://insight.sensiolabs.com/projects/CHANGEME)
[![Coverage Status](https://coveralls.io/repos/github/melihovv/base64-image-decoder/badge.svg?branch=master)](https://coveralls.io/github/melihovv/base64-image-decoder?branch=master)

[![Packagist](https://img.shields.io/packagist/v/melihovv/base64-image-decoder.svg)](https://packagist.org/packages/melihovv/base64-image-decoder)
[![Packagist](https://poser.pugx.org/melihovv/base64-image-decoder/d/total.svg)](https://packagist.org/packages/melihovv/base64-image-decoder)
[![Packagist](https://img.shields.io/packagist/l/melihovv/base64-image-decoder.svg)](https://packagist.org/packages/melihovv/base64-image-decoder)

Small class to easily decode base64 encoded image.
Useful when you need to upload image via ajax.

## Installation

Install via composer
```bash
composer require melihovv/base64-image-decoder
```

## Usage

```php
$base64EncodedImage = ; // image may come from http request or any other source.

// We check that image is encoded properly in constructor, otherwise exception will be thrown.
// You can use this info in your validation rule.
$decoder = new Base64ImageDecoder($base64EncodedImage, $allowedFormats = ['jpeg', 'png', 'gif']);

$decoder->getFormat(); // 'png', or 'jpeg', or 'gif', or etc.
$decoder->getDecodedContent(); // base64 decoded raw image bytes.
$decoder->getContent(); // base64 encoded raw image bytes.
```

## Security

If you discover any security related issues, please email amelihovv@ya.ru
instead of using the issue tracker.

## Credits

- [Alexander Melihov](https://github.com/melihovv/base64-image-decoder)
- [All contributors](https://github.com/melihovv/base64-image-decoder/graphs/contributors)

This package is bootstrapped with [melihovv/laravel-package-generator](https://github.com/melihovv/laravel-package-generator).
