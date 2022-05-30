<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "{{%freight_price}}".
 *
 * @property int $id
 * @property int $mall_id
 * @property int $tpl_id 模板ID
 * @property int $first_num 数量可能是件数  可能是重量
 * @property float $first_price
 * @property int $other_num 续件数量可能是件数  可能是重量
 * @property float $other_price
 * @property string|null $send_area
 * @property int|null $created_at
 * @property int $deleted_at
 * @property int $updated_at
 * @property int $is_delete
 * @property int $is_union 统一
 * @property string|null $send_codes
 */
class FreightPrice extends BaseActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return '{{%freight_price}}';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['mall_id', 'tpl_id'], 'required'],
            [['mall_id', 'tpl_id', 'first_num', 'other_num', 'created_at', 'deleted_at', 'updated_at', 'is_delete', 'is_union'], 'integer'],
            [['first_price', 'other_price'], 'number'],
            [['send_area','send_codes'], 'string'],
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
            'tpl_id' => '模板ID',
            'first_num' => '数量可能是件数  可能是重量',
            'first_price' => 'First Price',
            'other_num' => '续件数量可能是件数  可能是重量',
            'other_price' => 'Other Price',
            'send_area' => 'Send Area',
            'created_at' => 'Created At',
            'deleted_at' => 'Deleted At',
            'updated_at' => 'Updated At',
            'is_delete' => 'Is Delete',
            'is_union' => '统一',
            'send_codes'=>'send_codes'
        ];
    }
}
