<?php

namespace app\model\article;

use plum\basic\BaseModel;
use think\model\concern\SoftDelete;

class ArticleModel extends BaseModel
{
    use SoftDelete;

    protected $name = 'article';

    /**
     * 关联表 - category
     * @author Plum
     * @email liujunyi_coder@163.com
     * @time 2021/12/29
     */
    public function category()
    {
        return $this->belongsTo(ArticleCategoryModel::class, 'category_id', 'id');
    }

    /**
     * 搜索器 - category_id
     * @author Plum
     * @email liujunyi_coder@163.com
     * @time 2022/1/14
     */
    public function searchCategoryIdAttr($query, $value)
    {
        $query->where('category_id', $value);
    }

    /**
     * 搜索器 - title
     * @author Plum
     * @email liujunyi_coder@163.com
     * @time 2022/1/14
     */
    public function searchTitleAttr($query, $value)
    {
        $query->whereLike('title', "%{$value}%");
    }

    /**
     * search - 状态
     * @author Plum
     * @email liujunyi_coder@163.com
     * @time 2022/1/15
     */
    public function searchStatusAttr($query, $value)
    {
        $query->where('status', $value);
    }
}