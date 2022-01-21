<?php

namespace app\adminapi\controller\article;

use app\adminapi\Controller;
use app\adminapi\validate\article\ArticleCategoryValidate;
use app\model\article\ArticleCategoryModel;
use app\model\article\ArticleModel;
use plum\exception\FailException;

class ArticleCategory extends Controller
{
    /**
     * 分页
     * @author Plum
     * @email liujunyi_coder@163.com
     * @time 2021/12/29
     */
    public function page()
    {
        $page = ArticleCategoryModel::autoOrder()
            ->autoOrder()
            ->autoSearch()
            ->paginate();
        return $this->success($page);
    }

    /**
     * 分页
     * @author Plum
     * @email liujunyi_coder@163.com
     * @time 2022/1/14
     */
    public function list()
    {
        $list = ArticleCategoryModel::autoOrder()
            ->autoOrder()
            ->autoSearch()
            ->select();
        return $this->success($list);
    }

    /**
     * 创建
     * @author Plum
     * @email liujunyi_coder@163.com
     * @time 2021/12/29
     */
    public function create()
    {
        validate(ArticleCategoryValidate::class)
            ->scene('create')
            ->check($this->request->param());
        ArticleCategoryModel::create($this->request->param());
        return $this->success('操作成功');
    }

    /**
     * 更新
     * @author Plum
     * @email liujunyi_coder@163.com
     * @time 2021/12/29
     */
    public function update()
    {
        validate(ArticleCategoryValidate::class)
            ->scene('update')
            ->check($this->request->param());
        ArticleCategoryModel::update($this->request->param());
        return $this->success('操作成功');
    }

    /**
     * 详情
     * @author Plum
     * @email liujunyi_coder@163.com
     * @time 2021/12/29
     */
    public function detail()
    {
        $detail = ArticleCategoryModel::findOrFail($this->request->param('id'));
        return $this->success($detail);
    }

    /**
     * 删除
     * @author Plum
     * @email liujunyi_coder@163.com
     * @time 2021/12/29
     */
    public function delete()
    {
        $id = $this->request->param('id');
        $detail = ArticleCategoryModel::find($id);
        if (!$detail)
            throw new FailException('操作失败');
        $articles = ArticleModel::where('id', $id)
            ->select();
        if (!$articles->isEmpty())
            throw new FailException('请删除该分类下的文章后,在继续此操作!');
        $detail->delete();
        return $this->success('操作成功');
    }
}