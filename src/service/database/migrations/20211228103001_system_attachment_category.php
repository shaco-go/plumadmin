<?php

use think\migration\Migrator;
use think\migration\db\Column;

class SystemAttachmentCategory extends Migrator
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
        $this->table('system_attachment_category', ['comment' => "附件分类表"])
            ->addColumn('name', 'string', ['comment' => '分类名称', 'default' => ''])
            ->addColumn('parent_id', 'integer', ['comment' => '上级ID', 'default' => 0])
            ->addColumn('sort', 'integer', ['comment' => '排序(升序)', 'default' => 200])
            ->addColumn('operator_id', 'integer', ['comment' => '操作者ID', 'default' => 0])
            ->addTimestamps()
            ->addSoftDelete()
            ->save();
    }
}
