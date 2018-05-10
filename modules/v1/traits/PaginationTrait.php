<?php
/**
 * Created by Validson Team.
 * Author: https://github.com/YuriyBlackFlag
 */

namespace app\modules\v1\traits;


trait PaginationTrait
{
    private $_page;
    private $_limit;
    private $_offset;
    private $_itemsPerPage;

    public function setPagination($itemsPerPage, $page)
    {
        $this->_limit = $itemsPerPage;

        if ($page > 1) {
            $this->_offset = $page * $itemsPerPage - $itemsPerPage;
        } elseif ($page == 1) {
            $this->_offset = 0;
        }
    }

    /**
     * @return mixed
     */
    public function getPage()
    {
        return $this->_page;
    }

    /**
     * @param int $page
     * @return $this
     */
    public function setPage(int $page)
    {
        $this->_page = $page;
        return $this;
    }

    /**
     * @return mixed
     */
    public function getLimit()
    {
        return $this->_limit;
    }

    /**
     * @return mixed
     */
    public function getOffset()
    {
        return $this->_offset;
    }

    /**
     * @param mixed $itemsPerPage
     */
    public function setItemsPerPage($itemsPerPage)
    {
        $this->_itemsPerPage = $itemsPerPage;
    }

    /**
     * @return mixed
     */
    public function getItemsPerPage()
    {
        return $this->_itemsPerPage;
    }


}