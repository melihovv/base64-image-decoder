<?php

namespace Melihovv\Base64ImageDecoder\Exceptions;

class InvalidFormat extends CodingFailedException
{
    public static function create($allowedFormats, $actualFormat, $code = 0, $previous = null)
    {
        return new self(
            "Image format [$actualFormat] is not in one of allowed formats: ".implode(', ', $allowedFormats),
            $code,
            $previous
        );
    }
}
