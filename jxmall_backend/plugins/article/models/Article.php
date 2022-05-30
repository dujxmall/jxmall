<?php


namespace app\plugins\article\models;

use app\models\BaseActiveRecord;

use Yii;

/**
 * This is the model class for table "{{%plugin_article}}".
 *
 * @property int $id
 * @property int $mall_id
 * @property string $title
 * @property string|null $created_by 由谁创建
 * @property string $detail
 * @property string $video
 * @property string $cover_pic
 * @property int|null $deleted_at
 * @property int|null $updated_at
 * @property int|null $created_at
 * @property int $is_delete
 * @property int|null $cat_id
 * @property int $views
 * @property integer $sort
 * @property integer $is_published
 */
class Article extends BaseActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return '{{%plugin_article}}';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['mall_id', 'title', 'detail'], 'required'],
            [['mall_id', 'deleted_at', 'updated_at', 'created_at', 'is_delete', 'cat_id', 'views', 'sort', 'is_published'], 'integer'],
            [['detail'], 'string'],
            [['title', 'created_by'], 'string', 'max' => 45],
            [['video', 'cover_pic'], 'string', 'max' => 255],
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
            'title' => 'Title',
            'created_by' => '由谁创建',
            'detail' => 'Detail',
            'video' => 'Video',
            'cover_pic' => 'Cover Pic',
            'deleted_at' => 'Deleted At',
            'updated_at' => 'Updated At',
            'created_at' => 'Created At',
            'is_delete' => 'Is Delete',
            'cat_id' => 'Cat ID',
            'views' => '阅读量'
        ];
    }
}
