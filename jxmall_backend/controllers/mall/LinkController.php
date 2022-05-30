<?php
/**
 * @link:http://www.dujxmall.com/
 * @copyright: Copyright (c) 2020 广州动力宇宙信息科技
 * Created by PhpStorm
 * Author: ganxiaohao
 * Date: 2020-10-26
 * Time: 9:12
 */

namespace app\controllers\mall;


use app\forms\mall\link\LinkForm;

class LinkController extends Controller
{

    /**
     * @Author: 动力宇宙 ganxiaohao
     * @Date: 2020-10-26
     * @Time: 9:13
     * @Note:链接列表
     */
    public function actionList()
    {
        $form = new LinkForm();
        $form->attributes = $this->request->get();
        return $this->asJson($form->getList());
    }

}