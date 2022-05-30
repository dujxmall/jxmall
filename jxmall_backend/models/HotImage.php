<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "{{%hot_image}}".
 *
 * @property int $id
 * @property int $mall_id
 * @property string|null $name 热图名称
 * @property string|null $pic_url 图片链接
 * @property string $data 数据
 * @property int|null $updated_at 更新时间
 * @property int $deleted_at 删除时间
 * @property int|null $created_at 创建时间
 * @property int|null $is_delete 是否删除
 * @property string $created_time 创建时间
 */
class HotImage extends BaseActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return '{{%hot_image}}';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['mall_id'], 'required'],
            [['mall_id', 'updated_at', 'deleted_at', 'created_at', 'is_delete'], 'integer'],
            [['created_time'], 'safe'],
            [['name'], 'string', 'max' => 45],
            [['pic_url'], 'string', 'max' => 255],
            [['data'], 'string', 'max' => 10240],
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
            'name' => 'Name',
            'pic_url' => 'Pic Url',
            'data' => 'Data',
            'updated_at' => 'Updated At',
            'deleted_at' => 'Deleted At',
            'created_at' => 'Created At',
            'is_delete' => 'Is Delete',
            'created_time' => 'Created Time',
        ];
    }
}
