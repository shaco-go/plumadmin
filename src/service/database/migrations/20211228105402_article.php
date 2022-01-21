<?php

use think\migration\Migrator;
use think\migration\db\Column;

class Article extends Migrator
{
    /**
     * Change Method.
     *
     * Write your reversible migrations using this method.
     *
     * More information on writing migrations is available here:
     * http://docs.phinx.org/en/latest/migrations.html#the-abstractmigration-class
     *
     * The following commands can be used in this method and Phinx will
     * automatically reverse them when rolling back:
     *
     *    createTable
     *    renameTable
     *    addColumn
     *    renameColumn
     *    addIndex
     *    addForeignKey
     *
     * Remember to call "create()" or "update()" and NOT "save()" when working
     * with the Table class.
     */
    public function change()
    {
        $this->table('article', ['comment' => '文章表'])
            ->addColumn('category_id', 'integer', ['comment' => '分类ID', 'default' => 0])
            ->addColumn('title', 'string', ['comment' => '文章标题', 'default' => ''])
            ->addColumn('author', 'string', ['comment' => '作者', 'default' => ''])
            ->addColumn('cover', 'integer', ['comment' => '封面图', 'default' => 0])
            ->addColumn('intro', 'string', ['comment' => '介绍', 'default' => '', 'length' => 2000])
            ->addColumn('content', 'text', ['comment' => '内容'])
            ->addColumn('sort', 'integer', ['comment' => '排序(升序)', 'default' => 200])
            ->addColumn('status', 'integer', ['comment' => '状态(0-隐藏 1-正常)', 'default' => 1])
            ->addTimestamps()
            ->addSoftDelete()
            ->save();
    }
}
