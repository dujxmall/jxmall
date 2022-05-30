<?php
/**
 * @link:http://www.dujxmall.com/
 * @copyright: Copyright (c) 2020 广州动力宇宙信息科技
 * Created by PhpStorm
 * Author: ganxiaohao
 * Date: 2020-10-03
 * Time: 11:51
 */

namespace app\helpers;


class ExpressHelper
{

    public static function eorderStyle()
    {

        //电子面单对应的模板
        return [
            'ANE' => [
                [
                    'label' => '二联 180 (宽100mm 高180mm 切点110/70)',
                    'value' => '',
                ],
            ],
            'ANEKY' => [
                [
                    'label' => '二联 180 (宽100mm 高180mm 切点110/70)',
                    'value' => '',
                ],
            ],
            'CND' => [
                [
                    'label' => '二联 180 (宽 100mm)',
                    'value' => '',
                ],
            ],
            'DBL' => [
                [
                    'label' => '二联 177 (宽 100mm 高 177mm 切点 107/70)',
                    'value' => '',
                ],
                [
                    'label' => '二联 177 新 (宽 100mm 高 177mm 切点 107/70)',
                    'value' => '18001',
                ],
                [
                    'label' => '三联 177 新 (宽 100mm 高 177mm 切点 107/30/40)',
                    'value' => '18002',
                ],
                [
                    'label' => '一联 130 (宽 76mm 高 130mm)',
                    'value' => '130',
                ],
            ],
            'DBLKY' => [
                [
                    'label' => '三联 180 (宽 100mm 高180mm 切点 110/30/40)',
                    'value' => '',
                ],
            ],
            'DNWL' => [
                [
                    'label' => '一联 150 (宽 100mm 高 150mm 切点 90/60)',
                    'value' => '',
                ],
            ],
            'EST365' => [
                [
                    'label' => '一联 120 (宽 100mm 高 120mm)',
                    'value' => '',
                ],
            ],
            'EMS' => [
                [
                    'label' => '二联 150 (宽 100mm 高 150mm 切点 90/60)',
                    'value' => '',
                ],
                [
                    'label' => '二联 180 (宽 100mm 高180mm 切点 110/70)',
                    'value' => '180',
                ],
            ],
            'HTKY' => [
                [
                    'label' => '二联 180 (宽 100mm 高 180mm 切点 110/70)',
                    'value' => '',
                ],
                [
                    'label' => '二联 180 新 (宽 100mm 高 180mm 切点 110/70)',
                    'value' => '180',
                ],
                [
                    'label' => '一联 130 (宽 76mm 高 130mm)',
                    'value' => '130',
                ],
            ],
            'HTKYKY' => [
                [
                    'label' => '二联 180 (宽 100mm 高 180mm 切点 110/70)',
                    'value' => '',
                ],
            ],
            'CNEX' => [
                [
                    'label' => '一联 100 (宽 90mm 高 100mm)',
                    'value' => '',
                ],
            ],
            'JD' => [
                [
                    'label' => '二联 110 (宽 100mm 高 110mm 切点 60/50)',
                    'value' => '',
                ],
                [
                    'label' => '二联 110 新 (宽 100mm 高 110mm 切点 60/50)',
                    'value' => '110',
                ],
            ],
            'JDKY' => [
                [
                    'label' => '二联 110 (宽 100mm 高 110mm 切点 60/50)',
                    'value' => '',
                ],
            ],
            'JTSD' => [
                [
                    'label' => '一联 130 (宽 76mm 高 130mm)',
                    'value' => '139',
                ],
                [
                    'label' => '二联 180 (宽 100mm 高 180mm 切点 110/70)',
                    'value' => '',
                ],
            ],
            'KYSY' => [
                [
                    'label' => '二联 137 (宽 100mm 高 137mm 切点 101/36)',
                    'value' => '',
                ],
                [
                    'label' => '三联 210 (宽 100mm 高 210mm 切点 90/60/60)',
                    'value' => '210',
                ],
            ],
            'LB' => [
                [
                    'label' => '三联 104 (宽 75mm 高 104mm)',
                    'value' => '',
                ],
            ],
            'LHT' => [
                [
                    'label' => '二联 150 (宽 100mm 高 150mm 切点 90/60)',
                    'value' => '',
                ],
            ],
            'PJ' => [
                [
                    'label' => '一联 120 (宽 80mm 高 120mm)',
                    'value' => '',
                ],
            ],
            'UAPEX' => [
                [
                    'label' => '二联 150 (宽 100mm 高 150mm 切点 90/60)',
                    'value' => '',
                ],
            ],
            'SF' => [
                [
                    'label' => '二联 150 新 (宽 100mm 高 150mm 切点 90/60)',
                    'value' => '15001',
                ],
                [
                    'label' => '二联 180 新 (宽 100mm 高 180mm 切点 110/70)',
                    'value' => '180',
                ],
                [
                    'label' => '三联 210 新 (宽 100mm 高 210mm 切点 90/60/60)',
                    'value' => '21001',
                ],
            ],
            'STO' => [
                [
                    'label' => '二联 150 (宽 100mm 高 150mm 切点 90/60)',
                    'value' => '150',
                ],
                [
                    'label' => '二联 180 (宽 100mm 高 180mm 切点 110/70)',
                    'value' => '',
                ],
                [
                    'label' => '二联 180 新 (宽 100mm 高 180mm 切点 110/70)',
                    'value' => '180',
                ],
                [
                    'label' => '三联 180 新 (宽 100mm 高 180mm 切点 110/30/40)',
                    'value' => '18003',
                ],
                [
                    'label' => '一联 130 (宽 76mm 高 130mm)',
                    'value' => '130',
                ],
            ],
            'SURE' => [
                [
                    'label' => '二联 150 (宽 100mm 高 150mm 切点 90/60)',
                    'value' => '',
                ],
                [
                    'label' => '二联 150 新 (宽 100mm 高 150mm 切点 90/60)',
                    'value' => '150',
                ],
                [
                    'label' => '二联 180 新 (宽 100mm 高 180mm 切点 110/70)',
                    'value' => '180',
                ],
            ],
            'SX' => [
                [
                    'label' => '一联 105 (宽 75mm 高 105mm)',
                    'value' => '',
                ],
            ],
            'SNWL' => [
                [
                    'label' => '二联 180 (宽 100mm 高 180mm 切点 110/70)',
                    'value' => '',
                ],
            ],
            'TLWL' => [
                [
                    'label' => '一联 70 (宽 100mm 高 70mm)',
                    'value' => '',
                ],
            ],
            'HOAU' => [
                [
                    'label' => '二联 180 (宽 100mm 高 180mm 切点 110/70)',
                    'value' => '',
                ],
            ],
            'HHTT' => [
                [
                    'label' => '二联 180 (宽 100mm 高 180mm 切点 110/70)',
                    'value' => '',
                ],
            ],
            'XFEX' => [
                [
                    'label' => '二联 150 (宽 100mm 高 150mm 切点 90/60)',
                    'value' => '',
                ],
            ],
            'YD' => [
                [
                    'label' => '二联 203 (宽 100mm 高 203mm 切点 152/51)',
                    'value' => '',
                ],
                [
                    'label' => '二联 180 (宽 100mm 高 180mm 切点 110/70)',
                    'value' => '180',
                ],
                [
                    'label' => '一联 130 (宽 76mm 高 130mm)',
                    'value' => '130',
                ],
            ],
            'YDKY' => [
                [
                    'label' => '二联 180 (宽 100mm 高 180mm 切点 110/70)',
                    'value' => '',
                ],
            ],
            'YTO' => [
                [
                    'label' => '二联 180 (宽 100mm 高 180mm 切点 110/70)',
                    'value' => '',
                ],
                [
                    'label' => '三联 180 (宽 100mm 高 180mm 切点 110/30/40)',
                    'value' => '180',
                ],
                [
                    'label' => '二联 180 新 (宽 100mm 高 180mm 切点 110/70)',
                    'value' => '18001',
                ],
                [
                    'label' => '一联 130 (宽 76mm 高 130mm)',
                    'value' => '130',
                ],
            ],
            'YZBK' => [
                [
                    'label' => '二联 150 (宽 100mm 高 150mm 切点 90/60)',
                    'value' => '',
                ],
            ],
            'YZPY' => [
                [
                    'label' => '二联 180 (宽 100mm 高 180mm 切点 110/70)',
                    'value' => '',
                ],
                [
                    'label' => '二联 180 新 (宽 100mm 高 180mm 切点 110/70)',
                    'value' => '180',
                ],
            ],
            'UC' => [
                [
                    'label' => '二联 180 (宽 100mm 高 180mm 切点 110/70)',
                    'value' => '',
                ],
            ],
            'YCWL' => [
                [
                    'label' => '二联 180 (宽 100mm 高 180mm 切点 110/70)',
                    'value' => '',
                ],
            ],
            'YMDD' => [
                [
                    'label' => '二联 180 (宽 100mm 高 180mm 切点 110/70)',
                    'value' => '',
                ],
            ],
            'ZJS' => [
                [
                    'label' => '二联 120 (宽 100mm 高 116mm 切点 98/18)',
                    'value' => '',
                ],
                [
                    'label' => '二联 180 (宽 100m 高 180mm 切点 110/70)',
                    'value' => '180',
                ],
                [
                    'label' => '二联 120 新 (宽 100mm 高 116mm 切点 98/18)',
                    'value' => '120',
                ],
            ],
            'ZTO' => [
                [
                    'label' => '二联 180 (宽 100mm 高 180mm 切点 110/70)',
                    'value' => '',
                ],
                [
                    'label' => '二联 180 新 (宽 100mm 高 180mm 切点 110/70)',
                    'value' => '180',
                ],
                [
                    'label' => '一联 130 (宽 76mm 高 130mm)',
                    'value' => '130',
                ],
            ],
            'ZTOKY' => [
                [
                    'label' => '二联 180 (宽 100mm 高 180mm 切点 110/70)',
                    'value' => '',
                ],
            ],
        ];
    }

