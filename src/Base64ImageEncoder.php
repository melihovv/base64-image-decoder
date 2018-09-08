<?php

namespace Melihovv\Base64ImageDecoder;

use finfo;
use InvalidArgumentException;
use Melihovv\Base64ImageDecoder\Exceptions\InvalidFormat;
use Melihovv\Base64ImageDecoder\Exceptions\CannotReadFile;
use Melihovv\Base64ImageDecoder\Exceptions\CannotEncodeToBase64;

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

    /**
     * @param string $mimeType
     * @param array $allowedFormats
     * @throws InvalidFormat
     */
    private static function validate(string $mimeType, array $allowedFormats)
    {
        $format = strtr($mimeType, ['image/' => '']);

        if (! in_array($format, $allowedFormats, true)) {
            throw InvalidFormat::create($allowedFormats, $format);
        }
    }

    /**
     * @param string $content
     * @return string
     * @throws CannotEncodeToBase64
     */
    private static function encode(string $content): string
    {
        $encoded = base64_encode($content);

        if (false === $encoded) {
            throw CannotEncodeToBase64::create();
        }

        return $encoded;
    }

    /**
     * @param string $binaryData
     * @param array $allowedFormats
     * @return Base64ImageEncoder
     */
    public static function fromBinaryData(
        string $binaryData,
        array $allowedFormats = self::DEFAULT_ALLOWED_FORMATS
    ) : self {
        $finfo = new finfo(FILEINFO_MIME_TYPE);
        $mimeType = $finfo->buffer($binaryData);

        self::validate($mimeType, $allowedFormats);

        return new static($mimeType, self::encode($binaryData));
    }

    /**
     * @param string $fileName
     * @param array $allowedFormats
     * @return Base64ImageEncoder
     */
    public static function fromFileName(
        string $fileName,
        array $allowedFormats = self::DEFAULT_ALLOWED_FORMATS
    ) : self {
        if (! is_readable($fileName)) {
            throw CannotReadFile::create($fileName);
        }

        $mimeType = mime_content_type($fileName);
        self::validate($mimeType, $allowedFormats);

        return new static($mimeType, self::encode(file_get_contents($fileName)));
    }

    /**
     * @param resource $handle
     * @param array $allowedFormats
     * @return Base64ImageEncoder
     * @throws InvalidArgumentException
     */
    public static function fromResource($handle, array $allowedFormats = self::DEFAULT_ALLOWED_FORMATS): self
    {
        if (! is_resource($handle)) {
            $message = sprintf('Expected resource, got %s', is_object($handle) ? get_class($handle) : gettype($handle));
            throw new InvalidArgumentException($message);
        }

        return self::fromBinaryData(stream_get_contents($handle), $allowedFormats);
    }

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
