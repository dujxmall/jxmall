<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "{{%eorder_detail}}".
 *
 * @property int $id
 * @property int $mall_id
 * @property string $print_tpl
 * @property string $express_no
 * @property int|null $created_at
 * @property int|null $deleted_at
 * @property int|null $updated_at
 * @property int $is_delete
 * @property int $eorder_id
 */
class EorderDetail extends BaseActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return '{{%eorder_detail}}';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['mall_id', 'print_tpl', 'express_no'], 'required'],
            [['mall_id', 'created_at', 'deleted_at', 'updated_at', 'is_delete','eorder_id'], 'integer'],
            [['print_tpl'], 'string'],
            [['express_no'], 'string', 'max' => 45],
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
            'print_tpl' => 'Print Tpl',
            'express_no' => 'Express No',
            'created_at' => 'Created At',
            'deleted_at' => 'Deleted At',
            'updated_at' => 'Updated At',
            'is_delete' => 'Is Delete',
            'eorder_id'=>'eorder_id'
        ];
    }
}
