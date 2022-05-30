<?php


namespace app\core;


class BasePagination extends \yii\data\Pagination
{
    public $defaultPageSize = 10;
    public $pageSize;
    public $total_count;
    public $page_count;
    public $current_page;
    public $page_size;

    public function init()
    {
        parent::init();
        if (!$this->pageSize) {
            $this->pageSize = $this->defaultPageSize;
            $this->page_size=$this->defaultPageSize;
        }
        $totalCount = $this->totalCount ? intval($this->totalCount) : 0;
        $this->total_count = $totalCount;
        $this->page_count = $this->pageCount;

        $this->current_page = $this->page + 1;
    }

    public function getPageSize()
    {
        return $this->pageSize;
    }
}
