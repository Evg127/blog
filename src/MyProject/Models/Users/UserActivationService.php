<?php


namespace MyProject\Models\Users;


use MyProject\Exceptions\DbException;
use MyProject\Exceptions\InvalidArgumentException;
use MyProject\Services\Db;

/**
 * Class UserActivationService
 * @package MyProject\Models\Users
 */
class UserActivationService
{
    /**
     *
     */
    private const TABLE_NAME = 'users_activation_codes';

    /**
     * @param User $user
     * @return string
     * @throws DbException
     */
    public static function activationCodeCreate(User $user): string
    {
        $code = bin2hex(random_bytes(16));
        $db = Db::getDb();
        $sql = 'INSERT INTO `'.self::TABLE_NAME.'`  SET user_id=:user_id, code=:code;';
        $db->queryWithoutGettingData($sql, [':user_id' => $user->getId(), ':code' => $code]);
        return $code;
    }

    /**
     * @param int $userId
     * @param string $code
     * @return bool
     * @throws DbException
     */
    public static function activationCodeCheck(int $userId, string $code): bool
    {
        $db = Db::getDb();
        $sql = 'SELECT * FROM `' . self::TABLE_NAME . '`  WHERE user_id=:user_id AND code=:code;';
        $result = $db->queryWithGettingData($sql, [':user_id' => $userId, ':code' => $code]);
        return !empty($result);
    }

    /**
     * @param int $userId
     * @throws DbException
     */
    public static function activationCodeDelete(int $userId)
    {
        $db = Db::getDb();
        $sql = 'DELETE  FROM `' . self::TABLE_NAME . '`  WHERE user_id=:user_id;';
        $db->queryWithoutGettingData($sql, [':user_id' => $userId]);
    }

    /**
     * @param int $userId
     * @throws DbException
     */
    public static function activate(int $userId): void
    {
        $user = User::getById($userId);
        $user->setIsConfirmed(1);
        $user->save();
    }

    /**
     * @param array $dataFromActivationChangeForm
     * @param User $userById
     * @return User
     * @throws DbException
     * @throws InvalidArgumentException
     */
    public static function activationStatusChange(array $dataFromActivationChangeForm, User $userById)
    {
        if(!isset($dataFromActivationChangeForm['activation'])) {
            throw new InvalidArgumentException('activation status option is not passed');
        }
        if ($dataFromActivationChangeForm['activation'] === 'activate') {
            if ($userById->getIsConfirmed()) {
                throw new InvalidArgumentException('This activation status is already set as activated');
            }
            $userById->setIsConfirmed(1);
            $userById->save();
        }
        if ($dataFromActivationChangeForm['activation'] === 'deactivate') {
            if (!$userById->getIsConfirmed()) {
                throw new InvalidArgumentException('This activation status is already set as deactivated');
            }
            $userById->setIsConfirmed(0);
            $userById->save();
        }
        return $userById;
    }
}
