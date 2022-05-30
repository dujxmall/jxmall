<?php

namespace app\plugins\share\models;
use app\models\BaseActiveRecord;

use Yii;

/**
 * This is the model class for table "{{%plugin_share_apply}}".
 *
 * @property int $id
 * @property int $mall_id
 * @property int $user_id
 * @property int $status 0 待处理  1 通过  2 不通过
 * @property string|null $remarks
 * @property int|null $created_at
 * @property int|null $deleted_at
 * @property int|null $updated_at
 * @property int $is_delete
 */
class ShareApply extends BaseActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return '{{%plugin_share_apply}}';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['mall_id', 'user_id'], 'required'],
            [['mall_id', 'user_id', 'status', 'created_at', 'deleted_at', 'updated_at', 'is_delete'], 'integer'],
            [['remarks'], 'string', 'max' => 255],
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
            'status' => '0 待处理  1 通过  2 不通过',
            'remarks' => 'Remarks',
            'created_at' => 'Created At',
            'deleted_at' => 'Deleted At',
            'updated_at' => 'Updated At',
            'is_delete' => 'Is Delete',
        ];
    }
}
