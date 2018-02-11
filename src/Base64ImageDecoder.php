<?php

namespace Melihovv\Base64ImageDecoder;

use Melihovv\Base64ImageDecoder\Exceptions\InvalidFormat;
use Melihovv\Base64ImageDecoder\Exceptions\NotBase64Encoding;

class Base64ImageDecoder
{
    /**
     * @var string
     */
    private $base64EncodedImage;

    /**
     * @var array
     */
    private $allowedFormats;

    /**
     * @var string
     */
    private $format;

    /**
     * @var string
     */
    private $content;

    public function __construct(string $base64EncodedImage, array $allowedFormats = ['jpeg', 'png', 'gif'])
    {
        $this->base64EncodedImage = $base64EncodedImage;
        $this->allowedFormats = $allowedFormats;

        $this->validate();
    }

    private function validate()
    {
        $parts = explode(',', $this->base64EncodedImage);
        $this->format = str_replace(['data:image/', ';', 'base64'], ['', '', ''], $parts[0] ?? '');
        $this->content = $parts[1] ?? '';

        if (! preg_match('%^[a-zA-Z0-9/+]*={0,2}$%', $this->content)) {
            throw NotBase64Encoding::create();
        }

        if (! in_array($this->format, $this->allowedFormats, true)) {
            throw InvalidFormat::create($this->allowedFormats, $this->format);
        }
    }

    public function getFormat() : string
    {
        return $this->format;
    }

    public function getContent() : string
    {
        return $this->content;
    }

    public function getDecodedContent() : string
    {
        return base64_decode($this->content);
    }
}
