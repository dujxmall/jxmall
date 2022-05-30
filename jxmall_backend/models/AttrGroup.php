<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "{{%attr_group}}".
 *
 * @property int $id
 * @property int $mall_id
 * @property int $goods_id
 * @property int $attr_group_id
 * @property int|null $is_delete
 * @property int|null $created_at
 * @property int|null $deleted_at
 * @property int|null $updated_at
 * @property string|null $attr_group_name
 * @property string|null $attr_list
 */
class AttrGroup extends BaseActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return '{{%attr_group}}';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['mall_id', 'goods_id', 'attr_group_id'], 'required'],
            [['mall_id', 'goods_id', 'attr_group_id', 'is_delete', 'created_at', 'deleted_at', 'updated_at'], 'integer'],
            [['attr_group_name'], 'string', 'max' => 45],
            [['attr_list'], 'string', 'max' => 2048],
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
            'attr_group_id' => 'Attr Group ID',
            'is_delete' => 'Is Delete',
            'created_at' => 'Created At',
            'deleted_at' => 'Deleted At',
            'updated_at' => 'Updated At',
            'attr_group_name' => 'Attr Group Name',
            'attr_list' => 'Attr List',
        ];
    }
}
