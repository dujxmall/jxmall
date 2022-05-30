<?php

namespace app\models;

use Yii;
use yii\helpers\BaseArrayHelper;

/**
 * This is the model class for table "{{%plugin}}".
 *
 * @property int $id
 * @property string $name
 * @property string $label
 * @property string $version
 * @property int|null $created_at
 * @property int|null $deleted_at
 * @property int|null $updated_at
 * @property int $is_delete
 * @property int $type 0 分润类 1营销类 2工具
 * @property int $install_type 0本地 1在线
 * @property string|null $logo
 * @property string $description
 */
class Plugin extends BaseActiveRecord
{


    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return '{{%plugin}}';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['name', 'label', 'version'], 'required'],
            [['created_at', 'deleted_at', 'updated_at', 'is_delete', 'type', 'install_type'], 'integer'],
            [['name', 'label'], 'string', 'max' => 45],
            [['logo'], 'string'],
            [['description'], 'string', 'max' => 255],
            [['version'], 'string', 'max' => 10],
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
            'label' => 'Label',
            'version' => 'Version',
            'created_at' => 'Created At',
            'deleted_at' => 'Deleted At',
            'updated_at' => 'Updated At',
            'is_delete' => 'Is Delete',
            'type' => '0 分润类 1营销类 2工具',
            'install_type' => '0本地 1在线',
            'logo' => 'logo'
        ];
    }
}