    public static function express()
    {

        return [
            [
                'id' => '1',
                'name' => '顺丰',
                'code' => 'shunfeng',
                'key' => 'SF',
                'ali_key' => 'SF',
                'r_datas' => '[{"style":"默认风格","spec":"默认风格","size":"150","isdefault":1}]',
            ],
            [
                'id' => '2',
                'name' => '申通',
                'code' => 'shentong',
                'key' => 'STO',
                'ali_key' => 'STO',
                'r_datas' => '[{"style":"默认风格","spec":"默认风格","size":"150","isdefault":1}]',
            ],
            [
                'id' => '3',
                'name' => '韵达快运',
                'code' => 'yunda',
                'key' => 'YD',
                'ali_key' => 'YD',
                'r_datas' => '[{"style":"默认风格","spec":"默认风格","size":"150","isdefault":1}]',
            ],
            [
                'id' => '4',
                'name' => '天天快递',
                'code' => 'tiantian',
                'key' => 'HHTT',
                'ali_key' => 'HHTT',
                'r_datas' => '[{"style":"默认风格","spec":"默认风格","size":"150","isdefault":1}]',
            ],
            [
                'id' => '5',
                'name' => '圆通速递',
                'code' => 'yuantong',
                'key' => 'YTO',
                'ali_key' => 'YTO',
                'r_datas' => '[{"style":"默认风格","spec":"默认风格","size":"150","isdefault":1}]',
            ],
            [
                'id' => '6',
                'name' => '中通速递',
                'code' => 'zhongtong',
                'key' => 'ZTO',
                'ali_key' => 'ZTO',
                'r_datas' => '[{"style":"默认风格","spec":"默认风格","size":"150","isdefault":1}]',
            ],
            [
                'id' => '7',
                'name' => 'ems快递',
                'code' => 'ems',
                'key' => 'EMS',
                'ali_key' => 'EMS',
                'r_datas' => '[{"style":"默认风格","spec":"默认风格","size":"150","isdefault":1}]',
            ],
            [
                'id' => '8',
                'name' => '百世快递',
                'code' => 'huitongkuaidi',
                'key' => 'HTKY',
                'ali_key' => 'HTKY',
                'r_datas' => '[{"style":"默认风格","spec":"默认风格","size":"150","isdefault":1}]',
            ],
            [
                'id' => '9',
                'name' => '全峰快递',
                'code' => 'quanfengkuaidi',
                'key' => 'QFKD',
                'ali_key' => 'QFKD',
                'r_datas' => '[{"style":"默认风格","spec":"默认风格","size":"150","isdefault":1}]',
            ],
            [
                'id' => '10',
                'name' => '宅急送',
                'code' => 'zhaijisong',
                'key' => 'ZJS',
                'ali_key' => 'ZJS',
                'r_datas' => '[{"style":"默认风格","spec":"默认风格","size":"150","isdefault":1}]',
            ],
            [
                'id' => '11',
                'name' => 'aae全球专递',
                'code' => 'aae',
                'key' => 'AAE',
                'ali_key' => 'AAE',
                'r_datas' => '[{"style":"默认风格","spec":"默认风格","size":"150","isdefault":1}]',
            ],
            [
                'id' => '12',
                'name' => '安捷快递',
                'code' => 'anjie',
                'key' => 'AJ',
                'ali_key' => 'AJ',
                'r_datas' => '[{"style":"默认风格","spec":"默认风格","size":"150","isdefault":1}]',
            ],
            [
                'id' => '13',
                'name' => '安信达快递',
                'code' => 'anxindakuaixi',
                'key' => 'anxindakuaixi',
                'ali_key' => 'AXD',
                'r_datas' => '[{"style":"默认风格","spec":"默认风格","size":"150","isdefault":1}]',
            ],
            [
                'id' => '14',
                'name' => '彪记快递',
                'code' => 'biaojikuaidi',
                'key' => 'biaojikuaidi',
                'ali_key' => 'biaojikuaidi',
                'r_datas' => '[{"style":"默认风格","spec":"默认风格","size":"150","isdefault":1}]',
            ],
            [
                'id' => '15',
                'name' => 'bht',
                'code' => 'bht',
                'key' => 'BHT',
                'ali_key' => 'BHT',
                'r_datas' => '[{"style":"默认风格","spec":"默认风格","size":"150","isdefault":1}]',
            ],
            [
                'id' => '16',
                'name' => '百福东方国际物流',
                'code' => 'baifudongfang',
                'key' => 'BFDF',
                'ali_key' => 'BFDF',
                'r_datas' => '[{"style":"默认风格","spec":"默认风格","size":"150","isdefault":1}]',
            ],
            [
                'id' => '17',
                'name' => '中国东方（COE）',
                'code' => 'coe',
                'key' => 'COE',
                'ali_key' => 'COE',
                'r_datas' => '[{"style":"默认风格","spec":"默认风格","size":"150","isdefault":1}]',
            ],
            [
                'id' => '18',
                'name' => '长宇物流',
                'code' => 'changyuwuliu',
                'key' => 'changyuwuliu',
                'ali_key' => 'changyuwuliu',
                'r_datas' => '[{"style":"默认风格","spec":"默认风格","size":"150","isdefault":1}]',
            ],
            [
                'id' => '19',
                'name' => '大田物流',
                'code' => 'datianwuliu',
                'key' => 'DTWL',
                'ali_key' => 'DTWL',
                'r_datas' => '[{"style":"默认风格","spec":"默认风格","size":"150","isdefault":1}]',
            ],
            [
                'id' => '20',
                'name' => '德邦物流',
                'code' => 'debangwuliu',
                'key' => 'DBL',
                'ali_key' => 'DBL',
                'r_datas' => '[{"style":"默认风格","spec":"默认风格","size":"150","isdefault":1}]',
            ],
            [
                'id' => '21',
                'name' => 'dhl',
                'code' => 'dhl',
                'key' => 'DHL',
                'ali_key' => 'DHL',
                'r_datas' => '[{"style":"默认风格","spec":"默认风格","size":"150","isdefault":1}]',
            ],
            [
                'id' => '22',
                'name' => 'dpex',
                'code' => 'dpex',
                'key' => 'DPEX',
                'ali_key' => 'DPEX',
                'r_datas' => '[{"style":"默认风格","spec":"默认风格","size":"150","isdefault":1}]',
            ],
            [
                'id' => '23',
                'name' => 'd速快递',
                'code' => 'dsukuaidi',
                'key' => 'dsukuaidi',
                'ali_key' => 'DSWL',
                'r_datas' => '[{"style":"默认风格","spec":"默认风格","size":"150","isdefault":1}]',
            ],
            [
                'id' => '24',
                'name' => '递四方',
                'code' => 'disifang',
                'key' => 'disifang',
                'ali_key' => 'D4PX',
                'r_datas' => '[{"style":"默认风格","spec":"默认风格","size":"150","isdefault":1}]',
            ],
            [
                'id' => '25',
                'name' => 'fedex（国外）',
                'code' => 'fedex',
                'key' => 'FEDEX_GJ',
                'ali_key' => 'FEDEX_GJ',
                'r_datas' => '[{"style":"默认风格","spec":"默认风格","size":"150","isdefault":1}]',
            ],
            [
                'id' => '26',
                'name' => '飞康达物流',
                'code' => 'feikangda',
                'key' => 'FKD',
                'ali_key' => 'FKD',
                'r_datas' => '[{"style":"默认风格","spec":"默认风格","size":"150","isdefault":1}]',
            ],
            [
                'id' => '27',
                'name' => '凤凰快递',
                'code' => 'fenghuangkuaidi',
                'key' => 'fenghuangkuaidi',
                'ali_key' => 'fenghuangkuaidi',
                'r_datas' => '[{"style":"默认风格","spec":"默认风格","size":"150","isdefault":1}]',
            ],
            [
                'id' => '28',
                'name' => '飞快达',
                'code' => 'feikuaida',
                'key' => 'feikuaida',
                'ali_key' => 'feikuaida',
                'r_datas' => '[{"style":"默认风格","spec":"默认风格","size":"150","isdefault":1}]',
            ],
            [
                'id' => '29',
                'name' => '国通快递',
                'code' => 'guotongkuaidi',
                'key' => 'GTO',
                'ali_key' => 'GTO',
                'r_datas' => '[{"style":"默认风格","spec":"默认风格","size":"150","isdefault":1}]',
            ],
            [
                'id' => '30',
                'name' => '港中能达物流',
                'code' => 'ganzhongnengda',
                'key' => 'ganzhongnengda',
                'ali_key' => 'ganzhongnengda',
                'r_datas' => '[{"style":"默认风格","spec":"默认风格","size":"150","isdefault":1}]',
            ],
            [
                'id' => '31',
                'name' => '广东邮政物流',
                'code' => 'guangdongyouzhengwuliu',
                'key' => 'GDEMS',
                'ali_key' => 'GDEMS',
                'r_datas' => '[{"style":"默认风格","spec":"默认风格","size":"150","isdefault":1}]',
            ],
            [
                'id' => '32',
                'name' => '共速达',
                'code' => 'gongsuda',
                'key' => 'GSD',
                'ali_key' => 'GSD',
                'r_datas' => '[{"style":"默认风格","spec":"默认风格","size":"150","isdefault":1}]',
            ],
            [
                'id' => '33',
                'name' => '恒路物流',
                'code' => 'hengluwuliu',
                'key' => 'HLWL',
                'ali_key' => 'HLWL',
                'r_datas' => '[{"style":"默认风格","spec":"默认风格","size":"150","isdefault":1}]',
            ],
            [
                'id' => '34',
                'name' => '华夏龙物流',
                'code' => 'huaxialongwuliu',
                'key' => 'HXLWL',
                'ali_key' => 'HXLWL',
                'r_datas' => '[{"style":"默认风格","spec":"默认风格","size":"150","isdefault":1}]',
            ],
            [
                'id' => '35',
                'name' => '海红',
                'code' => 'haihongwangsong',
                'key' => 'haihongwangsong',
                'ali_key' => 'haihongwangsong',
                'r_datas' => '[{"style":"默认风格","spec":"默认风格","size":"150","isdefault":1}]',
            ],
            [
                'id' => '36',
                'name' => '海外环球',
                'code' => 'haiwaihuanqiu',
                'key' => 'haiwaihuanqiu',
                'ali_key' => 'haiwaihuanqiu',
                'r_datas' => '[{"style":"默认风格","spec":"默认风格","size":"150","isdefault":1}]',
            ],
            [
                'id' => '37',
                'name' => '佳怡物流',
                'code' => 'jiayiwuliu',
                'key' => 'JYWL',
                'ali_key' => 'JYWL',
                'r_datas' => '[{"style":"默认风格","spec":"默认风格","size":"150","isdefault":1}]',
            ],
            [
                'id' => '38',
                'name' => '京广速递',
                'code' => 'jinguangsudikuaijian',
                'key' => 'JGSD',
                'ali_key' => 'JGSD',
                'r_datas' => '[{"style":"默认风格","spec":"默认风格","size":"150","isdefault":1}]',
            ],
            [
                'id' => '39',
                'name' => '急先达',
                'code' => 'jixianda',
                'key' => 'JXD',
                'ali_key' => 'JXD',
                'r_datas' => '[{"style":"默认风格","spec":"默认风格","size":"150","isdefault":1}]',
            ],
            [
                'id' => '40',
                'name' => '佳吉物流',
                'code' => 'jiajiwuliu',
                'key' => 'jiajiwuliu',
                'ali_key' => 'JJKY',
                'r_datas' => '[{"style":"默认风格","spec":"默认风格","size":"150","isdefault":1}]',
            ],
            [
                'id' => '41',
                'name' => '加运美物流',
                'code' => 'jymwl',
                'key' => 'JYM',
                'ali_key' => 'JYM',
                'r_datas' => '[{"style":"默认风格","spec":"默认风格","size":"150","isdefault":1}]',
            ],
            [
                'id' => '42',
                'name' => '金大物流',
                'code' => 'jindawuliu',
                'key' => 'jindawuliu',
                'ali_key' => 'jindawuliu',
                'r_datas' => '[{"style":"默认风格","spec":"默认风格","size":"150","isdefault":1}]',
            ],
            [
                'id' => '43',
                'name' => '嘉里大通',
                'code' => 'jialidatong',
                'key' => 'JLDT',
                'ali_key' => 'jialidatong',
                'r_datas' => '[{"style":"默认风格","spec":"默认风格","size":"150","isdefault":1}]',
            ],
            [
                'id' => '44',
                'name' => '晋越快递',
                'code' => 'jykd',
                'key' => 'JYKD',
                'ali_key' => 'JYKD',
                'r_datas' => '[{"style":"默认风格","spec":"默认风格","size":"150","isdefault":1}]',
            ],
            [
                'id' => '45',
                'name' => '快捷速递',
                'code' => 'kuaijiesudi',
                'key' => 'FAST',
                'ali_key' => 'FAST',
                'r_datas' => '[{"style":"默认风格","spec":"默认风格","size":"150","isdefault":1}]',
            ],
            [
                'id' => '46',
                'name' => '联邦快递（国内）',
                'code' => 'lianb',
                'key' => 'FEDEX',
                'ali_key' => 'FEDEX',
                'r_datas' => '[{"style":"默认风格","spec":"默认风格","size":"150","isdefault":1}]',
            ],
            [
                'id' => '47',
                'name' => '联昊通物流',
                'code' => 'lianhaowuliu',
                'key' => 'lianhaowuliu',
                'ali_key' => 'LHT',
                'r_datas' => '[{"style":"默认风格","spec":"默认风格","size":"150","isdefault":1}]',
            ],
            [
                'id' => '48',
                'name' => '龙邦物流',
                'code' => 'longbanwuliu',
                'key' => 'longbanwuliu',
                'ali_key' => 'LB',
                'r_datas' => '[{"style":"默认风格","spec":"默认风格","size":"150","isdefault":1}]',
            ],
            [
                'id' => '49',
                'name' => '立即送',
                'code' => 'lijisong',
                'key' => 'LJSKD',
                'ali_key' => 'lijisong',
                'r_datas' => '[{"style":"默认风格","spec":"默认风格","size":"150","isdefault":1}]',
            ],
            [
                'id' => '50',
                'name' => '乐捷递',
                'code' => 'lejiedi',
                'key' => 'lejiedi',
                'ali_key' => 'lejiedi',
                'r_datas' => '[{"style":"默认风格","spec":"默认风格","size":"150","isdefault":1}]',
            ],
            [
                'id' => '51',
                'name' => '民航快递',
                'code' => 'minghangkuaidi',
                'key' => 'MHKD',
                'ali_key' => 'MHKD',
                'r_datas' => '[{"style":"默认风格","spec":"默认风格","size":"150","isdefault":1}]',
            ],
            [
                'id' => '52',
                'name' => '美国快递',
                'code' => 'meiguokuaidi',
                'key' => 'meiguokuaidi',
                'ali_key' => 'meiguokuaidi',
                'r_datas' => '[{"style":"默认风格","spec":"默认风格","size":"150","isdefault":1}]',
            ],
            [
                'id' => '53',
                'name' => '门对门',
                'code' => 'menduimen',
                'key' => 'MDM',
                'ali_key' => 'menduimen',
                'r_datas' => '[{"style":"默认风格","spec":"默认风格","size":"150","isdefault":1}]',
            ],
            [
                'id' => '54',
                'name' => 'OCS',
                'code' => 'ocs',
                'key' => 'OCS',
                'ali_key' => 'ocs',
                'r_datas' => '[{"style":"默认风格","spec":"默认风格","size":"150","isdefault":1}]',
            ],
            [
                'id' => '55',
                'name' => '配思货运',
                'code' => 'peisihuoyunkuaidi',
                'key' => 'peisihuoyunkuaidi',
                'ali_key' => 'peisihuoyunkuaidi',
                'r_datas' => '[{"style":"默认风格","spec":"默认风格","size":"150","isdefault":1}]',
            ],
            [
                'id' => '56',
                'name' => '全晨快递',
                'code' => 'quanchenkuaidi',
                'key' => 'QCKD',
                'ali_key' => 'QCKD',
                'r_datas' => '[{"style":"默认风格","spec":"默认风格","size":"150","isdefault":1}]',
            ],
            [
                'id' => '57',
                'name' => '全际通物流',
                'code' => 'quanjitong',
                'key' => 'quanjitong',
                'ali_key' => 'quanjitong',
                'r_datas' => '[{"style":"默认风格","spec":"默认风格","size":"150","isdefault":1}]',
            ],
            [
                'id' => '58',
                'name' => '全日通快递',
                'code' => 'quanritongkuaidi',
                'key' => 'QRT',
                'ali_key' => 'QRT',
                'r_datas' => '[{"style":"默认风格","spec":"默认风格","size":"150","isdefault":1}]',
            ],
            [
                'id' => '59',
                'name' => '全一快递',
                'code' => 'quanyikuaidi',
                'key' => 'UAPEX',
                'ali_key' => 'UAPEX',
                'r_datas' => '[{"style":"默认风格","spec":"默认风格","size":"150","isdefault":1}]',
            ],
            [
                'id' => '60',
                'name' => '如风达',
                'code' => 'rufengda',
                'key' => 'RFD',
                'ali_key' => 'RFD',
                'r_datas' => '[{"style":"默认风格","spec":"默认风格","size":"150","isdefault":1}]',
            ],
            [
                'id' => '61',
                'name' => '三态速递',
                'code' => 'santaisudi',
                'key' => 'santaisudi',
                'ali_key' => 'santaisudi',
                'r_datas' => '[{"style":"默认风格","spec":"默认风格","size":"150","isdefault":1}]',
            ],
            [
                'id' => '62',
                'name' => '盛辉物流',
                'code' => 'shenghuiwuliu',
                'key' => 'shenghuiwuliu',
                'ali_key' => 'SHWL',
                'r_datas' => '[{"style":"默认风格","spec":"默认风格","size":"150","isdefault":1}]',
            ],
            [
                'id' => '63',
                'name' => '速尔物流',
                'code' => 'sue',
                'key' => 'SURE',
                'ali_key' => 'SURE',
                'r_datas' => '[{"style":"默认风格","spec":"默认风格","size":"150","isdefault":1}]',
            ],
            [
                'id' => '64',
                'name' => '盛丰物流',
                'code' => 'shengfeng',
                'key' => 'SFWL',
                'ali_key' => 'SFWL',
                'r_datas' => '[{"style":"默认风格","spec":"默认风格","size":"150","isdefault":1}]',
            ],
            [
                'id' => '65',
                'name' => '赛澳递',
                'code' => 'saiaodi',
                'key' => 'SAD',
                'ali_key' => 'SAD',
                'r_datas' => '[{"style":"默认风格","spec":"默认风格","size":"150","isdefault":1}]',
            ],
            [
                'id' => '66',
                'name' => '天地华宇',
                'code' => 'tiandihuayu',
                'key' => 'HOAU',
                'ali_key' => 'HOAU',
                'r_datas' => '[{"style":"默认风格","spec":"默认风格","size":"150","isdefault":1}]',
            ],
            [
                'id' => '67',
                'name' => 'tnt',
                'code' => 'tnt',
                'key' => 'TNT',
                'ali_key' => 'TNT',
                'r_datas' => '[{"style":"默认风格","spec":"默认风格","size":"150","isdefault":1}]',
            ],
            [
                'id' => '68',
                'name' => 'ups',
                'code' => 'ups',
                'key' => 'UPS',
                'ali_key' => 'UPS',
                'r_datas' => '[{"style":"默认风格","spec":"默认风格","size":"150","isdefault":1}]',
            ],
            [
                'id' => '69',
                'name' => '万家物流',
                'code' => 'wanjiawuliu',
                'key' => 'WJWL',
                'ali_key' => 'WJWL',
                'r_datas' => '[{"style":"默认风格","spec":"默认风格","size":"150","isdefault":1}]',
            ],
            [
                'id' => '70',
                'name' => '文捷航空速递',
                'code' => 'wenjiesudi',
                'key' => 'wenjiesudi',
                'ali_key' => 'wenjiesudi',
                'r_datas' => '[{"style":"默认风格","spec":"默认风格","size":"150","isdefault":1}]',
            ],
            [
                'id' => '71',
                'name' => '伍圆',
                'code' => 'wuyuan',
                'key' => 'wuyuan',
                'ali_key' => 'wuyuan',
                'r_datas' => '[{"style":"默认风格","spec":"默认风格","size":"150","isdefault":1}]',
            ],
            [
                'id' => '72',
                'name' => '万象物流',
                'code' => 'wxwl',
                'key' => 'wxwl',
                'ali_key' => 'WXWL',
                'r_datas' => '[{"style":"默认风格","spec":"默认风格","size":"150","isdefault":1}]',
            ],
            [
                'id' => '73',
                'name' => '新邦物流',
                'code' => 'xinbangwuliu',
                'key' => 'xinbangwuliu',
                'ali_key' => 'XBWL',
                'r_datas' => '[{"style":"默认风格","spec":"默认风格","size":"150","isdefault":1}]',
            ],
            [
                'id' => '74',
                'name' => '信丰物流',
                'code' => 'xinfengwuliu',
                'key' => 'XFEX',
                'ali_key' => 'XFEX',
                'r_datas' => '[{"style":"默认风格","spec":"默认风格","size":"150","isdefault":1}]',
            ],
            [
                'id' => '75',
                'name' => '亚风速递',
                'code' => 'yafengsudi',
                'key' => 'YFSD',
                'ali_key' => 'YFSD',
                'r_datas' => '[{"style":"默认风格","spec":"默认风格","size":"150","isdefault":1}]',
            ],
            [
                'id' => '76',
                'name' => '一邦速递',
                'code' => 'yibangwuliu',
                'key' => 'yibangwuliu',
                'ali_key' => 'yibangwuliu',
                'r_datas' => '[{"style":"默认风格","spec":"默认风格","size":"150","isdefault":1}]',
            ],
            [
                'id' => '77',
                'name' => '优速物流',
                'code' => 'youshuwuliu',
                'key' => 'UC',
                'ali_key' => 'UC',
                'r_datas' => '[{"style":"默认风格","spec":"默认风格","size":"150","isdefault":1}]',
            ],
            [
                'id' => '78',
                'name' => '邮政快递包裹',
                'code' => 'youzhengguonei',
                'key' => 'YZBK',
                'ali_key' => 'YZPY',
                'r_datas' => '[{"style":"默认风格","spec":"默认风格","size":"150","isdefault":1}]',
            ],
            [
                'id' => '79',
                'name' => '邮政国际包裹挂号信',
                'code' => 'youzhengguoji',
                'key' => 'youzhengguoji',
                'ali_key' => 'youzhengguoji',
                'r_datas' => '[{"style":"默认风格","spec":"默认风格","size":"150","isdefault":1}]',
            ],
            [
                'id' => '80',
                'name' => '远成物流',
                'code' => 'yuanchengwuliu',
                'key' => 'YCWL',
                'ali_key' => 'YCWL',
                'r_datas' => '[{"style":"默认风格","spec":"默认风格","size":"150","isdefault":1}]',
            ],
            [
                'id' => '81',
                'name' => '源伟丰快递',
                'code' => 'yuanweifeng',
                'key' => 'yuanweifeng',
                'ali_key' => 'yuanweifeng',
                'r_datas' => '[{"style":"默认风格","spec":"默认风格","size":"150","isdefault":1}]',
            ],
            [
                'id' => '82',
                'name' => '元智捷诚快递',
                'code' => 'yuanzhijiecheng',
                'key' => 'yuanzhijiecheng',
                'ali_key' => 'yuanzhijiecheng',
                'r_datas' => '[{"style":"默认风格","spec":"默认风格","size":"150","isdefault":1}]',
            ],
            [
                'id' => '83',
                'name' => '运通快递',
                'code' => 'yuntongkuaidi',
                'key' => 'YTKD',
                'ali_key' => 'CTG',
                'r_datas' => '[{"style":"默认风格","spec":"默认风格","size":"150","isdefault":1}]',
            ],
            [
                'id' => '84',
                'name' => '越丰物流',
                'code' => 'yuefengwuliu',
                'key' => 'YFEX',
                'ali_key' => 'YFEX',
                'r_datas' => '[{"style":"默认风格","spec":"默认风格","size":"150","isdefault":1}]',
            ],
            [
                'id' => '85',
                'name' => '源安达',
                'code' => 'yad',
                'key' => 'YADEX',
                'ali_key' => 'YADEX',
                'r_datas' => '[{"style":"默认风格","spec":"默认风格","size":"150","isdefault":1}]',
            ],
            [
                'id' => '86',
                'name' => '银捷速递',
                'code' => 'yinjiesudi',
                'key' => 'yinjiesudi',
                'ali_key' => 'yinjiesudi',
                'r_datas' => '[{"style":"默认风格","spec":"默认风格","size":"150","isdefault":1}]',
            ],
            [
                'id' => '87',
                'name' => '中铁快运',
                'code' => 'zhongtiekuaiyun',
                'key' => 'ZTKY',
                'ali_key' => 'ZTKY',
                'r_datas' => '[{"style":"默认风格","spec":"默认风格","size":"150","isdefault":1}]',
            ],
            [
                'id' => '88',
                'name' => '中邮物流',
                'code' => 'zhongyouwuliu',
                'key' => 'ZYKD',
                'ali_key' => 'ZYWL',
                'r_datas' => '[{"style":"默认风格","spec":"默认风格","size":"150","isdefault":1}]',
            ],
            [
                'id' => '89',
                'name' => '忠信达',
                'code' => 'zhongxinda',
                'key' => 'zhongxinda',
                'ali_key' => 'zhongxinda',
                'r_datas' => '[{"style":"默认风格","spec":"默认风格","size":"150","isdefault":1}]',
            ],
            [
                'id' => '90',
                'name' => '芝麻开门',
                'code' => 'zhimakaimen',
                'key' => 'zhimakaimen',
                'ali_key' => 'ZMKMEX',
                'r_datas' => '[{"style":"默认风格","spec":"默认风格","size":"150","isdefault":1}]',
            ],
            [
                'id' => '91',
                'name' => '安能物流',
                'code' => 'annengwuliu',
                'key' => 'ANE',
                'ali_key' => 'ANE',
                'r_datas' => '[{"style":"默认风格","spec":"默认风格","size":"150","isdefault":1}]',
            ],
            [
                'id' => '92',
                'name' => '京东快递',
                'code' => 'jd',
                'key' => 'JD',
                'ali_key' => 'jd',
                'r_datas' => '[{"style":"默认风格","spec":"默认风格","size":"150","isdefault":1}]',
            ],
            [
                'id' => '93',
                'name' => '众邮快递',
                'code' => 'zhongyouex',
                'key' => 'ZYE',
                'ali_key' => 'ZYKD',
                'r_datas' => '[{"style":"默认风格","spec":"默认风格","size":"150","isdefault":1}]',
            ],
            [
                'id' => '94',
                'name' => '极兔速递',
                'code' => 'jtexpress',
                'key' => 'JTSD',
                'ali_key' => 'JITU',
                'r_datas' => '[{"style":"默认风格","spec":"默认风格","size":"150","isdefault":1}]',
            ],
            [
                'id' => '95',
                'name' => '中通快运',
                'code' => 'zhongtongkuaiyun',
                'key' => 'ZTOKY',
                'ali_key' => 'ZTO56',
                'r_datas' => '[{"style":"默认风格","spec":"默认风格","size":"150","isdefault":1}]',
            ],
        ];
    }
}