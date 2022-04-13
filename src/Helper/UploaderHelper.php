<?php

namespace App\Helper;

use Symfony\Component\Filesystem\Filesystem;
use Symfony\Component\HttpFoundation\File\UploadedFile;
use RecursiveDirectoryIterator;
use FilesystemIterator;
use RecursiveIteratorIterator;
use Symfony\Component\Filesystem\Exception\IOExceptionInterface;
use Symfony\Component\HttpFoundation\Session\Flash\FlashBagInterface;

class UploaderHelper
{
    private $filesystem;
    private $flashBag;

    public function __construct(Filesystem $filesystem, FlashBagInterface $flashBag)
    {
        $this->filesystem = $filesystem;
        $this->flashBag = $flashBag;
    }

    public function deleteUploadedFile(string $path, string $id)
    {
        try {
            $this->filesystem->remove($path . $id . ".png");
            if (file_exists($path . $id . "/")) {
                $di = new RecursiveDirectoryIterator($path . $id . "/", FilesystemIterator::SKIP_DOTS);
                $ri = new RecursiveIteratorIterator($di, RecursiveIteratorIterator::CHILD_FIRST);
                foreach ($ri as $file) {
                    $file->isDir() ?  $this->filesystem->remove($file) : $this->filesystem->remove($file);
                }
                $this->filesystem->remove($path . $id . "/");
                $this->filesystem->remove("favicon-" . $id . ".zip");
            }
        } catch (IOExceptionInterface $e) {
            $this->flashBag->add("generator_error", $e->getMessage());
        }
    }

    public function uploadFile(string $imgPath, UploadedFile $uploadedfile): ?string
    {
        try {
            $id = uniqid();
            $newImageFilename = $id . ".png";
            $uploadedfile->move($imgPath, $newImageFilename);
            $size = getimagesize($imgPath . $newImageFilename);
            list($height, $width) = $size;
            if ($height != $width) {
                $this->deleteUploadedFile($imgPath, $id);
                $this->flashBag->add("generator_error", "The height and width of the image must be equal.");
                return null;
            }
            return $id;
        } catch (IOExceptionInterface $e) {
            $this->flashBag->add("generator_error", $e->getMessage());
            return null;
        }
    }
}
