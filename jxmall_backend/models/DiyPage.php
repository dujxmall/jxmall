<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "{{%diy_page}}".
 *
 * @property int $id
 * @property int $mall_id
 * @property int $is_home
 * @property string $data
 * @property int $is_delete
 * @property int|null $created_at
 * @property int|null $deleted_at
 * @property int|null $updated_at
 * @property int $is_enable
 * @property string $name
 */
class DiyPage extends BaseActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return '{{%diy_page}}';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['mall_id', 'data'], 'required'],
            [['mall_id', 'is_home', 'is_delete', 'created_at', 'deleted_at', 'updated_at', 'is_enable'], 'integer'],
            [['data', 'name'], 'string'],
            [['name'], 'string', 'max' => 45]
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
            'is_home' => 'Is Home',
            'data' => 'Data',
            'is_delete' => 'Is Delete',
            'created_at' => 'Created At',
            'deleted_at' => 'Deleted At',
            'updated_at' => 'Updated At',
            'is_enable' => 'Is Enable',
            'name' => 'name'
        ];
    }
}
