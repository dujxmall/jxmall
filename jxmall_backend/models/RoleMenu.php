<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "{{%role_menu}}".
 *
 * @property int $id
 * @property int|null $role_id role_id
 * @property string $menu_id menu_id
 * @property int|null $updated_at 更新时间
 * @property int $deleted_at 删除时间
 * @property int|null $created_at 创建时间
 * @property int|null $is_delete 是否删除
 * @property string $created_time 创建时间
 */
class RoleMenu extends BaseActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return '{{%role_menu}}';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['menu_id'],'string'],
            [['role_id', 'updated_at', 'deleted_at', 'created_at', 'is_delete'], 'integer'],
            [['created_time'], 'safe'],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'role_id' => 'role_id',
            'menu_id' => 'menu_id',
            'updated_at' => '更新时间',
            'deleted_at' => '删除时间',
            'created_at' => '创建时间',
            'is_delete' => '是否删除',
            'created_time' => '创建时间',
        ];
    }
}
