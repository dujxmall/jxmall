<?php
/**
 * @link:http://www.dujxmall.com/
 * @copyright: Copyright (c) 2020 广州动力宇宙信息科技
 * Created by PhpStorm
 * Author: ganxiaohao
 * Date: 2020-10-03
 * Time: 9:50
 */

namespace app\helpers;


use app\models\Address;
use app\mongo\JxmallAddressTree;

class AddressHelper
{
    const ADDRESS_DATA = 'address_data';
    const AREA_DATA = 'area_data';

    public static function getProvince($province_code)
    {
        $address = Address::findOne(['province_code' => $province_code]);
        if ($address) {
            return $address->province_name;
        }
        return null;
    }


    public static function getCity($city_code)
    {
        $address = Address::findOne(['city_code' => $city_code]);
        if ($address) {
            return $address->province_name;
        }
        return null;

    }


    public static function getCounty($county_code)
    {
        $address = Address::findOne(['county_code' => $county_code]);
        if ($address) {
            return $address->county_name;
        }
        return null;
    }


    public static function getTown($town_code)
    {
        $address = Address::findOne(['town_code' => $town_code]);
        if ($address) {
            return $address->town_name;
        }
        return null;
    }

    public static function getArea($province_code, $city_code, $county_code, $town_code)
    {
        if ($town_code) {
            $address = Address::findOne(['county_code' => $county_code, 'province_code' => $province_code, 'city_code' => $city_code]);
        } else {
            $address = Address::findOne(['county_code' => $county_code, 'province_code' => $province_code, 'city_code' => $city_code, 'town_code' => $town_code]);
        }
        if ($address) {
            return $address;
        }
        return null;
    }

    public static function getTownList($county_code)
    {
        $list = Address::find()->where(['county_code' => $county_code])->select('town_code  as code,town_name as name')->asArray()->all();
        foreach ($list as &$town) {
            $town['level'] = 4;
        }
        unset($town);
        return $list;
    }


    public static function getAreaData()
    {
        $data = CacheHelper::get(AddressHelper::AREA_DATA);
        if ($data) {
            return $data;
        }
        $list = Address::find()->groupBy('province_code')->select('province_code as code,province_name as name')->asArray()->all();
        foreach ($list as &$province) {
            $province['level'] = 1;
            $province['list'] = Address::find()->where(['province_code' => $province['code']])->groupBy('city_code')->select('city_code as code,city_name as name')->asArray()->all();
            foreach ($province['list'] as &$city) {
                $city['level'] = 2;
                $city['list'] = Address::find()->where(['city_code' => $city['code']])->groupBy('county_code')->select('county_code  as code,county_name as name')->asArray()->all();
                foreach ($city['list'] as &$county) {
                    $county['level'] = 3;
                }
                unset($county);
            }
            unset($city);
        }
        unset($province);
        CacheHelper::set(AddressHelper::AREA_DATA, $list, null);
        return $list;
    }

    public static function getAddressData()
    {

        $address_data = CacheHelper::get(AddressHelper::ADDRESS_DATA);
        if ($address_data) {
            return $address_data;
        }
        $list = Address::find()->groupBy('province_code')->select('province_code as code,province_name as name')->asArray()->all();
        foreach ($list as &$province) {
            $province['level'] = 1;
            $province['list'] = Address::find()->where(['province_code' => $province['code']])->groupBy('city_code')->select('city_code as code,city_name as name')->asArray()->all();
            foreach ($province['list'] as &$city) {
                $city['level'] = 2;
                $city['list'] = Address::find()->where(['city_code' => $city['code']])->groupBy('county_code')->select('county_code  as code,county_name as name')->asArray()->all();
                foreach ($city['list'] as &$county) {
                    $county['level'] = 3;
                    $county['list'] = Address::find()->where(['county_code' => $county['code']])->groupBy('town_code')->select('town_code  as code,town_name as name')->asArray()->all();
                    foreach ($county['list'] as &$town) {
                        $town['level'] = 4;
                    }
                }
                unset($county);
            }
            unset($city);
        }
        unset($province);
        /* foreach ($list as $item) {
             $model = new JxmallAddressTree();
             $model->code = $item['code'];
             $model->name=$item['name'];
             $model->list=$item['list'];
             $model->level=$item['level'];
             $model->save();
         }*/
       CacheHelper::set(AddressHelper::ADDRESS_DATA, $list, null);
        return $list;
    }


}
