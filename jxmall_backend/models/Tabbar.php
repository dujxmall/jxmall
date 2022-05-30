<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "{{%tabbar}}".
 *
 * @property int $id
 * @property int $mall_id
 * @property string $setting
 * @property int $is_delete
 * @property int|null $created_at
 * @property int|null $updated_at
 * @property int|null $deleted_at
 * @property string|null $color
 */
class Tabbar extends BaseActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return '{{%tabbar}}';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['mall_id', 'setting'], 'required'],
            [['mall_id', 'is_delete', 'created_at', 'updated_at', 'deleted_at'], 'integer'],
            [['setting','color'], 'string'],
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
            'setting' => 'Setting',
            'is_delete' => 'Is Delete',
            'created_at' => 'Created At',
            'updated_at' => 'Updated At',
            'deleted_at' => 'Deleted At',
            'color'=>'color'
        ];
    }
}
