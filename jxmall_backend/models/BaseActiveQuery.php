<?php

namespace app\models;

use app\core\BasePagination;
use yii\db\ActiveQuery;

class BaseActiveQuery extends ActiveQuery
{
    /**
     * @param BasePagination|null $pagination
     * @param Integer $limit
     * @param Integer $page
     * @return BaseActiveQuery
     */
    public function page(&$pagination = null, $limit = 20, $page = null)
    {
        $count = $this->count();
        if ($page) {
            $currentPage = $page - 1;
        } else {
            $currentPage = \Yii::$app->request->get('page', 1) - 1;
        }
        $pagination = new BasePagination(['totalCount' => $count, 'pageSize' => $limit, 'page' => $currentPage]);
        $this->limit($pagination->limit)->offset($pagination->offset);
        return $this;
    }

    /**
     * @param int $limit
     * @param int $page
     * @return BaseActiveQuery
     * 无需计算总数的分页
     */
    public function apiPage($limit = 20, $page = 1)
    {
        $offset = ($page - 1) * $limit;
        $this->limit($limit)->offset($offset);
        return $this;
    }

    /**
     * @param string|boolean $keyword
     * @param array|string $condition
     * @return BaseActiveQuery
     * 当keyword为true时，将条件添加到andWhere中
     */
    public function keyword($keyword, $condition)
    {
        if ($keyword) {
            $this->andWhere($condition);
        }
        return $this;
    }
}
