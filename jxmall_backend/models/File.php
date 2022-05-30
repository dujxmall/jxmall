<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "{{%file}}".
 *
 * @property int $id
 * @property int $mall_id
 * @property int $mch_id
 * @property string|null $url
 * @property string|null $thumb_url
 * @property int|null $created_at
 * @property int|null $deleted_at
 * @property int|null $updated_at
 * @property int $is_delete
 * @property string $type
 * @property int $group_id
 * @property string|null $name 文件名
 * @property int $user_id
 * @property int $admin_id
 * @property int $is_site
 * @property string $path
 * @property string $location
 *
 */
class File extends BaseActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return '{{%file}}';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['mall_id'], 'required'],
            [['mall_id', 'mch_id', 'created_at', 'deleted_at', 'updated_at', 'is_delete', 'group_id', 'admin_id', 'user_id', 'is_site'], 'integer'],
            [['url', 'thumb_url'], 'string', 'max' => 512],
            [['location'], 'string', 'max' => 10],
            [['path'], 'string', 'max' => 256],
            [['type'], 'string', 'max' => 15],
            [['name'], 'string', 'max' => 256],
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
            'mch_id' => 'Mch ID',
            'url' => 'Url',
            'thumb_url' => 'Thumb Url',
            'created_at' => 'Created At',
            'deleted_at' => 'Deleted At',
            'updated_at' => 'Updated At',
            'is_delete' => 'Is Delete',
            'type' => 'Type',
            'group_id' => 'Group ID',
            'name' => '文件名',
            'admin_id' => 'admin_id',
            'user_id' => 'user_id',
            'is_site' => 'is_site',
            'path' => '路径',
            'location' => '存储位置'
        ];
    }
}
