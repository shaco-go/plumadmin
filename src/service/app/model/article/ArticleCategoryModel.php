<?php

namespace app\model\article;

use plum\basic\BaseModel;
use think\model\concern\SoftDelete;

class ArticleCategoryModel extends BaseModel
{
    use SoftDelete;

    protected $name = 'article_category';

    /**
     * 搜索器 - name
     * @author Plum
     * @email liujunyi_coder@163.com
     * @time 2021/12/29
     */
    public function searchNameAttr($query, $value)
    {
        $query->whereLike('name', "%$value%");
    }
}