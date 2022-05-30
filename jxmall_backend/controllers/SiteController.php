<?php

namespace app\controllers;

use app\core\ApiCode;
use app\models\Admin;
use Yii;
use yii\filters\AccessControl;
use yii\web\Controller;
use yii\web\Cookie;
use yii\web\Response;
use yii\filters\VerbFilter;


class SiteController extends Controller
{
    /**
     * {@inheritdoc}
     */
    public function behaviors()
    {
        return [
            'access' => [
                'class' => AccessControl::className(),
                'only' => ['logout'],
                'rules' => [
                    [
                        'actions' => ['logout'],
                        'allow' => true,
                        'roles' => ['@'],
                    ],
                ],
            ],
            'verbs' => [
                'class' => VerbFilter::className(),
                'actions' => [
                    'logout' => ['post'],
                ],
            ],
        ];
    }


    /**
     * Displays homepage.
     *
     * @return string
     */
    public function actionIndex()
    {
        Yii::$app->response->redirect('/admin');
        return $this->render('index');
    }

    /**
     * Login action.
     *
     * @return Response|string
     */
    public function actionLogin()
    {

        $admin = Admin::findOne(['username' => 'admin', 'is_delete' => 0]);

        $admin->access_token = Yii::$app->security->generateRandomString();
        $admin->auth_key = Yii::$app->security->generateRandomString();

        Yii::$app->response->cookies->add(new Cookie(['name' => 'x-admin-token', 'value' => $admin->access_token, 'expire' => time() + 3600 * 24 * 1, 'httpOnly' => false]));

        Yii::$app->admin->login($admin);

        Yii::$app->response->redirect('/admin');
    }

    /**
     * Logout action.
     *
     * @return Response
     */
    public function actionLogout()
    {
        Yii::$app->user->logout();
        return $this->goHome();
    }


    /**
     * Displays about page.
     *
     * @return string
     */
    public function actionAbout()
    {
        return $this->render('about');
    }

    public function actionError()
    {

        return $this->render('error', ['name' => '出现了一个错误~', 'message' => '给您带来的不便，我们深感抱歉！']);
    }
}
