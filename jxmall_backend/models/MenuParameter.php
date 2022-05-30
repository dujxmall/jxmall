<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "{{%menu_parameter}}".
 *
 * @property int $id
 * @property int|null $base_menu_id 按钮参数
 * @property string|null $type query params
 * @property string|null $key key
 * @property string|null $value 值
 * @property int|null $updated_at 更新时间
 * @property int $deleted_at 删除时间
 * @property int|null $created_at 创建时间
 * @property int|null $is_delete 是否删除
 * @property string $created_time 创建时间
 */
class MenuParameter extends BaseActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return '{{%menu_parameter}}';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['base_menu_id', 'updated_at', 'deleted_at', 'created_at', 'is_delete'], 'integer'],
            [['created_time'], 'safe'],
            [['type'], 'string', 'max' => 15],
            [['key'], 'string', 'max' => 45],
            [['value'], 'string', 'max' => 255],
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
            'type' => 'query params',
            'key' => 'key',
            'value' => '值',
            'updated_at' => '更新时间',
            'deleted_at' => '删除时间',
            'created_at' => '创建时间',
            'is_delete' => '是否删除',
            'created_time' => '创建时间',
        ];
    }
}
