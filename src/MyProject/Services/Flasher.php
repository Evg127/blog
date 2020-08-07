<?php


namespace MyProject\Services;


/**
 * Class Flasher
 * @package MyProject\Services
 */
class Flasher
{
    /**
     * @param $key
     * @param $message
     */
    public static function set($key, $message): void
    {
        if (session_status() === PHP_SESSION_NONE) {
            session_start(); // If session is not running yet
        }
        $_SESSION['flash'][$key] = $message;
    }

    /**
     * @param $key
     * @return string
     */
    public static function get($key): string
    {
        if (session_status() === PHP_SESSION_NONE) {
            session_start(); // If session is not running yet
        }
        if (empty($_SESSION['flash'][$key])) {
            return '';
        }
        $message = $_SESSION['flash'][$key];
        unset($_SESSION['flash'][$key]);
        return $message;
    }
}