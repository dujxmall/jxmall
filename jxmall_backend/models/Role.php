<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "{{%role}}".
 *
 * @property int $id
 * @property string|null $role_name role_name
 * @property string|null $role_key role_key
 * @property int|null $role_sort 排序
 * @property string|null $remark 备注
 * @property int|null $status 状态
 * @property int|null $menu_check_strictly menu_check_strictly
 * @property int|null $admin  是否是管理员
 * @property int|null $updated_at 更新时间
 * @property int $deleted_at 删除时间
 * @property int|null $created_at 创建时间
 * @property int|null $is_delete 是否删除
 * @property string $created_time 创建时间
 * @property integer $created_by 创建人
 */
class Role extends BaseActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return '{{%role}}';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['role_sort','admin', 'status', 'menu_check_strictly', 'updated_at', 'deleted_at', 'created_at', 'is_delete','created_by'], 'integer'],
            [['created_time'], 'safe'],
            [['role_name'], 'string', 'max' => 45],
            [['role_key', 'remark'], 'string', 'max' => 255],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'role_name' => 'role_name',
            'role_key' => 'role_key',
            'role_sort' => '排序',
            'remark' => '备注',
            'status' => '状态',
            'menu_check_strictly' => 'menu_check_strictly',
            'admin' => '管理员',
            'updated_at' => '更新时间',
            'deleted_at' => '删除时间',
            'created_at' => '创建时间',
            'is_delete' => '是否删除',
            'created_time' => '创建时间',
            'created_by'=>'创建人'
        ];
    }
}
