<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "{{%upload_setting}}".
 *
 * @property int $id
 * @property int $mall_id
 * @property string $type local 本地  oss 阿里云  cos 腾讯云 qiniu 七牛云
 * @property int $is_delete
 * @property int|null $created_at
 * @property int|null $updated_at
 * @property int|null $deleted_at
 */
class UploadSetting extends BaseActiveRecord
{

    const TYPE_LOCAL = 'local';
    const TYPE_COS = 'cos';
    const TYPE_OSS = 'oss';
    const TYPE_QINIU = 'qiniu';

    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return '{{%upload_setting}}';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['mall_id'], 'required'],
            [['mall_id', 'is_delete', 'created_at', 'updated_at', 'deleted_at'], 'integer'],
            [['type'], 'string', 'max' => 45],
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
            'type' => 'local 本地  oss 阿里云  cos 腾讯云 qiniu 七牛云',
            'is_delete' => 'Is Delete',
            'created_at' => 'Created At',
            'updated_at' => 'Updated At',
            'deleted_at' => 'Deleted At',
        ];
    }
}
