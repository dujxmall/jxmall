<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "{{%cat}}".
 *
 * @property int $id
 * @property string $name
 * @property int $mall_id
 * @property string|null $cover_pic 缩略图
 * @property string|null $adv_pic 广告图
 * @property int $is_show
 * @property int $type 0 顶级 1 次级分类
 * @property string|null $link
 * @property int $sort 排序
 * @property int|null $updated_at
 * @property int|null $created_at
 * @property int|null $deleted_at
 * @property int $is_delete
 * @property int $mch_id
 * @property int $parent_id
 */
class Cat extends BaseActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return '{{%cat}}';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['name'], 'required'],
            [['mall_id', 'is_show', 'type', 'sort', 'updated_at', 'created_at', 'deleted_at', 'is_delete', 'mch_id', 'parent_id'], 'integer'],
            [['name'], 'string', 'max' => 45],
            [['cover_pic', 'adv_pic'], 'string', 'max' => 255],
            [['link'], 'string', 'max' => 128],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'name' => 'Name',
            'mall_id' => 'Mall ID',
            'cover_pic' => '缩略图',
            'adv_pic' => '广告图',
            'is_show' => 'Is Show',
            'type' => ' 0 顶级 1 次级分类',
            'link' => 'Link',
            'sort' => '排序',
            'updated_at' => 'Updated At',
            'created_at' => 'Created At',
            'deleted_at' => 'Deleted At',
            'is_delete' => 'Is Delete',
            'mch_id' => '商户Id'
        ];
    }
}
