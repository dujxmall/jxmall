<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "{{%file_group}}".
 *
 * @property int $id
 * @property int $mall_id
 * @property int $mch_id
 * @property string|null $name
 * @property int|null $created_at
 * @property int|null $updated_at
 * @property int|null $deleted_at
 * @property int $is_delete
 * @property int $is_site
 * @property int $admin_id
 */
class FileGroup extends BaseActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return '{{%file_group}}';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['mall_id'], 'required'],
            [['id', 'mall_id', 'mch_id', 'created_at', 'updated_at', 'deleted_at', 'is_delete', 'is_site', 'admin_id'], 'integer'],
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
            'mch_id' => 'Mch ID',
            'name' => 'Name',
            'created_at' => 'Created At',
            'updated_at' => 'Updated At',
            'deleted_at' => 'Deleted At',
            'is_delete' => 'Is Delete',
            'admin_id'=>'admin_id',
            'is_site'=>'is_site'
        ];
    }
}
