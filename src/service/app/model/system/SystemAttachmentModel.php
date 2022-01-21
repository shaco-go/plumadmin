<?php

namespace app\model\system;

use plum\basic\BaseModel;
use plum\utils\Arr;
use plum\utils\Helper;
use think\facade\Filesystem;
use think\model\concern\SoftDelete;

class SystemAttachmentModel extends BaseModel
{
    use SoftDelete;

    protected $name = 'system_attachment';

    /**
     * 获取器 - url
     * @author Plum
     * @email liujunyi_coder@163.com
     * @time 2021/12/29
     */
    public function getUrlAttr($value, $data)
    {
        return str_replace('\\', '/', Filesystem::disk($data['driver'])->getUrl($data['path']));
    }

    /**
     * 获取器 - size_text
     * @author Plum
     * @email liujunyi_coder@163.com
     * @time 2021年11月29日 17:44
     */
    public function getSizeTextAttr($value, $data)
    {
        return Helper::prettyFileSize($data['size']);
    }

    /**
     * 搜索器 - name
     * @author Plum
     * @email liujunyi_coder@163.com
     * @time 2021年12月13日 01:29
     */
    public function searchNameAttr($query, $value)
    {
        $query->whereLike('name', "%$value%");
    }

    /**
     * 搜索器 - type
     * @author Plum
     * @email liujunyi_coder@163.com
     * @time 2021/12/28
     */
    public function searchTypeAttr($query, $value)
    {
        switch ($value) {
            case 'image':
                $query->whereLike('mime', "image/%");
                break;
            case 'video':
                $query->whereLike('mime', "video/%");
                break;
            case 'file':
                $query->whereNotLike('mime', "image/%")
                    ->whereNotLike('mime', "video/%");
                break;
        }
    }

    /**
     * 搜索器
     * @author Plum
     * @email liujunyi_coder@163.com
     * @time 2021/12/29
     */
    public function searchCategoryIdAttr($query, $value)
    {
        if ($value === 0) {
            $query->where('parent_id', $value);
        } else {
            $ids = Helper::getChildrenIds(new SystemAttachmentCategoryModel(),$value);
            $query->whereIn('category_id',$ids);
        }
    }

}