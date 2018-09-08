<?php

namespace Melihovv\Base64ImageDecoder;

use Melihovv\Base64ImageDecoder\Exceptions\InvalidFormat;

class Base64ImageEncoder
{
    const DEFAULT_ALLOWED_FORMATS = [
        'jpeg',
        'png',
        'gif',
    ];

    /**
     * @var string
     */
    private $mimeType;

    /**
     * @var string
     */
    private $base64;

    private function __construct(string $mimeType, string $base64)
    {
        $this->mimeType = $mimeType;
        $this->base64 = $base64;
    }

    private static function validate(string $mimeType, array $allowedFormats)
    {
        $format = strtr($mimeType, ['image/' => '']);
        if (! in_array($format, $allowedFormats, true)) {
            throw InvalidFormat::create($allowedFormats, $format);
        }
    }

    private static function encode(string $content): string
    {
        $encoded = base64_encode($content);
        if (false === $encoded) {
            throw new \RuntimeException('Failed encoding in base 64.');
        }

        return $encoded;
    }

    public static function fromBinaryData(string $binaryData, array $allowedFormats = self::DEFAULT_ALLOWED_FORMATS): self
    {
        $finfo = new \finfo(FILEINFO_MIME_TYPE);
        $mimeType = $finfo->buffer($binaryData);
        self::validate($mimeType, $allowedFormats);

        return new static($mimeType, self::encode($binaryData));
    }

    public static function fromFileName(string $fileName, array $allowedFormats = self::DEFAULT_ALLOWED_FORMATS): self
    {
        if (! is_readable($fileName)) {
            throw new \InvalidArgumentException(sprintf('Unable to read file %s', $fileName));
        }
        $mimeType = mime_content_type($fileName);
        self::validate($mimeType, $allowedFormats);

        return new static($mimeType, self::encode(file_get_contents($fileName)));
    }

    /**
     * @param       $handle
     * @param array $allowedFormats
     * @return Base64ImageEncoder
     * @throws \InvalidArgumentException
     */
    public static function fromResource($handle, array $allowedFormats = self::DEFAULT_ALLOWED_FORMATS): self
    {
        if (! is_resource($handle)) {
            throw new \InvalidArgumentException(sprintf('Expected resource, got %s', is_object($handle) ? get_class($handle) : gettype($handle)));
        }

        return self::fromBinaryData(stream_get_contents($handle), $allowedFormats);
    }

    /**
     * @return string
     */
    public function getMimeType(): string
    {
        return $this->mimeType;
    }

    public function getContent(): string
    {
        return $this->base64;
    }

    public function getDataUri(): string
    {
        return 'data:'.$this->mimeType.';base64,'.$this->base64;
    }
}
