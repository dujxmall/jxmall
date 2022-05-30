<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "{{%bank}}".
 *
 * @property int $id
 * @property int $mall_id
 * @property int $user_id
 * @property string $name 开户名
 * @property string $account
 * @property string $bank_name
 * @property string $bank_branch_name
 * @property int|null $deleted_at
 * @property int|null $updated_at
 * @property int|null $created_at
 * @property int $is_delete
 * @property string|null $bank_code
 */
class Bank extends BaseActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return '{{%bank}}';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['mall_id', 'user_id', 'name', 'account', 'bank_name', 'bank_branch_name'], 'required'],
            [['mall_id', 'user_id', 'deleted_at', 'updated_at', 'created_at', 'is_delete'], 'integer'],
            [['name', 'account', 'bank_name', 'bank_branch_name', 'bank_code'], 'string', 'max' => 45],
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
            'name' => '开户名',
            'account' => 'Account',
            'bank_name' => 'Bank Name',
            'bank_branch_name' => 'Bank Branch Name',
            'deleted_at' => 'Deleted At',
            'updated_at' => 'Updated At',
            'created_at' => 'Created At',
            'is_delete' => 'Is Delete',
            'bank_code' => 'bank_code'
        ];
    }
}
