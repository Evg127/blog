<?php


namespace MyProject\Models\Comments;


use MyProject\Exceptions\DbException;
use MyProject\Exceptions\InvalidArgumentException;
use MyProject\Exceptions\UnauthorizedException;
use MyProject\Models\ActiveRecordEntity;
use MyProject\Models\Users\User;
use MyProject\Services\Db;

/**
 * Class Comment
 * @package MyProject\Models\Comments
 */
class Comment extends ActiveRecordEntity
{

    /** @var int */
    protected $articleId;

    /** @var string */
    protected $commentText;

    /** @var int */
    protected $authorId;

    /** @var string */
    protected $createdAt;

    /** @var string */
    protected $signature;

    /**
     * @return string
     */
    public function getCreatedAt()
    {
        return $this->createdAt;
    }

    /**
     * @return int
     */
    public function getAuthorId(): int
    {
        return $this->authorId;
    }

    /**
     * @return string
     */
    public function getCommentText()
    {
        return $this->commentText;
    }

    /**
     * @param int $articleId
     */
    public function setArticleId($articleId)
    {
        $this->articleId = $articleId;
    }

    /**
     * @param int $authorId
     */
    public function setAuthorId($authorId)
    {
        $this->authorId = $authorId;
    }

    /**
     * @param string $commentText
     */
    public function setCommentText($commentText)
    {
        $this->commentText = $commentText;
    }

    /**
     * @return string
     */
    public function getCreateAt()
    {
        return $this->createdAt;
    }

    /**
     * @return int
     */
    public function getArticleId()
    {
        return $this->articleId;
    }

    /**
     * @return User|null
     * @throws DbException
     */
    public function getAuthor()
    {
        return User::getById($this->authorId);
    }

    /**
     * @return string
     */
    protected static function getTableName(): string
    {
        return 'comments';
    }

    /**
     * @return null|string
     * @throws DbException
     */
    public function getSignature(): ?string
    {
        return User::getById($this->authorId)->getSignature();
    }

    /**
     * @param array $newCommentArray
     * @param int $articleId
     * @param User $author
     * @return $this
     * @throws DbException
     * @throws UnauthorizedException|InvalidArgumentException
     */
    public function add(array $newCommentArray, int $articleId, User $author): Comment
    {
        if (empty($newCommentArray['text'])) {
            throw new InvalidArgumentException('Text field cannot be empty');
        }
        $this->setArticleId($articleId);
        $this->setCommentText($_POST['text']);
        $authorId = $author->getId();
        if ($authorId === null) {
            throw new UnauthorizedException('Wrong user id');
        }
        $this->setAuthorId($authorId);
        $this->save();
        return $this;
    }

    /**
     * @param array $editCommentData
     * @return $this
     * @throws DbException
     * @throws InvalidArgumentException
     */
    public function edit(array $editCommentData): Comment
    {
        if (empty($editCommentData['text'])) {
            throw new InvalidArgumentException('Comment cannot be empty');
        }
        if ($editCommentData['text'] === $this->getCommentText()) {
            throw new InvalidArgumentException('Nothing changed');
        }
        $this->setCommentText($editCommentData['text']);
        $this->save();
        return $this;
    }
}