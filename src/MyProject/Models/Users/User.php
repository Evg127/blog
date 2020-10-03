<?php

namespace MyProject\Models\Users;

use DateTime;
use Exception;
use Imagick;
use MyProject\Models\ActiveRecordEntity;
use MyProject\Exceptions\InvalidArgumentException;
use MyProject\Exceptions\DbException;
use MyProject\Services\AddressingServices;
use MyProject\Services\ImageServices;

/**
 * Class User
 * @package MyProject\Models\Users
 */
class User extends ActiveRecordEntity
{
    /** @var string */
    protected $nickname;

    /** @var string */
    protected $email;

    /** @var int */
    protected $isConfirmed;

    /** @var string */
    protected $role;

    /** @var string */
    protected $passwordHash;

    /** @var string */
    protected $authToken;

    /** @var string */
    protected $createdAt;

    /** @var string */
    protected $lastVisitTime;

    /** @var string */
    protected $signature;

    /**
     * @return string
     */
    public function getSignature(): string
    {
        return $this->signature;
    }

    /**
     * @param string $signature
     */
    public function setSignature(string $signature): void
    {
        $this->signature = $signature;
    }

    /**
     * @return string
     */
    public function getEmail(): string
    {
        return $this->email;
    }

    /**
     * @return string
     */
    public function getCreatedAt(): string
    {
        return $this->createdAt;
    }

    /**
     * @return int
     */
    public function getIsConfirmed(): int
    {
        return $this->isConfirmed;
    }

    /**
     * @param int $isConfirmed
     */
    public function setIsConfirmed(int $isConfirmed): void
    {
        $this->isConfirmed = $isConfirmed;
    }

    /**
     * @return string
     */
    public function getNickname(): string
    {
        return $this->nickname;
    }

    /**
     * @return string
     */
    public function getAuthToken(): string
    {
        return $this->authToken;
    }

    /**
     * @return string
     */
    public static function getTableName(): string
    {
        return 'users';
    }

    /**
     * @return string
     */
    public function getRole(): string
    {
        return $this->role;
    }

    /**
     * @param string $role
     */
    public function setRole(string $role): void
    {
        $this->role = $role;
    }

    /**
     * @return string
     */
    public function getPasswordHash(): string
    {
        return $this->passwordHash;
    }

    /**
     * @return string
     */
    public function getLastVisitTime(): string
    {
        return $this->lastVisitTime;
    }

    /**
     * @param DateTime $lastVisitTime
     */
    public function setLastVisitTime($lastVisitTime)
    {
        $this->lastVisitTime = $lastVisitTime->format('Y-m-d H:i:s');
    }

    public function isAdmin(): bool
    {
        return $this->role === 'admin';
    }

    /**
     * @param array $userData
     * @return User
     * @throws InvalidArgumentException
     * @throws DbException
     */
    public static function registration(array $userData): User
    {
        if (!isset($userData['nickname'])) {
            throw new InvalidArgumentException('Nickname is not passed');
        }
        if (!preg_match('~^[A-Za-z0-9]{3,}$~', $userData['nickname'])) {
            throw new InvalidArgumentException('Nickname must be of more than three only numbers and latin letters');
        }
        if (!isset($userData['email'])) {
            throw new InvalidArgumentException('email is not passed');
        }
        if (!filter_var($userData['email'],FILTER_VALIDATE_EMAIL)) {
            throw new InvalidArgumentException('Email is not correct');
        }
        if (empty($userData['password'])) {
            throw new InvalidArgumentException('password is not passed');
        }
        if (!preg_match('~^[A-Za-z0-9]{8,}$~', $userData['password'])) {
            throw new InvalidArgumentException('Password must be min 8 symbols of numbers and latin letters');
        }
        if(static::getOneByColumn('nickname', $userData['nickname']) !== null) {
            throw new InvalidArgumentException('User ' . $userData['nickname'] . ' already exists');
        }
        if (static::getOneByColumn('email', $userData['email']) !== null) {
            throw new InvalidArgumentException('User with email ' . $userData['email'] . 'already exists');
        }
        $user = new User();
        $user->nickname = $userData['nickname'];
        $user->email = $userData['email'];
        $user->passwordHash = password_hash($userData['password'], PASSWORD_DEFAULT);
        $user->authToken = sha1(random_bytes(100)) . sha1(random_bytes(100));
        $user->save();
        return $user;
    }

    /**
     * @param array $loginData
     * @return User
     * @throws InvalidArgumentException
     * @throws DbException
     * @throws Exception
     */
    public static function login(array $loginData): User
    {
        if (!isset($loginData['email'], $loginData['password'])) {
            throw new InvalidArgumentException('Email not passed');
        }
        if (!isset($loginData['password'])) {
            throw new InvalidArgumentException('Password not passed');
        }
        $userFromDb = User::getOneByColumn('email', $loginData['email']);
        if ($userFromDb === null) {
            throw new InvalidArgumentException('User with this email is not existed');
        }
        if (!password_verify($loginData['password'], $userFromDb->getPasswordHash())) {
            throw new InvalidArgumentException('Password does not match entered email');
        }
        if (!$userFromDb->isConfirmed) {
            throw new InvalidArgumentException('User ' . $userFromDb->getNickname() . ' is not activated yet');
        }
        $userFromDb->refreshAuthToken();
        $userFromDb->save();
        return $userFromDb;
    }

    /**
     * @throws Exception
     */
    private function refreshAuthToken()
    {
        $this->authToken = sha1(random_bytes(100)) . sha1(random_bytes(100));
    }

    /**
     * @throws DbException
     */
    public function updateLastVisitTime()
    {
        $timeInObjectFormat = new DateTime();
        $this->setLastVisitTime($timeInObjectFormat);
        $this->save();
    }

    /**
     * @return bool
     * @throws Exception
     */
    public function isOnline(): bool
    {
        return  new DateTime($this->lastVisitTime) > new DateTime('2 minutes ago');
    }

    /**
     * @param array $dataFromChangeRoleForm
     * @param User $userById
     * @return string
     * @throws DbException
     * @throws InvalidArgumentException
     */
    public function changeAndSaveRole(array $dataFromChangeRoleForm, User $userById): string
    {
        if(!isset($dataFromChangeRoleForm['role'])) {
            throw new InvalidArgumentException('role option is not passed');
        }
        if ($userById->getRole() === $dataFromChangeRoleForm['role']) {
            throw new InvalidArgumentException('This user role is already set as "'.$dataFromChangeRoleForm['role'].'"');
        }
        $this->setRole($dataFromChangeRoleForm['role']);
        $this->save();
        return $this->role;
    }

    /**
     * @param $signatureArray
     * @throws DbException
     */
    public function signatureSet($signatureArray)
    {
        $this->setSignature($signatureArray['text']);
        $this->save();
    }


    /**
     * @param string $prefix
     * @return string|null
     */
    public function getAvatar(string $prefix = ''): ?string
    {
        $prefix = $prefix ?? '';
        $userImagesPath = AddressingServices::imageFolderLink('users', $this->getId());
        $userImagesAddress = '/images/users/' . $this->getId();
        $userImagesDir = glob($userImagesPath . '/*');
        foreach ($userImagesDir as $item) {
            if (pathinfo($item)['filename'] == $prefix . $this->getId()) {
                $file = pathinfo($item)['basename'];
                return  $userImagesAddress . '/' . $file;
                break;
            }
        }
        return null;
    }
}