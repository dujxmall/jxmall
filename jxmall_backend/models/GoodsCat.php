<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "{{%goods_cat}}".
 *
 * @property int $id
 * @property int $mall_id
 * @property int $goods_id
 * @property int $cat_id
 * @property int $is_delete
 * @property int $level
 * @property int|null $created_at
 * @property int|null $deleted_at
 * @property int|null $updated_at
 */
class GoodsCat extends BaseActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return '{{%goods_cat}}';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['mall_id'], 'required'],
            [['mall_id', 'goods_id', 'cat_id', 'is_delete', 'created_at', 'deleted_at', 'updated_at','level'], 'integer'],
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
            'goods_id' => 'Goods ID',
            'cat_id' => 'Cat ID',
            'is_delete' => 'Is Delete',
            'created_at' => 'Created At',
            'deleted_at' => 'Deleted At',
            'updated_at' => 'Updated At',
            'level'=>'分类层级'
        ];
    }
}
