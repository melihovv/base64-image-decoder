<?php

namespace Melihovv\Base64ImageDecoder\Tests;

use Melihovv\Base64ImageDecoder\Base64ImageDecoder;
use PHPUnit\Framework\TestCase;

class Base64ImageDecoderTest extends TestCase
{
    /** @test */
    public function it_decodes_base64_encoded_image()
    {
        list($base64EncodedImage, $rawImage) = $this->generateBase64EncodedPngImage();

        $decoder = new Base64ImageDecoder($base64EncodedImage, ['jpeg', 'png', 'gif']);
        $this->assertEquals('png', $decoder->getFormat());
        $this->assertEquals(base64_encode($rawImage), $decoder->getContent());
        $this->assertEquals($rawImage, $decoder->getDecodedContent());
    }

    /**
     * @test
     * @expectedException \Melihovv\Base64ImageDecoder\Exceptions\InvalidFormat
     */
    public function it_throws_error_if_image_is_in_invalid_format()
    {
        list($base64EncodedImage) = $this->generateBase64EncodedPngImage();

        new Base64ImageDecoder($base64EncodedImage, ['jpeg']);
    }

    /**
     * @test
     * @expectedException \Melihovv\Base64ImageDecoder\Exceptions\NotBase64Encoding
     */
    public function it_throws_error_if_image_is_not_base64_encoded()
    {
        new Base64ImageDecoder('data:image/png;base64,not-valid-base64-encoding', ['jpeg', 'png', 'gif']);
    }

    public function generateBase64EncodedPngImage($width = 10, $height = 10) : array
    {
        ob_start();

        imagepng(imagecreatetruecolor($width, $height));

        $rawImage = ob_get_clean();

        return ['data:image/png;base64,' . base64_encode($rawImage), $rawImage];
    }
}
