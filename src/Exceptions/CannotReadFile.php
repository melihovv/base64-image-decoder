<?php

namespace Melihovv\Base64ImageDecoder\Exceptions;

class CannotReadFile extends CodingFailedException
{
    public static function create($fileName, $code = 0, $previous = null)
    {
        return new self("Unable to read file [$fileName]", $code, $previous);
    }
}
