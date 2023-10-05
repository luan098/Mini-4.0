<?php

namespace Mini\utils;

use Intervention\Image\ImageManagerStatic as Image;

/**
 * Manipula imagens e salva no path, você pode ajustar width, height, quality, e até mesmo o format 
 * 
 */
class ImageManipulator
{
    /**
     * Caminho do arquivo com o nome e extensão dele juntos
     *
     * @var string
     */
    private $sourcePath;

    /**
     * Onde o arquivo vai ser transferido com o nome e extensão dele
     *
     * @var string
     */
    private $destinationPath;
    private $width;
    private $height;
    private $quality;
    private $format;

    public function __construct($sourcePath, $destinationPath, $width = null, $height = null, $quality = 90, $format = null)
    {
        $this->sourcePath = $sourcePath;
        $this->destinationPath = $destinationPath;
        $this->width = $width;
        $this->height = $height;
        $this->quality = $quality;
        $this->format = $format;
    }

    public function save()
    {
        try {
            $image = Image::make($this->sourcePath);

            if ($this->width !== null || $this->height !== null) {
                $image->resize($this->width, $this->height, function ($constraint) {
                    $constraint->aspectRatio();
                    $constraint->upsize();
                });
            }

            $image->encode(null, $this->quality);

            if ($this->format !== null) {
                $image->encode($this->format);
            }

            $image->save($this->destinationPath);

            return true;
        } catch (\Exception $e) {
            return false;
        }
    }
}
