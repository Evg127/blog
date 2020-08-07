<?php


namespace MyProject\Services;

/**
 * Class paginator
 * @package MyProject\Services
 */
class Paginator
{
    /**
     * @var string
     */
    private $url;

    /**
     * @var int
     */
    private $paginatorWidth;
    /**
     * @var int
     */
    private $currentPage;
    /**
     * @var int
     */
    private $itemsInDb;

    /**
     * @var int
     */
    private $itemsPerPage = 3;

    /**
     * paginator constructor.
     * @param int $currentPage
     * @param int $itemsInDb
     * @param string $url
     */
    public function __construct(int $currentPage, int $itemsInDb, string $url)
    {
        $this->currentPage = $currentPage;
        $this->itemsInDb = $itemsInDb;
        $this->paginatorWidth = 5;
        $this->url = $url;
    }

    /**
     * @return int
     */
    public function getPagesCount():int
    {
        return ceil($this->itemsInDb / $this->itemsPerPage);
    }

    /**
     * @return int
     */
    public function getPrev()
    {
        $prev = $this->getCurrentPage() == 1 ? 1 : $this->getCurrentPage() - 1;
        return $this->url . $prev;
    }

    /**
     * @return string
     */
    public function getFirst()
    {
        return $this->url . '1';
    }

    /**
     * @return int
     */
    public function getLast()
    {
        return $this->url . $this->getPagesCount();
    }

    /**
     * @return int
     */
    public function getNext()
    {
        $next = $this->getCurrentPage() == $this->getPagesCount() ? $this->getPagesCount() : $this->getCurrentPage() + 1;
        return $this->url . $next;
    }

    /**
     * @return int
     */
    public function getCurrentPage()
    {
        return $this->currentPage;
    }
    
    /**
     * @return int
     */
    public function getPagesLimit()
    {
        return $this->itemsPerPage;
    }

    /**
     * @return int
     */
    public function getOffset()
    {
        return ((max(1, $this->getCurrentPage())) - 1) * $this->getPagesLimit();
    }

    /**
     * @return bool
     */
    public function isStart(): bool
    {
        $pages = $this->getPaginatorLinksArray();
        return reset($pages)->number === 1;
    }

    /**
     * @return bool
     */
    public function isEnd(): bool
    {
        $pages = $this->getPaginatorLinksArray();
        return end($pages)->number === $this->getPagesCount();
    }

    /**
     * @return array
     */
    public function getPaginatorLinksArray()
    {
        $start = max(1, $this->getCurrentPage() - ($this->paginatorWidth - 1) / 2);
        $end = min($this->getPagesCount(), $start + $this->paginatorWidth - 1);
        $start = max(1, $end - ($this->paginatorWidth - 1));
        $pages = [];
        for ($i = $start; $i <= $end; $i++) {
            $pages[] = new PaginationItem($i, (int)$i == $this->getCurrentPage(), $this->url);
        }
        return $pages;
    }
}