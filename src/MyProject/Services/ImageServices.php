<?php


namespace MyProject\Services;


/**
 * Class imageServices
 * @package MyProject\Services\ImagesServices
 */
class ImageServices
{

    /**
     * @param array $image
     * @return bool
     */
    public static function isCorrect(array $image): bool
    {
            switch($image['error']) {
                case UPLOAD_ERR_INI_SIZE:
                    Flasher::set('error', 'The uploaded file exceeds the upload_max_file_size directive in php.ini');
                    break;
                case UPLOAD_ERR_FORM_SIZE:
                    Flasher::set('error', 'The uploaded file exceeds the MAX_FILE_SIZE directive that was specified in the HTML form');
                    break;
                case UPLOAD_ERR_PARTIAL:
                    Flasher::set('error', 'The uploaded file was only partially uploaded');
                    break;
                case UPLOAD_ERR_NO_FILE:
                    Flasher::set('error', 'No file was uploaded');
                    break;
                case UPLOAD_ERR_NO_TMP_DIR:
                    Flasher::set('error', 'Missing a temporary folder');
                    break;
                case UPLOAD_ERR_CANT_WRITE:
                    Flasher::set('error', 'Failed to write file to disk');
                    break;
                case UPLOAD_ERR_EXTENSION:
                    Flasher::set('error', 'File upload stopped by extension');
                    break;
                case UPLOAD_ERR_OK:
                    $allowedExtensions = ['jpg', 'jpeg', 'png', 'gif'];
                    $extension = pathinfo($image['name'], PATHINFO_EXTENSION);
                    $size = getimagesize($image['tmp_name']);
                    if (!in_array($extension, $allowedExtensions)) {
                        Flasher::set('error', 'File extension is not allowed');
                        return false;
                    }
                    elseif ($image['size'] > 20480) {
                        Flasher::set('error', 'File size cannot be more than 20KB');
                        return false;
                    }
                    elseif ($size[0] > 128 || $size[1] > 128) {
                        Flasher::set('error', 'Image size cannot be more than 128*128 px');
                        return false;
                    }
                    return true;
                default:
                    Flasher::set('error', 'Unknown upload error');
                    break;
            }
            return false;
    }

    /**
     * @param string $collection
     * @param int $id
     */
    public static function remove(string $collection, int $id)
    {
        $pathToFile = AddressingServices::imageFolderLink($collection, $id);
        if (!file_exists($pathToFile)) {
            Flasher::set('error', 'Nothing to remove');
        } else {
        $dir = scandir($pathToFile);
        foreach($dir as $file) {
            if ($file === '.' || $file === '..') {
                continue;
            }
            unlink($pathToFile . '/' . $file);
        }
        rmdir($pathToFile);}
    }


    /**
     * @param string $srcUserImageFolder
     * @param $file
     * @param int $w
     * @param int $h
     */
    public static function createMiniature(string $srcUserImageFolder, $file, int $w = 32, int $h = 0)
    {
        $filename = $srcUserImageFolder . '/' . $file;
        $info   = getimagesize($filename);
        $width  = $info[0];
        $height = $info[1];
        $type   = $info[2];

        switch ($type) {
            case 1:
                $img = imageCreateFromGif($filename);
                imageSaveAlpha($img, true);
                break;
            case 2:
                $img = imageCreateFromJpeg($filename);
                break;
            case 3:
                $img = imageCreateFromPng($filename);
                imageSaveAlpha($img, true);
                break;
        }

        if (empty($w)) {
            $w = ceil($h / ($height / $width));
        }
        if (empty($h)) {
            $h = ceil($w / ($width / $height));
        }

        $tmp = imageCreateTrueColor($w, $h);
        if ($type == 1 || $type == 3) {
            imagealphablending($tmp, true);
            imageSaveAlpha($tmp, true);
            $transparent = imagecolorallocatealpha($tmp, 0, 0, 0, 127);
            imagefill($tmp, 0, 0, $transparent);
            imagecolortransparent($tmp, $transparent);
        }

        $tw = ceil($h / ($height / $width));
        $th = ceil($w / ($width / $height));
        if ($tw < $w) {
            imageCopyResampled($tmp, $img, ceil(($w - $tw) / 2), 0, 0, 0, $tw, $h, $width, $height);
        } else {
            imageCopyResampled($tmp, $img, 0, ceil(($h - $th) / 2), 0, 0, $w, $th, $width, $height);
        }

        $img = $tmp;
        switch ($type) {
            case 1:
                imageGif($img, $srcUserImageFolder . '/Mini' . $file);
                break;
            case 2:
                imageJpeg($img, $srcUserImageFolder . '/Mini' . $file, 100);
                break;
            case 3:
                imagePng($img, $srcUserImageFolder . '/Mini' . $file);
                break;
        }

        imagedestroy($img);
    }

    /**
     * @param int $userId
     * @param array $image
     * @return bool
     */
    public static function upload(int $userId, array $image): bool
    {
        $isCorrect = ImageServices::isCorrect($image);
        if ($isCorrect) {
            $pathForUpload = AddressingServices::imageFolderLink('users', $userId);
            $ext = pathinfo($image['name'], PATHINFO_EXTENSION);
            if (file_exists($pathForUpload)) {
                Flasher::set('error', 'You are choosing the same avatar');
                return false;
            }
            mkdir($pathForUpload, 0777);
            $avatarImageFile = $pathForUpload . '/' . $userId. '.' .$ext;
            move_uploaded_file($image['tmp_name'], $avatarImageFile);
            self::createMiniature($pathForUpload, $userId. '.' .$ext);
        }
        return true;
    }
}