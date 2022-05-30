<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "{{%address}}".
 *
 * @property int $id
 * @property string|null $province_code
 * @property string|null $province_name
 * @property string|null $city_code
 * @property string|null $city_name
 * @property string|null $county_code
 * @property string|null $county_name
 * @property string|null $town_code
 * @property string|null $town_name
 */
class Address extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return '{{%address}}';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['province_code', 'city_code', 'county_code', 'town_code'], 'string', 'max' => 15],
            [['province_name', 'city_name', 'county_name', 'town_name'], 'string', 'max' => 45],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'province_code' => 'Province Code',
            'province_name' => 'Province Name',
            'city_code' => 'City Code',
            'city_name' => 'City Name',
            'county_code' => 'County Code',
            'county_name' => 'County Name',
            'town_code' => 'Town Code',
            'town_name' => 'Town Name',
        ];
    }
}
