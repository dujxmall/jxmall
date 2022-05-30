<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "{{%goods_pic}}".
 *
 * @property int $id
 * @property int $mall_id
 * @property int $goods_id
 * @property string $pic_url
 * @property int|null $is_delete
 * @property int|null $created_at
 * @property int|null $deleted_at
 * @property int|null $updated_at
 */
class GoodsPic extends BaseActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return '{{%goods_pic}}';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['mall_id', 'goods_id', 'pic_url'], 'required'],
            [['mall_id', 'goods_id', 'is_delete', 'created_at', 'deleted_at', 'updated_at'], 'integer'],
            [['pic_url'], 'string', 'max' => 255],
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
            'pic_url' => 'Pic Url',
            'is_delete' => 'Is Delete',
            'created_at' => 'Created At',
            'deleted_at' => 'Deleted At',
            'updated_at' => 'Updated At',
        ];
    }
}
