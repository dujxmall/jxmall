<?php


namespace app\plugins\share\models;

use app\models\BaseActiveRecord;

use Yii;

/**
 * This is the model class for table "{{%plugin_share}}".
 *
 * @property int $id
 * @property int $mall_id
 * @property int $user_id
 * @property int $level_id
 * @property int $level
 * @property float $total_price 累计分销佣金
 * @property int|null $created_at
 * @property int|null $deleted_at
 * @property int|null $updated_at
 * @property int $is_delete
 * @property int $is_auto
 * @property int|null $level_at
 * @property int $is_manual
 */
class Share extends BaseActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return '{{%plugin_share}}';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['mall_id', 'user_id', 'level_id'], 'required'],
            [['mall_id', 'user_id', 'level', 'created_at', 'deleted_at', 'updated_at', 'is_delete', 'is_auto', 'level_id', 'level_at', 'is_manual'], 'integer'],
            [['total_price'], 'number'],
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
            'level' => 'Level',
            'total_price' => '累计分销佣金',
            'created_at' => 'Created At',
            'deleted_at' => 'Deleted At',
            'updated_at' => 'Updated At',
            'is_delete' => 'Is Delete',
            'is_auto' => 'is auto',
            'level_id' => 'level_id',
            'level_at' => 'level_at',
            'is_manual' => 'is_manual'
        ];
    }
}
