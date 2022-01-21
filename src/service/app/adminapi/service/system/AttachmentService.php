<?php

namespace app\adminapi\service\system;

use app\model\system\SystemAttachmentModel;
use plum\exception\FailException;
use plum\utils\Arr;
use plum\utils\Helper;
use think\facade\Filesystem;

class AttachmentService
{
    const FIELD = 'file';

    /**
     * 校验文件
     * @author Plum
     * @email liujunyi_coder@163.com
     * @time 2021/12/29
     */
    private static function valid($file): void
    {
        if (!$file)
            throw new FailException('请上传文件');
        $rules = config('filesystem_valid');
        $extension = $file->getOriginalExtension();
        $size = $file->getSize();
        $isValid = false;
        foreach ($rules as $rule) {
            if (Arr::inArray($extension, $rule['extension'])) {
                $isValid = true;
                if ($size > $rule['size']) {
                    throw new FailException('上传文件大小不能超过' . Helper::prettyFileSize($rule['size']));
                }
                break;
            }
        }
        //如果规则存在,但是未经过规则,则不支持此类型文件
        if (count($rules)>0 && !$isValid)
            throw new FailException('不支持上传此类型文件');
    }

    /**
     * 上传
     * @author Plum
     * @email liujunyi_coder@163.com
     * @time 2021/12/29
     */
    public static function upload()
    {
        $file = request()->file('file');
        self::valid($file);
        $path = Filesystem::putFile('', $file);
        $data = [
            'name'        => $file->getOriginalName(),
            'path'        => $path,
            'driver'      => config('filesystem.default'),
            'mime'        => $file->getOriginalMime(),
            'size'        => $file->getSize(),
            'module'      => app('http')->getName(),
            'category_id' => request()->param('category_id', 0),
            'operator_id' => request()->middleware('userinfo')['id'] ?? 0,
        ];
        $fileModel = SystemAttachmentModel::create($data);
        $fileModel->append(['size_text', 'url'])->visible(['name', 'url', 'mime']);
        return $fileModel;
    }
}