<?php


namespace MyProject\Services;


/**
 * Class AddressingServices
 * @package MyProject\Services
 */
class AddressingServices
{
    /**
     * @param string $collection
     * @param $folder
     * @return string
     */
    public static function imageFolderLink(string $collection, $folder): string
    {
        $imagesPath = '/images/';
        return $_SERVER['DOCUMENT_ROOT'] . $imagesPath . $collection . '/' . $folder;
    }

}