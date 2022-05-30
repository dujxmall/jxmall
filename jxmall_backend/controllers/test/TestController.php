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


   public function actionIndex(){
       dd('测试');
   }
}