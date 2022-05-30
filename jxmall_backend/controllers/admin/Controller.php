<?php
/**
 * @link:http://www.dujxmall.com/
 * @copyright: Copyright (c) 2020 广州动力宇宙信息科技
 * Created by PhpStorm
 * Author: ganxiaohao
 * Date: 2020-09-09
 * Time: 17:31
 */

namespace app\controllers\admin;

use app\controllers\admin\filters\AdminFilter;
use app\controllers\BaseController;
use yii\web\Request;

/**
 * Class Controller
 * @package app\controllers\admin
 * @Notes
 * @property Request $request
 */
class Controller extends BaseController
{

    public $enableCsrfValidation = false;
    public $request;

    public function init()
    {

        $this->request = \Yii::$app->request;
        parent::init(); // TODO: Change the autogenerated stub
    }

    public function behaviors()
    {
        return array_merge(parent::behaviors(), [
            'adminFilter' => AdminFilter::class
        ]);// TODO: Change the autogenerated stub
    }

}