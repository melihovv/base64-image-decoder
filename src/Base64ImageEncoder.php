<?php

namespace Melihovv\Base64ImageEncoder;

use Melihovv\Base64ImageDecoder\Exceptions\InvalidFormat;
use Melihovv\Base64ImageDecoder\Exceptions\NotBase64Encoding;

class Base64ImageEncoder {

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

    private function mime_content_type($filename) {

        $mime_types = array(
            'txt' => 'text/plain',
            'htm' => 'text/html',
            'html' => 'text/html',
            'php' => 'text/html',
            'css' => 'text/css',
            'js' => 'application/javascript',
            'json' => 'application/json',
            'xml' => 'application/xml',
            'swf' => 'application/x-shockwave-flash',
            'flv' => 'video/x-flv',
            // images
            'png' => 'image/png',
            'jpe' => 'image/jpeg',
            'jpeg' => 'image/jpeg',
            'jpg' => 'image/jpeg',
            'gif' => 'image/gif',
            'bmp' => 'image/bmp',
            'ico' => 'image/vnd.microsoft.icon',
            'tiff' => 'image/tiff',
            'tif' => 'image/tiff',
            'svg' => 'image/svg+xml',
            'svgz' => 'image/svg+xml',
            // archives
            'zip' => 'application/zip',
            'rar' => 'application/x-rar-compressed',
            'exe' => 'application/x-msdownload',
            'msi' => 'application/x-msdownload',
            'cab' => 'application/vnd.ms-cab-compressed',
            // audio/video
            'mp3' => 'audio/mpeg',
            'qt' => 'video/quicktime',
            'mov' => 'video/quicktime',
            // adobe
            'pdf' => 'application/pdf',
            'psd' => 'image/vnd.adobe.photoshop',
            'ai' => 'application/postscript',
            'eps' => 'application/postscript',
            'ps' => 'application/postscript',
            // ms office
            'doc' => 'application/msword',
            'rtf' => 'application/rtf',
            'xls' => 'application/vnd.ms-excel',
            'ppt' => 'application/vnd.ms-powerpoint',
            // open office
            'odt' => 'application/vnd.oasis.opendocument.text',
            'ods' => 'application/vnd.oasis.opendocument.spreadsheet',
        );
        $ext = explode('.', $filename);
        $ext = strtolower($ext[count($ext) - 1]);
        if (array_key_exists($ext, $mime_types)) {
            return $mime_types[$ext];
        } elseif (function_exists('finfo_open')) {
            $finfo = finfo_open(FILEINFO_MIME);
            $mimetype = finfo_file($finfo, $filename);
            finfo_close($finfo);
            return $mimetype;
        } else {
            return 'application/octet-stream';
        }
    }

    /**
     * 
     * @param string $filename
     * @param type $allowedFormats
     */
    public function __construct(string $filename, $allowedFormats = ['jpeg', 'png', 'gif']) {
        $binary = file_get_contents($filename);
        $mime_type = $this->mime_content_type($filename);
        $this->base64EncodedImage = 'data:' . $mime_type . ';base64,' . base64_encode($binary);
        $this->allowedFormats = $allowedFormats;

        $this->validate();
    }

    private function validate() {
        $parts = explode(',', $this->base64EncodedImage);
        $this->format = str_replace(['data:image/', ';', 'base64'], ['', '', ''], $parts[0] ?? '');
        $this->content = $parts[1] ?? '';

        if (!preg_match('%^[a-zA-Z0-9/+]*={0,2}$%', $this->content)) {
            throw NotBase64Encoding::create();
        }

        if (!in_array($this->format, $this->allowedFormats, true)) {
            throw InvalidFormat::create($this->allowedFormats, $this->format);
        }
    }

    public function getFormat(): string {
        return $this->format;
    }

    public function getContent(): string {
        return $this->content;
    }

    public function getBase64() {
        return $this->base64EncodedImage;
    }

    public function getDecodedContent(): string {
        return base64_decode($this->content);
    }

}
