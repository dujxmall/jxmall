<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "{{%menu_button}}".
 *
 * @property int $id
 * @property int|null $base_menu_id 按钮参数
 * @property string|null $name 名称
 * @property string|null $description 描述
 * @property int|null $updated_at 更新时间
 * @property int $deleted_at 删除时间
 * @property int|null $created_at 创建时间
 * @property int|null $is_delete 是否删除
 * @property string $created_time 创建时间
 */
class MenuButton extends BaseActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return '{{%menu_button}}';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['base_menu_id', 'updated_at', 'deleted_at', 'created_at', 'is_delete'], 'integer'],
            [['created_time'], 'safe'],
            [['name', 'description'], 'string', 'max' => 45],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'base_menu_id' => '按钮参数',
            'name' => '名称',
            'description' => '描述',
            'updated_at' => '更新时间',
            'deleted_at' => '删除时间',
            'created_at' => '创建时间',
            'is_delete' => '是否删除',
            'created_time' => '创建时间',
        ];
    }
}
