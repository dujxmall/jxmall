<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "{{%role_api}}".
 *
 * @property int $id
 * @property string|null $path 接口路径
 * @property string|null $role_id 角色ID
 * @property string|null $method 请求方法
 * @property string|null $uniqueid 唯一ID
 * @property int|null $updated_at 更新时间
 * @property int $deleted_at 删除时间
 * @property int|null $created_at 创建时间
 * @property int|null $is_delete 是否删除
 * @property string $created_time 创建时间
 */
class RoleApi extends BaseActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return '{{%role_api}}';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['updated_at', 'deleted_at', 'created_at', 'is_delete'], 'integer'],
            [['created_time'], 'safe'],
            [['path', 'uniqueid'], 'string', 'max' => 255],
            [['role_id'], 'string', 'max' => 90],
            [['method'], 'string', 'max' => 10],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'path' => '接口路径',
            'role_id' => '角色ID',
            'method' => '请求方法',
            'uniqueid' => '唯一ID',
            'updated_at' => '更新时间',
            'deleted_at' => '删除时间',
            'created_at' => '创建时间',
            'is_delete' => '是否删除',
            'created_time' => '创建时间',
        ];
    }
}
