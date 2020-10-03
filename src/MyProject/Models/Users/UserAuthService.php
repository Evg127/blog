<?php

namespace MyProject\Models\Users;

use MyProject\Exceptions\DbException;
use MyProject\Exceptions\UnauthorizedException;

/**
 * Class UserAuthService
 * @package MyProject\Models\Users
 */
class UserAuthService
{
    /**
     * @var
     */
    public static $user;
    /**
     * @param User $user
     */
    public static function tokenCreate(User $user)
    {
        $token = $user->getId() .':'. $user->getAuthToken();
        setcookie('token', $token, 0, '/', '', false, true);
    }

    /**
     * @return User|null
     * @throws DbException
     */
    public static function getByToken(): ?User
    {
        if (isset($_COOKIE['token']) && !empty($_COOKIE['token'])) {
            [$id, $token] = explode(':', $_COOKIE['token'], 2);
            $user = User::getById($id);
            if ($user !== null) {
                if ($user->getAuthToken() === $token) {
                    return $user;
                }
            }
        }
        return null;
    }

    /**
     * @throws UnauthorizedException
     */
    public static function tokenDelete()
    {
        if (!isset($_COOKIE['token'])) {
            throw new UnauthorizedException('Unauthorized access');
        }
        setcookie('token', '', 0, '/', '', false, true);
    }
}