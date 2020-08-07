<?php


namespace MyProject\Services;


/**
 * Class PaginationItem
 * @package MyProject\Services
 */
class PaginationItem
{
    /**
     * @var string
     */
    public $link;

    /**
     * @var int
     */
    public $number;

    /**
     * @var bool
     */
    public $isCurrent = false;

    /**
     * PaginationItem constructor.
     * @param int $number
     * @param bool $isCurrent
     * @param string $url
     */
    public function __construct(int $number, bool $isCurrent, string $url)
    {
        $this->number = $number;
        $this->isCurrent = $isCurrent;
        $this->link = $url . $number;
    }
}