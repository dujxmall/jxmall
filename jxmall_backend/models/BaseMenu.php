<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "{{%base_menu}}".
 *
 * @property int $id
 * @property int|null $menu_level 菜单级别
 * @property string|null $parent_id 父级ID
 * @property string|null $path 路由path
 * @property string|null $name 路由name
 * @property int|null $hidden 是否在列表隐藏
 * @property string|null $component 对应前端文件路径
 * @property int|null $sort 排序标记
 * @property int|null $keep_alive 附加属性
 * @property int|null $default_menu 附加属性
 * @property string|null $title 标题
 * @property string|null $icon 图标
 * @property int|null $close_tab 附加属性
 * @property int|null $updated_at 更新时间
 * @property int $deleted_at 删除时间
 * @property int|null $created_at 创建时间
 * @property int|null $is_delete 是否删除
 * @property string $created_time 创建时间
 */
class BaseMenu extends BaseActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return '{{%base_menu}}';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['menu_level', 'hidden', 'sort', 'keep_alive', 'default_menu', 'close_tab', 'updated_at', 'deleted_at', 'created_at', 'is_delete'], 'integer'],
            [['created_time'], 'safe'],
            [['parent_id'], 'string', 'max' => 90],
            [['path', 'name', 'component', 'title', 'icon'], 'string', 'max' => 255],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'menu_level' => 'Menu Level',
            'parent_id' => 'Parent ID',
            'path' => 'Path',
            'name' => 'Name',
            'hidden' => 'Hidden',
            'component' => 'Component',
            'sort' => 'Sort',
            'keep_alive' => 'Keep Alive',
            'default_menu' => 'Default Menu',
            'title' => 'Title',
            'icon' => 'Icon',
            'close_tab' => 'Close Tab',
            'updated_at' => 'Updated At',
            'deleted_at' => 'Deleted At',
            'created_at' => 'Created At',
            'is_delete' => 'Is Delete',
            'created_time' => 'Created Time',
        ];
    }
}
