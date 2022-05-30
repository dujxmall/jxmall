<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "jxmall_admin_mall".
 *
 * @property int $id
 * @property int $mall_id
 * @property int $admin_id
 * @property string $role founder      manager   operator
 * @property int|null $created_at
 * @property int|null $is_delete
 * @property int|null $deleted_at
 * @property int|null $updated_at
 */
class AdminMall extends BaseActiveRecord
{
    const FOUNDER = 'founder';
    const MANAGER = 'manager';
    const OPERATOR = 'operator';

    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'jxmall_admin_mall';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['mall_id', 'admin_id', 'role'], 'required'],
            [['mall_id', 'admin_id', 'created_at', 'is_delete', 'deleted_at', 'updated_at'], 'integer'],
            [['role'], 'string', 'max' => 10],
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
            'admin_id' => 'Admin ID',
            'role' => 'Role',
            'created_at' => 'Created At',
            'is_delete' => 'Is Delete',
            'deleted_at' => 'Deleted At',
            'updated_at' => 'Updated At',
        ];
    }
}
