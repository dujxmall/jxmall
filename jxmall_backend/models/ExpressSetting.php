<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "{{%express_setting}}".
 *
 * @property int $id
 * @property int $mall_id
 * @property string|null $kdniao_appcode
 * @property string|null $kdniao_customer
 * @property int|null $created_at
 * @property int|null $updated_at
 * @property int|null $deleted_at
 * @property int $is_delete
 */
class ExpressSetting extends BaseActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return '{{%express_setting}}';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['mall_id'], 'required'],
            [['mall_id', 'created_at', 'updated_at', 'deleted_at', 'is_delete'], 'integer'],
            [['kdniao_appcode', 'kdniao_customer'], 'string', 'max' => 64],
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
            'kdniao_appcode' => 'Kdniao Appcode',
            'kdniao_customer' => 'Kdniao Customer',
            'created_at' => 'Created At',
            'updated_at' => 'Updated At',
            'deleted_at' => 'Deleted At',
            'is_delete' => 'Is Delete',
        ];
    }
}
