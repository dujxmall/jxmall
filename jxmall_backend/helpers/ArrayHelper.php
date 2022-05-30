<?php
/**
 * @link:http://www.dujxmall.com/
 * @copyright: Copyright (c) 2020 广州动力宇宙信息科技
 * Created by PhpStorm
 * Author: ganxiaohao
 * Date: 2020-10-28
 * Time: 0:50
 */

namespace app\helpers;


use yii\helpers\BaseArrayHelper;

class ArrayHelper extends BaseArrayHelper
{

    /**
     * @Author: 动力宇宙 ganxiaohao
     * @Date: 2020-10-28
     * @Time: 0:50
     * @Note:递归数组
     * @param array $items
     * @param int $pid
     * @param string $idField
     * @param string $pidField
     * @param string $child
     * @return array
     */
    public static function itemsMerge(array $items, $pid = 0, $idField = "id", $pidField = 'pid', $child = '-')
    {
        $arr = [];
        foreach ($items as $v) {
            if ($v[$pidField] == $pid) {
                $v[$child] = self::itemsMerge($items, $v[$idField], $idField, $pidField);
                $arr[] = $v;
            }
        }

        return $arr;
    }


    /**
     * @Author: 动力宇宙 ganxiaohao
     * @Date: 2020-10-28
     * @Time: 16:11
     * @Note:传递一个子分类ID返回所有的父级分类
     * @param array $items
     * @param $id
     * @return array
     */
    public static function getParents(array $items, $id)
    {
        $arr = [];
        foreach ($items as $v) {
            if ($v['id'] == $id) {
                $arr[] = $v;
                $arr = array_merge(self::getParents($items, $v['pid']), $arr);
            }
        }

        return $arr;
    }


    /**
     * @Author: 动力宇宙 ganxiaohao
     * @Date: 2020-10-28
     * @Time: 16:11
     * @Note:传递一个父级分类ID返回所有子分类
     * @param $cate
     * @param $pid
     * @return array
     */
    public static function getChilds($cate, $pid)
    {
        $arr = [];
        foreach ($cate as $v) {
            if ($v['pid'] == $pid) {
                $arr[] = $v;
                $arr = array_merge($arr, self::getChilds($cate, $v['id']));
            }
        }

        return $arr;
    }


    /**
     * @Author: 动力宇宙 ganxiaohao
     * @Date: 2020-10-28
     * @Time: 16:11
     * @Note:传递一个父级分类ID返回所有子分类ID
     * @param $cate
     * @param $pid
     * @param string $idField
     * @param string $pidField
     * @return array
     */
    public static function getChildIds($cate, $pid, $idField = "id", $pidField = 'pid')
    {
        $arr = [];
        foreach ($cate as $v) {
            if ($v[$pidField] == $pid) {
                $arr[] = $v[$idField];
                $arr = array_merge($arr, self::getChildIds($cate, $v[$idField], $idField, $pidField));
            }
        }

        return $arr;
    }


    /**
     * @Author: 动力宇宙 ganxiaohao
     * @Date: 2020-10-28
     * @Time: 16:11
     * @Note: php二维数组排序 按照指定的key 对数组进行排序
     * @param  array $arr 将要排序的数组
     * @param string $keys 指定排序的key
     * @param string $type 排序类型 asc | desc
     * @return array
     */
    public static function arraySort($arr, $keys, $type = 'asc')
    {
        if (count($arr) <= 1) {
            return $arr;
        }

        $keysValue = [];
        $newArray = [];

        foreach ($arr as $k => $v) {
            $keysValue[$k] = $v[$keys];
        }

        $type == 'asc' ? asort($keysValue) : arsort($keysValue);
        reset($keysValue);
        foreach ($keysValue as $k => $v) {
            $newArray[$k] = $arr[$k];
        }

        return $newArray;
    }


    /**
     * @Author: 动力宇宙 ganxiaohao
     * @Date: 2020-04-22
     * @Time: 16:12
     * @Note:获取数组指定的字段为key
     * @param array $arr 数组
     * @param $field 要成为key的字段名
     * @return array
     */
    public static function arrayKey(array $arr, $field)
    {
        $newArray = [];
        foreach ($arr as $value) {
            isset($value[$field]) && $newArray[$value[$field]] = $value;
        }

        return $newArray;
    }


    /**
     * @Author: 动力宇宙 ganxiaohao
     * @Date: 2020-04-22
     * @Time: 16:12
     * @Note:移除数组内某个key的值为传递的值
     * @param array $array
     * @param $value
     * @param string $key
     * @return array
     */
    public static function removeByValue(array $array, $value, $key = 'id')
    {
        foreach ($array as $index => $item) {
            if ($item[$key] == $value) {
                unset($array[$index]);
            }
        }

        return $array;
    }


