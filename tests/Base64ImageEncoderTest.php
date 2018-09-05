<?php

namespace Melihovv\Base64ImageDecoder\Tests;

use Melihovv\Base64ImageDecoder\Base64ImageEncoder;
use PHPUnit\Framework\TestCase;

class Base64ImageEncoderTest extends TestCase
{

    /**
     * @test
     */
    public function it_encodes_a_file_to_base64()
    {
        $fileName = $this->generateTmpPngImage();

        if (0 === filesize($fileName) || !is_readable($fileName)) {
            throw new \RuntimeException("Failed reading tmp file.");
        }

        $content = file_get_contents($fileName);
        $encoder = Base64ImageEncoder::fromFileName($fileName);
        $this->assertEquals(base64_encode($content), $encoder->getContent());
        $this->assertEquals('image/png', $encoder->getMimeType());
        $this->assertEquals('data:image/png;base64,' . base64_encode($content), $encoder->getDataUri());
    }

    /**
     * @test
     */
    public function it_encodes_content_to_base64()
    {
        $fileName = $this->generateTmpPngImage();

        if (0 === filesize($fileName) || !is_readable($fileName)) {
            throw new \RuntimeException("Failed reading tmp file.");
        }

        $content = file_get_contents($fileName);
        $encoder = Base64ImageEncoder::fromBinaryData($content);
        $this->assertEquals(base64_encode($content), $encoder->getContent());
        $this->assertEquals('image/png', $encoder->getMimeType());
        $this->assertEquals('data:image/png;base64,' . base64_encode($content), $encoder->getDataUri());
    }

    /**
     * @test
     */
    public function it_encodes_a_resource_to_base64()
    {
        $fileName = $this->generateTmpPngImage();

        if (0 === filesize($fileName) || !is_readable($fileName)) {
            throw new \RuntimeException("Failed reading tmp file.");
        }

        $handle = fopen($fileName, 'r');
        $encoder = Base64ImageEncoder::fromResource($handle);
        $expectedContent = Base64ImageEncoder::fromFileName($fileName)->getContent();
        $this->assertEquals($expectedContent, $encoder->getContent());
        $this->assertEquals('image/png', $encoder->getMimeType());
        $this->assertEquals('data:image/png;base64,' . $expectedContent, $encoder->getDataUri());
    }

    /**
     * @test
     * @expectedException \Melihovv\Base64ImageDecoder\Exceptions\InvalidFormat
     */
    public function it_throws_error_if_image_is_in_invalid_format()
    {
        $fileName = $this->generateTmpPngImage();

        if (0 === filesize($fileName) || !is_readable($fileName)) {
            throw new \RuntimeException("Failed reading tmp file.");
        }
        Base64ImageEncoder::fromFileName($fileName, ['jpeg']);
    }

    public function generateTmpPngImage($width = 10, $height = 10) : string
    {
        $fileName = tempnam(sys_get_temp_dir(), 'b64');

        if (!is_writable($fileName)) {
            throw new \RuntimeException("Failed writing in tmp file.");
        }

        imagepng(imagecreatetruecolor($width, $height), $fileName);

        return $fileName;
    }

}
