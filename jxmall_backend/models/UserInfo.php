<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "{{%user_info}}".
 *
 * @property int $id
 * @property int $mall_id
 * @property int $user_id
 * @property string $openid
 * @property int $platform 0 wechat 1 mpwx  2 app
 * @property int $created_at
 * @property int $deleted_at
 * @property int $updated_at
 * @property int $is_delete
 * @property string|null $union_id 用户unionid
 */
class UserInfo extends BaseActiveRecord
{
    const PLATFORM_WECHAT = 0;
    const PLATFORM_MPWX = 1;
    const PLATFORM_WXAPP = 2;
    const PLATFORM_MOBILE = 3;
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return '{{%user_info}}';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['mall_id', 'user_id', 'openid', 'created_at', 'deleted_at', 'updated_at'], 'required'],
            [['mall_id', 'user_id', 'platform', 'created_at', 'deleted_at', 'updated_at', 'is_delete'], 'integer'],
            [['openid', 'union_id'], 'string', 'max' => 64],
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
            'openid' => 'Openid',
            'platform' => '0 wechat 1 mpwx  2 app',
            'created_at' => 'Created At',
            'deleted_at' => 'Deleted At',
            'updated_at' => 'Updated At',
            'is_delete' => 'Is Delete',
            'union_id' => '用户unionid',
        ];
    }
}
