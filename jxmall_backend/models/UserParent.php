<?php

namespace app\models;

use Yii;
use yii\base\BaseObject;

/**
 * This is the model class for table "{{%user_parent}}".
 *
 * @property int $id
 * @property int $mall_id
 * @property int $user_id
 * @property int $parent_id
 * @property int $is_delete
 * @property int|null $created_at
 * @property int|null $deleted_at
 * @property int|null $updated_at
 * @property int $level
 */
class UserParent extends BaseActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return '{{%user_parent}}';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['mall_id', 'user_id', 'parent_id', 'level'], 'required'],
            [['mall_id', 'user_id', 'parent_id', 'is_delete', 'created_at', 'deleted_at', 'updated_at', 'level'], 'integer'],
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
            'user_id' => 'User ID',
            'parent_id' => 'Parent ID',
            'is_delete' => 'Is Delete',
            'created_at' => 'Created At',
            'deleted_at' => 'Deleted At',
            'updated_at' => 'Updated At',
            'level' => 'Level',
        ];
    }
}
