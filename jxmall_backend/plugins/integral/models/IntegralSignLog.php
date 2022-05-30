<?php

namespace app\plugins\integral\models;

use app\models\BaseActiveRecord;
use app\models\BaseModel;
use Yii;

/**
 * This is the model class for table "{{%plugin_integral_sign_log}}".
 *
 * @property int $id
 * @property int $mall_id
 * @property int $user_id
 * @property int $integral 积分
 * @property string $date 日期
 * @property int|null $updated_at 更新时间
 * @property int $deleted_at
 * @property int|null $created_at 创建时间
 * @property int|null $is_delete
 * @property string $created_time 创建时间
 */
class IntegralSignLog extends BaseActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return '{{%plugin_integral_sign_log}}';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['mall_id', 'user_id'], 'required'],
            [['mall_id', 'user_id', 'integral', 'updated_at', 'deleted_at', 'created_at', 'is_delete'], 'integer'],
            [['created_time'], 'safe'],
            [['date'], 'string', 'max' => 45],
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
            'integral' => 'Integral',
            'date' => 'Date',
            'updated_at' => 'Updated At',
            'deleted_at' => 'Deleted At',
            'created_at' => 'Created At',
            'is_delete' => 'Is Delete',
            'created_time' => 'Created Time',
        ];
    }
}
