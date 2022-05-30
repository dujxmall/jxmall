<?php

namespace app\plugins\share\models;

use app\models\BaseActiveRecord;
use Yii;

/**
 * This is the model class for table "{{%plugin_share_level}}".
 *
 * @property int $id
 * @property int $mall_id
 * @property int $level
 * @property string $name
 * @property float $first_price
 * @property float $second_price
 * @property float $third_price
 * @property int $price_type 0 固定金额  1百分比
 * @property int $is_delete
 * @property int|null $deleted_at
 * @property int|null $created_at
 * @property int|null $updated_at
 */
class ShareLevel extends BaseActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return '{{%plugin_share_level}}';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['mall_id', 'level', 'name', 'first_price', 'second_price', 'third_price'], 'required'],
            [['mall_id', 'level', 'price_type', 'is_delete', 'deleted_at', 'created_at', 'updated_at'], 'integer'],
            [['first_price', 'second_price', 'third_price'], 'number'],
            [['name'], 'string', 'max' => 45],
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
            'level' => 'Level',
            'name' => 'Name',
            'first_price' => 'First Price',
            'second_price' => 'Second Price',
            'third_price' => 'Third Price',
            'price_type' => '0 固定金额  1百分比',
            'is_delete' => 'Is Delete',
            'deleted_at' => 'Deleted At',
            'created_at' => 'Created At',
            'updated_at' => 'Updated At',
        ];
    }
}
