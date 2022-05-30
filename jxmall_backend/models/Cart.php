<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "{{%cart}}".
 *
 * @property int $id
 * @property int $mall_id
 * @property int $user_id
 * @property int $goods_id
 * @property int $goods_attr_id
 * @property int $num
 * @property int|null $deleted_at
 * @property int|null $updated_at
 * @property int|null $created_at
 * @property int $is_delete
 */
class Cart extends BaseActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return '{{%cart}}';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['mall_id', 'user_id', 'goods_id'], 'required'],
            [['mall_id', 'user_id', 'goods_id', 'goods_attr_id', 'num', 'deleted_at', 'updated_at', 'created_at', 'is_delete'], 'integer'],
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
            'goods_id' => 'Goods ID',
            'goods_attr_id' => 'Goods Attr ID',
            'num' => 'Num',
            'deleted_at' => 'Deleted At',
            'updated_at' => 'Updated At',
            'created_at' => 'Created At',
            'is_delete' => 'Is Delete',
        ];
    }

    public static function deleteCart($goods_id,$goods_attr_id){

        $cart=self::findOne(['goods_id'=>$goods_id,'goods_attr_id'=>$goods_attr_id]);
        if($cart){

            $cart->is_delete=1;
            $cart->save();
        }

    }
}
