<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "{{%option}}".
 *
 * @property int $id
 * @property int $mall_id
 * @property string $name
 * @property string|null $value
 * @property int|null $created_at
 * @property int|null $updated_at
 * @property int|null $deleted_at
 * @property int $is_delete
 */
class Option extends BaseActiveRecord
{

    const NAME_SYS_SETTING = 'sys';
    const NAME_MALL_SETTING = "mall_setting";

    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return '{{%option}}';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['mall_id', 'name'], 'required'],
            [['mall_id', 'created_at', 'updated_at', 'deleted_at', 'is_delete'], 'integer'],
            [['value'], 'string'],
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
            'name' => 'Name',
            'value' => 'Value',
            'created_at' => 'Created At',
            'updated_at' => 'Updated At',
            'deleted_at' => 'Deleted At',
            'is_delete' => 'Is Delete',
        ];
    }
}
