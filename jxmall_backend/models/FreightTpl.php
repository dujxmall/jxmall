<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "{{%freight_tpl}}".
 *
 * @property int $id
 * @property int $mall_id
 * @property int $enabled
 * @property string $name
 * @property int $is_default 默认模板
 * @property int $price_type 0 按件计费   1 按重量计费
 * @property int $updated_at
 * @property int $created_at
 * @property int $deleted_at
 * @property int $is_delete
 */
class FreightTpl extends BaseActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return '{{%freight_tpl}}';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['mall_id', 'name', 'updated_at', 'created_at', 'deleted_at'], 'required'],
            [['mall_id', 'enabled', 'is_default', 'price_type', 'updated_at', 'created_at', 'deleted_at', 'is_delete'], 'integer'],
            [['name'], 'string', 'max' => 45],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'mall_id' => 'Mall ID',
            'enabled' => 'Enabled',
            'name' => 'Name',
            'is_default' => '默认模板',
            'price_type' => '0 按件计费   1 按重量计费',
            'updated_at' => 'Updated At',
            'created_at' => 'Created At',
            'deleted_at' => 'Deleted At',
            'is_delete' => 'Is Delete',
        ];
    }
}