    /**
     * @Author: 动力宇宙 ganxiaohao
     * @Date: 2020-04-22
     * @Time: 16:12
     * @Note: 获取数字区间
     * @param int $start
     * @param int $end
     * @param bool $key
     * @return array
     */
    public static function numBetween($start = 0, $end = 1, $key = true)
    {
        $arr = [];
        for ($i = $start; $i <= $end; $i++) {
            $key == true ? $arr[$i] = $i : $arr[] = $i;
        }

        return $arr;
    }


    /**
     * @Author: 动力宇宙 ganxiaohao
     * @Date: 2020-04-22
     * @Time: 16:12
     * @Note: 根据级别和数组返回字符串
     * @param $level 级别
     * @param array $models
     * @param $k
     * @param int $treeStat 开始计算
     * @return bool|string
     */
    public static function itemsLevel($level, array $models, $k, $treeStat = 1)
    {
        $str = '';
        for ($i = 1; $i < $level; $i++) {
            $str .= '　　';

            if ($i == $level - $treeStat) {
                if (isset($models[$k + 1])) {
                    return $str . "├──";
                }

                return $str . "└──";
            }
        }

        return false;
    }

    /**
     * @Author: 动力宇宙 ganxiaohao
     * @Date: 2020-04-22
     * @Time: 16:12
     * @Note: 必须经过递归才能进行重组为下拉框
     * @param $models
     * @param string $idField
     * @param string $titleField
     * @param int $treeStat
     * @return array
     */
    public static function itemsMergeDropDown($models, $idField = 'id', $titleField = 'title', $treeStat = 1)
    {
        $arr = [];
        foreach ($models as $k => $model) {
            $arr[] = [
                $idField => $model[$idField],
                $titleField => self::itemsLevel($model['level'], $models, $k, $treeStat) . " " . $model[$titleField],
            ];

            if (!empty($model['-'])) {
                $arr = ArrayHelper::merge($arr,
                    self::itemsMergeDropDown($model['-'], $idField, $titleField, $treeStat));
            }
        }

        return $arr;
    }


    /**
     * @Author: 动力宇宙 ganxiaohao
     * @Date: 2020-04-22
     * @Time: 16:12
     * @Note: 匹配ip在ip数组内支持通配符
     * @param $ip
     * @param $allowedIPs
     * @return bool
     */
    public static function ipInArray($ip, $allowedIPs)
    {
        foreach ($allowedIPs as $filter) {
            if ($filter === '*' || $filter === $ip || (($pos = strpos($filter, '*')) !== false && !strncmp($ip, $filter, $pos))) {
                return true;
            }
        }

        return false;
    }


    /**
     * @Author: 动力宇宙 ganxiaohao
     * @Date: 2020-04-22
     * @Time: 16:13
     * @Note: 获取递归的第一个数据
     * @param array $array
     * @return bool|mixed
     */
    public static function getFirstRowByItemsMerge(array $array)
    {
        foreach ($array as $item) {
            if (!empty($item['-'])) {
                return self::getFirstRowByItemsMerge($item['-']);
            } else {
                return $item;
            }
        }

        return false;
    }


    /**
     * @Author: 动力宇宙 ganxiaohao
     * @Date: 2020-04-22
     * @Time: 16:13
     * @Note:获取所有没有子级的数据
     * @param array $array
     * @return array
     */
    public static function getNotChildRowsByItemsMerge(array $array)
    {
        $arr = [];

        foreach ($array as $item) {
            if (!empty($item['-'])) {
                $arr = array_merge($arr, self::getNotChildRowsByItemsMerge($item['-']));
            } else {
                $arr[] = $item;
            }
        }

        return $arr;
    }


    /**
     * @Author: 动力宇宙 ganxiaohao
     * @Date: 2020-04-22
     * @Time: 16:13
     * @Note:   数组转xml
     * 微信回调成功：['return_code' => 'SUCCESS', 'return_msg' => 'OK']
     * 微信回调失败：['return_code' => 'FAIL', 'return_msg' => 'OK']
     * @param $arr
     * @return bool|string
     */
    public static function toXml($arr)
    {
        if (!is_array($arr) || count($arr) <= 0) {
            return false;
        }

        $xml = "<xml>";
        foreach ($arr as $key => $val) {
            if (is_numeric($val)) {
                $xml .= "<" . $key . ">" . $val . "</" . $key . ">";
            } else {
                $xml .= "<" . $key . "><![CDATA[" . $val . "]]></" . $key . ">";
            }
        }

        $xml .= "</xml>";
        return $xml;
    }

}