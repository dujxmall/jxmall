<?php

namespace app\plugins\share\models;

use app\models\BaseActiveRecord;
use Yii;

/**
 * This is the model class for table "{{%plugin_share_order}}".
 *
 * @property int $id
 * @property int $mall_id
 * @property int $user_id
 * @property int $common_order_id
 * @property int $is_delete
 * @property int|null $created_at
 * @property int|null $deleted_at
 * @property int|null $updated_at
 * @property int $parent_id_1
 * @property int $parent_id_2
 * @property int $parent_id_3
 * @property float $share_price
 */
class ShareOrder extends BaseActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return '{{%plugin_share_order}}';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['mall_id', 'user_id', 'common_order_id'], 'required'],
            [['share_price'], 'number'],
            [['mall_id', 'user_id', 'common_order_id', 'is_delete', 'created_at', 'deleted_at', 'updated_at', 'parent_id_1', 'parent_id_2', 'parent_id_3'], 'integer'],
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
            'common_order_id' => 'Common Order ID',
            'is_delete' => 'Is Delete',
            'created_at' => 'Created At',
            'deleted_at' => 'Deleted At',
            'updated_at' => 'Updated At',
            'parent_id_1' => 'Parent ID',
            'parent_id_2' => 'Parent Id 2',
            'parent_id_3' => 'Parent Id 3',
            'share_price' => '分销佣金'
        ];
    }
}
