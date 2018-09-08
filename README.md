# Base64 Image Decoder

[![Build Status](https://travis-ci.org/melihovv/base64-image-decoder.svg?branch=master)](https://travis-ci.org/melihovv/base64-image-decoder)
[![styleci](https://styleci.io/repos/121083762/shield)](https://styleci.io/repos/121083762)
[![Scrutinizer Code Quality](https://scrutinizer-ci.com/g/melihovv/base64-image-decoder/badges/quality-score.png?b=master)](https://scrutinizer-ci.com/g/melihovv/base64-image-decoder/?branch=master)
[![SensioLabsInsight](https://insight.sensiolabs.com/projects/1ba39d70-b4f8-4e78-9f65-dfba75f30cf5/mini.png)](https://insight.sensiolabs.com/projects/1ba39d70-b4f8-4e78-9f65-dfba75f30cf5)
[![Coverage Status](https://coveralls.io/repos/github/melihovv/base64-image-decoder/badge.svg?branch=master)](https://coveralls.io/github/melihovv/base64-image-decoder?branch=master)

[![Packagist](https://img.shields.io/packagist/v/melihovv/base64-image-decoder.svg)](https://packagist.org/packages/melihovv/base64-image-decoder)
[![Packagist](https://poser.pugx.org/melihovv/base64-image-decoder/d/total.svg)](https://packagist.org/packages/melihovv/base64-image-decoder)
[![Packagist](https://img.shields.io/packagist/l/melihovv/base64-image-decoder.svg)](https://packagist.org/packages/melihovv/base64-image-decoder)

A small set of classes (decoder, encoder) to work with images as data-uris.

## Installation

Install via composer
```bash
composer require melihovv/base64-image-decoder
```

## Usage

### Encoder

```php
use Melihovv\Base64ImageDecoder\Base64ImageEncoder;

$encoder = Base64ImageEncoder::fromFileName('/path/to/picture.jpg', $allowedFormats = ['jpeg', 'png', 'gif']);
#$encoder = Base64ImageEncoder::fromBinaryData($someRawBinaryData, $allowedFormats = ['jpeg', 'png', 'gif']);
#$encoder = Base64ImageEncoder::fromResource($someResource, $allowedFormats = ['jpeg', 'png', 'gif']);

$encoder->getMimeType(); // image/jpeg for instance
$encoder->getContent(); // base64 encoded image bytes.
$encoder->getDataUri(); // a base64 data-uri to use in HTML or CSS attributes.
```

### Decoder

```php
use Melihovv\Base64ImageDecoder\Base64ImageDecoder;

$dataUri = 'data:image/gif;base64,R0lGODlhLAH6AOZ/AMyokXJMK0uE...'; // image may come from http request or any other source.

// We check that image is encoded properly in constructor, otherwise exception will be thrown.
// You can use this info in your validation rule.
$decoder = new Base64ImageDecoder($dataUri, $allowedFormats = ['jpeg', 'png', 'gif']);

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
