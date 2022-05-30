<?php
/**
 * Created by PhpStorm.
 * User: ganxi
 * Date: 2022/4/15
 * Time: 14:56
 * Note:
 */

namespace app\controllers\test;


use app\controllers\BaseController;
use app\helpers\HttpHelper;
use app\helpers\JobHelper;

use app\helpers\WechatHelper;
use app\jobs\test\TestJob;
use app\models\Cat;
use app\models\Menu;
use app\models\UserParent;
use app\mongo\JxmallOption;

use app\plugins\integral\forms\api\IntegralSignLogListForm;
use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;
use yii\helpers\FileHelper;
use yii\helpers\Json;
use yii\helpers\Url;


class TestController extends BaseController
{


    public function actionNode(){



       $res= HttpHelper::get('http://localhost:8899');

       dd($res);

    }

    public function actionMini()
    {


        dd(\Yii::$app->request->hostName);
        $appid = 'wx9b1496c7b38ef3b3';
        $min_path = \Yii::getAlias('@webroot') . '/mini_path/standard';

        FileHelper::unlink(FileHelper::copyDirectory($min_path, \Yii::getAlias('@webroot') . '/mini_path/' . $appid));
        //  FileHelper::copyDirectory($min_path,\Yii::getAlias('@webroot').'/mini_path/'.$appid);
        $path = \Yii::getAlias('@webroot') . '/mini_path/' . $appid;
        $info = Json::encode([
            "mall_id" => 1,
            "uniacid" => "-1",
            "acid" => "-1",
            "multiid" => "0",
            "version" => "1.0",
            "siteroot" => 'https://jxmall.sinbel.cn/web/index.php?r=',
            "design_method" => "3"
        ]);
        $data = "var siteinfo = {$info}
module.exports = siteinfo";
        file_put_contents($path . '/siteinfo.js', $data);

        $config = Json::decode(file_get_contents($path . '/project.config.json'));
        $config['appid'] = $appid;
        file_put_contents($path . '/project.config.json', Json::encode($config));


        dd('ok');

    }

    public function actionJob()
    {


    }

    public function actionRedis()
    {


    }

    /**
     * Created by PhpStorm.
     * User：ganxi
     * Date：2022/4/12
     * Time：13:43
     * Note：递归获取路由
     */
    private function loopRouters($parent_id, $menu_ids = null)
    {
        $children = Menu::find()->andWhere(['parent_id' => $parent_id])
            ->andFilterWhere(['menu_id' => $menu_ids])
            ->orderBy('order_num asc')
            ->select('id,menu_id,parent_id,menu_name,icon,hidden,is_cache,link,path,component,breadcrumb,show_tag,active_menu,menu_type,name,plugin')
            ->asArray()
            ->all();
        $arr = [];
        foreach ($children as $child) {
            $row = [];
            $row['id'] = $child['id'];
            $row['menu_id'] = $child['menu_id'];
            $row['hidden'] = $child['hidden'] ? true : false;
            $row['name'] = $child['name'];
            $row['path'] = $child['path'];
            $row['parent_id'] = $child['parent_id'];
            $row['menu_type'] = $child['menu_type'];
            if ($child['menu_type'] == 'M') {
                $row['alwaysShow'] = true;
                $row['redirect'] = 'noRedirect';
                $row['component'] = 'ParentView';
            } else {
                $row['component'] = $child['component'];
            }
            $meta = [
                'menu_type' => $child['menu_type'],
                'show_tag' => $child['show_tag'],
                'title' => $child['menu_name'],
                'icon' => $child['icon'],
                'is_cache' => $child['is_cache'],
                'link' => $child['link'] ?? null,
                'breadcrumb' => $child['breadcrumb'] ? true : false,
                'activeMenu' => $child['active_menu']
            ];
            $row['meta'] = $meta;
            $exist = Menu::find()->andWhere(['parent_id' => $child['menu_id']])
                ->andWhere(['menu_id' => $menu_ids])
                ->exists();
            if ($exist) {
                $row['children'] = $this->loopRouters($child['menu_id'], $menu_ids);
            }
            $arr[] = $row;
        }
        return $arr;
    }

    public function actionIndex()
    {
        $menuIds = Menu::find()->andWhere(['is_delete' => 0])->select('menu_id')->column();
        $list = Menu::find()->andWhere(['parent_id' => 0])
            ->andWhere(['menu_id' => $menuIds])
            ->select('id,menu_id,parent_id,menu_name,icon,hidden,is_cache,link,path,component,breadcrumb,show_tag,active_menu,menu_type,name,plugin')
            ->orderBy('order_num asc')->asArray()->all();
        $arr = [];
        foreach ($list as $item) {
            $row = [];
            $row['id'] = $item['id'];
            $row['hidden'] = $item['hidden'] ? true : false;
            $row['name'] = $item['name'];
            $row['menu_id'] = $item['menu_id'];
            $row['parent_id'] = $item['parent_id'];
            $row['menu_type'] = $item['menu_type'];
            $row['path'] = '/' . $item['path'];//一级目录特殊处理
            $row['component'] = 'Layout';
            $meta = [
                'menu_type' => $item['menu_type'],
                'component' => $item['component'],
                'show_tag' => $item['show_tag'],
                'title' => $item['menu_name'],
                'icon' => $item['icon'],
                'is_cache' => $item['is_cache'],
                'link' => $item['link'] ?? null,
                'breadcrumb' => $item['breadcrumb'] ? true : false,
                'activeMenu' => $item['active_menu']
            ];
            $row['meta'] = $meta;
            if ($item['menu_type'] == 'M') {
                $row['redirect'] = 'noRedirect';
                $row['alwaysShow'] = true;
            } elseif ($item['menu_type'] == 'C') {
                //如果第一级是菜单，进行特殊处理
                $row['path'] = '/';//一级目录特殊处理
                $children = [
                    [
                        'component' => $item['component'],
                        'hidden' => $item['hidden'] ? true : false,
                        'name' => $item['name'],
                        'path' => $item['path'],
                        'meta' => [
                            'menu_type' => $item['menu_type'],
                            'component' => $item['component'],
                            'show_tag' => $item['show_tag'],
                            'title' => $item['menu_name'],
                            'icon' => $item['icon'],
                            'is_cache' => $item['is_cache'],
                            'link' => $item['link'] ?? null,
                            'breadcrumb' => $item['breadcrumb'] ? true : false,
                            'activeMenu' => $item['active_menu']
                        ]
                    ]
                ];
                $row['children'] = $children;
                $arr[] = $row;
                continue;
            }
            //递归拿数据
            $children = $this->loopRouters($item['menu_id'], $menuIds);
            $row['children'] = $children;
            $arr[] = $row;
        }
        unset($row);

        /*
         *  $this->createIndex('user_id', '{{%bank}}', ['user_id'], false);
         * */

        return [
            "msg" => "操作成功",
            "code" => 0,
            'data' => $arr
        ];
    }

    public function actionMsg()
    {
        $spreadsheet = new Spreadsheet();
        $sheet = $spreadsheet->getActiveSheet();
        $sheet->setCellValue('A1', 'Hello World !');
        //单元格从1开始
        $sheet->setCellValueByColumnAndRow(2, 1, 'hellow owowoo');
        $writer = new Xlsx($spreadsheet);
        $writer->save(\Yii::$app->runtimePath . '/hello world.xlsx');
        \Yii::$app->response->sendFile(\Yii::$app->runtimePath . '/hello world.xlsx');
    }
}