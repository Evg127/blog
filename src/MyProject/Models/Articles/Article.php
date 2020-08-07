<?php

namespace MyProject\Models\Articles;

use DateTime;
use Exception;
use MyProject\Exceptions\DbException;
use MyProject\Exceptions\InvalidArgumentException;
use MyProject\Models\ActiveRecordEntity;
use MyProject\Models\Users\User;
use Parsedown;

class Article extends ActiveRecordEntity
{
    /** @var string */
    protected $name;

    /** @var string */
    protected $text;

    /** @var int */
    protected $authorId;

    /** @var string */
    protected $createdAt;

    /**
     * @param string $name
     */
    public function setName(string $name): void
    {
        $this->name = $name;
    }

    /**
     * @param int $authorId
     */
    public function setAuthorId(int $authorId): void
    {
        $this->authorId = $authorId;
    }

    /**
     * @param DateTime $createdAt
     * @return void
     */
    public function setCreatedAt(DateTime $createdAt): void
    {
        $this->createdAt = $createdAt->format('Y-m-d H:m:s');
    }

    /**
     * @param string $text
     */
    public function setText(string $text): void
    {
        $this->text = $text;
    }

    /**
     * @return string
     */
    public function getName(): string
    {
        return $this->name;
    }

    /**
     * @return string
     */
    public function getText(): string
    {
        return $this->text;
    }

    /**
     * @return string
     */
    public function getParsedText(): string
    {
        $parser = new Parsedown();
        return $parser->text($this->getText());
    }

    /**
     * @return string
     */
    public function getShortText50(): string
    {
        if (mb_strlen($this->text) > 50) {
            return substr($this->text, 0, 50) . '...';
        } else {
            return substr($this->text, 0, 50);
        }
    }

    /**
     * @return string
     */
    public function getParsedShortText50(): string
    {
        $parser = new Parsedown();
        return $parser->text($this->getShortText50());
    }

    /**
     * @return string
     */
    public function getParsedShortText300(): string
    {
        $parser = new Parsedown();
        return $parser->text($this->getShortText300());
    }

    /**
     * @return string
     */
    public function getShortText300(): string
    {
        if (mb_strlen($this->text) > 300) {
            return substr($this->text, 0, 300) . '...';
        } else {
            return substr($this->text, 0, 300);
        }
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
    public function getStringCreatedAt(): string
    {
        return $this->createdAt;
    }

    /**
     * @return DateTime
     * @throws Exception
     */
    public function getCreatedAt(): DateTime
    {
        return new DateTime($this->createdAt);
    }

    /**
     * @return User
     * @throws DbException
     */
    public function getAuthor(): User
    {
        return User::getById($this->authorId);
    }

    protected static function getTableName(): string
    {
        return 'articles';
    }

    /**
     * @param array $dataArray
     * @param int $authorId
     * @return Article
     * @throws DbException
     * @throws InvalidArgumentException
     */
    public static function createFromCreateForm(array $dataArray, int $authorId)
    {
        if (!isset($dataArray['name'], $dataArray['text'])) {
            throw new InvalidArgumentException('Article name or text is not existed');
        }
        if (empty($dataArray['name'])) {
            throw new InvalidArgumentException('Article name is not passed');
        }
        if (empty($dataArray['text'])) {
            throw new InvalidArgumentException('Article text is not passed');
        }
        $article = new Article();
        $article->setName($_POST['name']);
        $article->setText($_POST['text']);
        $article->setAuthorId($authorId);
        $article->save();
        return $article;
    }

    /**
     * @param array $dataArray
     * @return $this
     * @throws DbException
     * @throws InvalidArgumentException
     */
    public function updateFromEditForm(array $dataArray): Article
    {
        if (!isset($dataArray['name'], $dataArray['text'])) {
            throw new InvalidArgumentException('Article name or text is not existed');
        }
        if (empty($dataArray['name'])) {
            throw new InvalidArgumentException('Article name is not passed');
        }
        if (empty($dataArray['text'])) {
            throw new InvalidArgumentException('Article text is not passed');
        }
        $this->setName($dataArray['name']);
        $this->setText($dataArray['text']);
        $this->save();
        return $this;
    }
}