<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "{{%goods_comment}}".
 *
 * @property int $id
 * @property int $mall_id
 * @property int $user_id
 * @property int $order_id
 * @property int $goods_id
 * @property string|null $content
 * @property string|null $pic_list
 * @property int|null $updated_at
 * @property int|null $created_at
 * @property int|null $deleted_at
 * @property int $is_delete
 * @property int $grade_level
 */
class GoodsComment extends BaseActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return '{{%goods_comment}}';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['mall_id', 'user_id', 'order_id', 'goods_id'], 'required'],
            [['mall_id', 'user_id', 'order_id', 'goods_id', 'updated_at', 'created_at', 'deleted_at', 'is_delete', 'grade_level'], 'integer'],
            [['content'], 'string', 'max' => 255],
            [['pic_list'], 'string', 'max' => 2048],
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
            'order_id' => 'Order ID',
            'goods_id' => 'Goods ID',
            'content' => 'Content',
            'pic_list' => 'Pic List',
            'updated_at' => 'Updated At',
            'created_at' => 'Created At',
            'deleted_at' => 'Deleted At',
            'is_delete' => 'Is Delete',
            'grade_level' => 'Grade Level',
        ];
    }
}
