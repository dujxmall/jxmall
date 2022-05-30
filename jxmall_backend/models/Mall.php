<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "{{%mall}}".
 *
 * @property int $id
 * @property int $admin_id 创建者管理员的ID
 * @property string $name 商城名称
 * @property int $is_has_expire 是否存在过期
 * @property int|null $end_at 到期时间
 * @property int $is_expire 是否过期
 * @property int|null $updated_at
 * @property int|null $created_at
 * @property int|null $deleted_at
 * @property int $is_delete 是否删除
 * @property int $is_stop 是否停用
 * @property string|null $logo logo
 * @property string|null $detail
 * @property int|null $uniacid
 */
class Mall extends BaseActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return '{{%mall}}';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['admin_id', 'name'], 'required'],
            [['admin_id','uniacid', 'is_has_expire', 'end_at', 'is_expire', 'updated_at', 'created_at', 'deleted_at', 'is_delete', 'is_stop'], 'integer'],
            [['name'], 'string', 'max' => 45],
            [['logo', 'detail'], 'string', 'max' => 255],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'admin_id' => '创建者管理员的ID',
            'name' => '商城名称',
            'is_has_expire' => '是否存在过期',
            'end_at' => '到期时间',
            'is_expire' => '是否过期',
            'updated_at' => 'Updated At',
            'created_at' => 'Created At',
            'deleted_at' => 'Deleted At',
            'is_delete' => '是否删除',
            'is_stop' => '是否停用',
            'logo' => 'logo',
            'detail' => '详细',
            'uniacid'=>'统一公众号ID'
        ];
    }
}
