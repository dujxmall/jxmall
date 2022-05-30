<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "{{%user_address}}".
 *
 * @property int $id
 * @property int $mall_id
 * @property int $user_id
 * @property string $name
 * @property string $address
 * @property int $is_delete
 * @property int|null $created_at
 * @property int|null $deleted_at
 * @property int|null $updated_at
 * @property string $province_code
 * @property string $city_code
 * @property string $county_code
 * @property string $town_code
 * @property int $is_default
 * @property string|null $province_name
 * @property string|null $city_name
 * @property string|null $county_name
 * @property string|null $town_name
 * @property string|null $mobile
 * @property string|null $area
 * @property string|null $detail
 */
class UserAddress extends BaseActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return '{{%user_address}}';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['mall_id', 'user_id', 'address', 'name', 'mobile'], 'required'],
            [['mall_id', 'user_id', 'is_delete', 'created_at', 'deleted_at', 'updated_at', 'is_default'], 'integer'],
            [['address'], 'string', 'max' => 255],
            [['mobile'], 'string', 'max' => 11],
            [['province_code', 'city_code', 'county_code', 'town_code'], 'string', 'max' => 20],
            [['province_name', 'city_name', 'county_name', 'town_name'], 'string', 'max' => 45],
            [['area', 'address', 'detail'], 'string', 'max' => 64],
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
            'user_id' => 'User ID',
            'address' => 'Address',
            'is_delete' => 'Is Delete',
            'created_at' => 'Created At',
            'deleted_at' => 'Deleted At',
            'updated_at' => 'Updated At',
            'province_code' => 'Province Code',
            'city_code' => 'City Code',
            'county_code' => 'County Code',
            'town_code' => 'Town Code',
            'is_default' => 'Is Default',
            'province_name' => 'Province Name',
            'city_name' => 'City Name',
            'county_name' => 'County Name',
            'town_name' => 'Town Name',
            'mobile' => 'Mobile',
            'name' => 'Name',
            'detail' => '详细地址',
            'area' => '所属区域'
        ];
    }
}
