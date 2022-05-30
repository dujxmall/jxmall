<?php
/**
 * Created by PhpStorm.
 * User: ganxi
 * Date: 2022/4/4
 * Time: 16:30
 * Note:
 */

namespace app\controllers\admin;

use app\core\ApiCode;
use app\forms\admin\AdminDeleteForm;
use app\forms\admin\AdminListForm;
use app\forms\admin\ApiForm;
use app\forms\admin\ApiListForm;
use app\forms\admin\LogoutForm;
use app\forms\admin\MenuListForm;
use app\forms\admin\PasswordChangeForm;
use app\forms\admin\PasswordResetForm;
use app\forms\admin\PluginMapForm;
use app\helpers\ResponseHelper;
use app\models\Admin;
use app\models\AdminMall;
use app\models\AdminRole;
use app\models\Menu;
use app\models\Role;
use app\models\RoleMenu;
use app\plugins\Plugin;
use yii\helpers\Json;


class AdminController extends Controller
{


    /**
     * @Author: 动力宇宙 ganxiaohao
     * @Date: 2022-05-08
     * @Time: 21:59
     * @Note:插件
     */
    public function actionPluginMap()
    {
        $form = new PluginMapForm();
        return $this->asJson($form->getList());
    }


    /**
     * Created by PhpStorm.
     * User：ganxi
     * Date：2022/5/13
     * Time：15:06
     * Note：当前的mall_id 可以使用的插件
     */
    public function actionMallPlugin()
    {
        $list = \app\models\MallPlugin::find()->alias('m')
            ->innerJoin(['p' => \app\models\Plugin::tableName()], 'p.name=m.plugin')
            ->andWhere(['m.mall_id' => $this->mall_id])->andWhere(['m.is_delete' => 0, 'p.is_delete' => 0])
            ->select('m.plugin,p.label')
            ->orderBy('p.type asc')
            ->asArray()
            ->all();



        return ResponseHelper::send(ApiCode::CODE_SUCCESS, '', ['list' => $list]);
    }


    /**
     * 针对某个用户进行密码重置
     * @return \yii\web\Response
     */
    public function actionPasswordReset()
    {
        $form = new PasswordResetForm();
        $form->attributes = $this->request->post();
        return $this->asJson($form->save());
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
            ->andWhere(['menu_type' => ['C', 'M']])
            ->orderBy('order_num asc')
            ->select('parent_id,menu_id,menu_name,icon,hidden,is_cache,link,path,component,breadcrumb,show_tag,active_menu,menu_type,name,plugin')
            ->asArray()
            ->all();
        $arr = [];
        foreach ($children as $child) {
            $row = [];
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
                ->andWhere(['menu_type' => ['C', 'M']])
                ->exists();
            if ($exist) {
                $row['children'] = $this->loopRouters($child['menu_id'], $menu_ids);
            }
            $arr[] = $row;
        }
        return $arr;
    }


    /**
     * @return array 通过角色获取路由
     */
    public function actionRouters()
    {
        //查询当前商城所关联的插件
        $plugins = \app\models\MallPlugin::find()->alias('m')
            ->innerJoin(['p' => \app\models\Plugin::tableName()], 'p.name=m.plugin')
            ->select('m.plugin')->andWhere(['m.mall_id' => $this->mall_id])->andWhere(['m.is_delete' => 0, 'p.is_delete' => 0])->column();
        $plugins[] = 'mall';
        if (\Yii::$app->admin->identity->admin_type == Admin::ADMIN_TYPE_SUPER) {
            $menuIds = Menu::find()->andWhere(['plugin' => $plugins])->andWhere(['is_delete' => 0])->select('menu_id')->column();
        } else {
            $menuIds = RoleMenu::find()->alias('r')->innerJoin(['m' => Menu::tableName()], 'm.menu_id=r.menu_id')->andWhere(['m.plugin' => $plugins])->andWhere(['r.role_id' => \Yii::$app->admin->identity->role_id])->select('r.menu_id')->column();
        }
        $list = Menu::find()->andWhere(['parent_id' => 0])
            ->andWhere(['menu_id' => $menuIds])
            ->andWhere(['menu_type' => ['M', 'C']])
            ->select('parent_id,menu_id,menu_name,icon,hidden,is_cache,link,path,component,breadcrumb,show_tag,active_menu,menu_type,name,plugin')
            ->orderBy('order_num asc')->asArray()->all();
        $arr = [];
        foreach ($list as $item) {
            $row = [];
            $row['hidden'] = $item['hidden'] ? true : false;
            $row['name'] = $item['name'];
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
        return [
            "msg" => "操作成功",
            "code" => 0,
            'data' => $arr
        ];
    }

    /**
     * Created by PhpStorm.
     * User：ganxi
     * Date：2022/4/12
     * Time：13:43
     * Note：用户编辑
     */
    public function actionUserEdit()
    {
        $data = $this->request->post();
        $admin = null;
        if ($data['id']) {
            $admin = Admin::findOne(['id' => $data['id'], 'created_by' => \Yii::$app->admin->identity->getId(), 'is_delete' => 0]);
            if (!$admin) {
                return ResponseHelper::send(ApiCode::CODE_FAIL, '用户不存在');
            }
        }
        if (!$admin) {
            if ($data['admin_type'] == Admin::ADMIN_TYPE_FOUNDER) {
                if (\Yii::$app->admin->identity->admin_type != Admin::ADMIN_TYPE_SUPER) {
                    return ResponseHelper::send(ApiCode::CODE_FAIL, '您的账户类型不可创建子账户');
                }
            }
            $admin = Admin::findOne(['username' => $data['username'], 'is_delete' => 0]);
            if ($admin) {
                return ResponseHelper::send(ApiCode::CODE_FAIL, '用户名已存在！');
            }

            $admin = new Admin();
            //0 超级管理员   1 分账户  2 员工
            $admin->admin_type = $data['admin_type'];
            $admin->created_by = \Yii::$app->admin->identity->getId();
            $admin->username = $data['username'];
        }
        $admin->nickname = $data['nickname'];
        $admin->mobile = $data['mobile'];
        $admin->avatar = $data['avatar'];
        $admin->password = \Yii::$app->security->generatePasswordHash($data['password']);
        $admin->email = $data['email'];
        $admin->remark = $data['remark'];
        $admin->name = $admin->nickname;
        $roleIds = $data['roleIds'];
        if (count($roleIds)) {
            $admin->role_id = $roleIds[0];
        }
        if ($admin->save()) {
            if ($admin->admin_type == Admin::ADMIN_TYPE_OPERATOR) {//员工
                $adminMall = AdminMall::findOne(['admin_id' => $admin->id, 'is_delete' => 0]);
                if ($adminMall) {
                    $adminMall->mall_id = $this->mall_id;
                } else {
                    $adminMall = new AdminMall();
                    $adminMall->admin_id = $admin->id;
                    $adminMall->mall_id = $this->mall_id;
                    $adminMall->role = AdminMall::OPERATOR;
                }
                $adminMall->save();
            }
        }
        AdminRole::deleteAll(['admin_id' => $admin->id]);
        foreach ($roleIds as $id) {
            $role = new AdminRole();
            $role->admin_id = $admin->id;
            $role->role_id = $id;
            $role->save();
        }
        return ResponseHelper::send(ApiCode::CODE_SUCCESS, '更新成功');
    }

    /**
     * Created by PhpStorm.
     * User：ganxi
     * Date：2022/4/12
     * Time：13:43
     * Note：角色编辑获取菜单内容
     */
    public function actionRoleMenuTreeSelect()
    {
        $role_id = $this->request->get('role_id');
        $checkedKeys = RoleMenu::find()->andWhere(['role_id' => $role_id])->select('menu_id')->column();

        if (\Yii::$app->admin->identity->admin_type != Admin::ADMIN_TYPE_SUPER) {
            $menuIds = RoleMenu::find()->andWhere(['role_id' => \Yii::$app->admin->identity->role_id])->select('menu_id')->column();
        } else {
            $menuIds = Menu::find()->andWhere(['is_delete' => 0])->select('menu_id')->column();
        }
        $list = $this->loopMenuTree(0, $menuIds);
        return [
            "msg" => "操作成功",
            "code" => 0,
            "menus" => $list,
            "checkedKeys" => $checkedKeys
        ];
    }

    /**
     * Created by PhpStorm.
     * User：ganxi
     * Date：2022/4/12
     * Time：13:42
     * Note：角色列表，只允许读取自己创建的角色
     */
    public function actionRoles()
    {
        $list = Role::find()
            ->andWhere(['is_delete' => 0])
            ->andWhere(['created_by' => \Yii::$app->admin->identity->getId()])
            ->orderBy('admin desc')
            ->orderBy('id asc')
            ->select('*,id as role_id')
            ->asArray()
            ->all();
        foreach ($list as &$item) {
            $item['menu_check_strictly'] = $item['menu_check_strictly'] ? true : false;
            $item['admin'] = $item['admin'] ? true : false;
            $item['menuIds'] = [];
        }
        unset($item);
        return [
            "total" => 2,
            "rows" => $list,
            "code" => 0,
            "msg" => "查询成功"
        ];
    }

    /**
     * Created by PhpStorm.
     * User：ganxi
     * Date：2022/4/12
     * Time：13:42
     * Note：角色详情
     */
    public function actionRole()
    {
        $role = Role::getOne($this->request->get('role_id'));
        $info = $role->attributes;
        $info['admin'] = $info['admin'] ? true : false;
        $info['role_id'] = $role->id;
        $info['menu_check_strictly'] = $info['menu_check_strictly'] ? true : false;
        return [
            "msg" => "操作成功",
            "code" => 0,
            "data" => $info
        ];
    }

    /**
     * Created by PhpStorm.
     * User：ganxi
     * Date：2022/4/12
     * Time：13:42
     * Note：角色编辑
     */
    public function actionRoleEdit()
    {
        $data = $this->request->post();
        $menuIds = $data['menuIds'];
        $model = Role::getOne($data['role_id']);
        if (!$model) {
            $model = new Role();
            $model->created_by = \Yii::$app->admin->identity->getId();
        }
        $model->role_key = $data['role_key'];
        $model->role_name = $data['role_name'];
        $model->role_sort = $data['role_sort'];
        $model->status = $data['status'];
        $model->remark = $data['remark'];
        $model->menu_check_strictly = $data['menu_check_strictly'] ? 1 : 0;
        $model->admin = $data['admin'] ? 1 : 0;
        $model->save();
        RoleMenu::deleteAll(['role_id' => $model->id]);
        foreach ($menuIds as $id) {
            $menu = new RoleMenu();
            $menu->menu_id = $id;
            $menu->role_id = $model->id;
            $menu->save();
        }
        return ['code' => 0, 'msg' => '新增成功'];
    }


    /**
     * Created by PhpStorm.
     * User：ganxi
     * Date：2022/4/12
     * Time：13:42
     * Note：菜单删除
     */
    public function actionRoleDelete()
    {
        $post = $this->request->post();
        $role = Role::findOne($post['role_id']);
        if (!$role) {
            return ResponseHelper::send(ApiCode::CODE_FAIL, '角色不存在');
        }
        $exist = AdminRole::find()->andWhere(['role_id' => $role->id])->exists();
        if ($exist) {
            return ResponseHelper::send(ApiCode::CODE_FAIL, '该角色关联了部分系统用户，请先移除系统用户的角色后再删除');
        }
        RoleMenu::deleteAll(['role_id' => $role->id]);
        Admin::updateAll(['role_id' => 0], ['role_id' => $role->id]);
        AdminRole::deleteAll(['role_id' => $role->id]);
        $role->delete();
        return ResponseHelper::send(ApiCode::CODE_SUCCESS, '删除成功');
    }

    /**
     * Created by PhpStorm.
     * User：ganxi
     * Date：2022/4/12
     * Time：13:41
     * Note：菜单详情
     */
    public function actionMenu()
    {
        $menu = Menu::findOne(['menu_id' => $this->request->get('menu_id')]);
        return [
            "msg" => "操作成功",
            "code" => 0,
            'data' => $menu->attributes
        ];
    }

    /**
     * Created by PhpStorm.
     * User：ganxi
     * Date：2022/4/12
     * Time：13:41
     * Note：添加菜单
     */
    public function actionAddMenu()
    {
        $data = $this->request->post();
        $model = new Menu();
        $model->menu_name = $data['menu_name'];
        $model->plugin = $data['plugin'];
        $model->icon = $data['icon'];
        $model->menu_type = $data['menu_type'];
        $model->order_num = $data['order_num'];
        $model->is_cache = $data['is_cache'];
        $model->name = $data['name'];
        $model->is_frame = $data['is_frame'];
        $model->hidden = $data['hidden'];
        $model->status = $data['status'];
        $model->path = $data['path'];
        $model->component = $data['component'];
        $model->breadcrumb = $data['breadcrumb'];
        $model->query = $data['query'];
        $model->perms = $data['perms'];
        $model->parent_id = $data['parent_id'];
        $model->show_tag = $data['show_tag'];
        $model->active_menu = $data['active_menu'];
        $parent = Menu::findOne(['menu_id' => $model->parent_id]);
        if (!$parent->parent_id) {
            $count = Menu::find()->andWhere(['parent_id' => $parent->menu_id, 'is_delete' => 0])->count();
            $menuid = ($parent->menu_id * 1000) . ($count ? $count + 1 : 1);
        } else {
            $count = Menu::find()->andWhere(['parent_id' => $parent->menu_id, 'is_delete' => 0])->count();
            $menuid = ($parent->menu_id) . ($count ? $count + 1 : 1);
        }
        $model->menu_id = $menuid;
        if (!$model->save()) {
            return ['code' => 1, 'msg' => '保存失败'];
        }
        return ['code' => 0, 'msg' => '保存成功'];
    }


    /**
     * Created by PhpStorm.
     * User：ganxi
     * Date：2022/4/12
     * Time：13:41
     * Note：循环获取菜单树
     */
    private function loopMenuTree($parent_id, $menuIds = null)
    {
        $list = Menu::find()
            ->andFilterWhere(['menu_id' => $menuIds])
            ->andWhere(['parent_id' => $parent_id])
            ->orderBy('order_num asc')
            ->select('menu_id as id,menu_name as label')
            ->asArray()
            ->all();
        foreach ($list as &$item) {
            $exist = Menu::find()->andFilterWhere(['menu_id' => $menuIds])->andWhere(['parent_id' => $item['id']])->exists();
            if (!$exist) {
                continue;
            }
            $item['children'] = $this->loopMenuTree($item['id'], $menuIds);
        }
        unset($item);
        return $list;
    }

    /**
     * Created by PhpStorm.
     * User：ganxi
     * Date：2022/4/12
     * Time：13:41
     * Note：菜单树
     */
    public function actionMenuTree()
    {
        $plugins = \app\models\MallPlugin::find()->alias('m')
            ->innerJoin(['p' => \app\models\Plugin::tableName()], 'p.name=m.plugin')
            ->select('m.plugin')->andWhere(['m.mall_id' => $this->mall_id])->andWhere(['m.is_delete' => 0, 'p.is_delete' => 0])->column();
        $plugins[] = 'mall';
        if (\Yii::$app->admin->identity->admin_type != Admin::ADMIN_TYPE_SUPER) {
            $menuIds = RoleMenu::find()->andWhere(['role_id' => \Yii::$app->admin->identity->role_id])->andWhere(['plugin' => $plugins])->select('menu_id')->column();
        } else {
            $menuIds = Menu::find()->andWhere(['is_delete' => 0])->andWhere(['plugin' => $plugins])->select('menu_id')->column();
        }
        $list = $this->loopMenuTree(0, $menuIds);
        return [
            "msg" => "操作成功",
            "code" => 0,
            "data" => $list,
        ];
    }

    /**
     * Created by PhpStorm.
     * User：ganxi
     * Date：2022/4/12
     * Time：13:39
     * Note：菜单删除
     */
    public function actionMenuDelete()
    {
        $data = $this->request->post();
        $menu = Menu::findOne(['menu_id' => $data['menu_id']]);
        if ($menu) {
            RoleMenu::deleteAll(['menu_id' => $menu->menu_id]);
            $menu->delete();
        }
        return ResponseHelper::send(ApiCode::CODE_SUCCESS, '删除成功');
    }

    /**
     * Created by PhpStorm.
     * User：ganxi
     * Date：2022/4/12
     * Time：13:39
     * Note：用户信息
     */
    public function actionUser()
    {
        $admin = Admin::getOne($this->request->get('id'));
        $role = Role::getOne($admin->role_id);
        $info = [
            "created_time" => $admin->created_time,
            "remark" => $admin->remark,
            "id" => $admin->id,
            "admin_type" => $admin->admin_type,
            "username" => $admin->username,
            "nickname" => $admin->nickname,
            "email" => $admin->email,
            "mobile" => $admin->mobile,
            "avatar" => $admin->avatar,
            'login_ip' => $admin->login_ip
        ];
        if ($role) {
            $info['roles'] = [];
        }
        $list = Role::find()->andWhere(['created_by' => \Yii::$app->admin->identity->getId()])->andWhere(['is_delete' => 0])->orderBy('id asc')->asArray()->all();
        foreach ($list as &$item) {
            $item['admin'] = $item['admin'] ? 1 : 0;
            $item['role_id'] = ($item['id']);
            $item['menu_check_strictly'] = $item['menu_check_strictly'] ? true : false;
        }
        $roleIds = AdminRole::find()
            ->andWhere(['admin_id' => $admin->id])->select('role_id asc')->select('role_id')->column();
        $arr = [];
        foreach ($roleIds as $id) {
            $arr[] = ($id);
        }
        return [
            "msg" => "操作成功",
            "code" => 0,
            "roleIds" => $arr,
            "data" => $info,
            "roles" => $list,
            "admin_type" => \Yii::$app->admin->identity->admin_type
        ];
    }

    /** 菜单编辑
     * @return array
     */
    public function actionMenuEdit()
    {
        $data = $this->request->post();
        $model = Menu::getOne($data['id']);
        if (!$model) {
            return ['code' => 1, 'msg' => '路由不存在'];
        }
        $model->menu_name = $data['menu_name'];
        $model->plugin = $data['plugin'];
        $model->icon = $data['icon'];
        $model->menu_type = $data['menu_type'];
        $model->name = $data['name'];
        $model->order_num = $data['order_num'];
        $model->is_cache = $data['is_cache'];
        $model->is_frame = $data['is_frame'];
        $model->hidden = $data['hidden'];
        $model->status = $data['status'];
        $model->breadcrumb = $data['breadcrumb'];
        $model->path = $data['path'];
        $model->component = $data['component'];
        $model->parent_id = $data['parent_id'];
        $model->query = $data['query'];
        $model->show_tag = $data['show_tag'];
        $model->perms = $data['perms'];
        $model->active_menu = $data['active_menu'];
        if (!$model->save()) {
            return ['code' => 1, 'msg' => '保存失败'];
        }
        return ['code' => 0, 'msg' => '保存成功'];

    }

    /**
     * Created by PhpStorm.
     * User：ganxi
     * Date：2022/4/12
     * Time：13:39
     * Note：设置头像
     */
    public function actionSetAvatar()
    {
        $data = $this->request->post();
        /**
         * @var Admin $admin
         */
        $admin = \Yii::$app->admin->identity;
        if ($data['avatar']) {
            $admin->avatar = $data['avatar'];
        }

        if (!$admin->save()) {
            return ResponseHelper::send(ApiCode::CODE_FAIL, '保存失败');
        }
        return ResponseHelper::send(ApiCode::CODE_SUCCESS, '更新成功');
    }


    /**
     * @Author: 动力宇宙 ganxiaohao
     * @Date: 2022-04-07
     * @Time: 21:31
     * @Note:个人信息页面
     */
    public function actionProfile()
    {
        /**
         * @var Admin $admin
         */
        $admin = \Yii::$app->admin->identity;
        $roles = Role::find()->alias('r')
            ->innerJoin(['a' => AdminRole::tableName()], 'a.role_id=r.id')->where(['a.admin_id' => $admin->id])
            ->select('a.*,a.id as role_id')
            ->asArray()->all();
        $roleIds = AdminRole::find()->where(['admin_id' => $admin->id])->select('role_id')->column();
        $roleKeys = Role::find()->alias('r')
            ->innerJoin(['a' => AdminRole::tableName()], 'a.role_id=r.id')->where(['a.admin_id' => $admin->id])
            ->select('r.role_key')
            ->column();
        $role = Role::findOne($admin->role_id);
        if ($admin->admin_type == Admin::ADMIN_TYPE_SUPER) {
            $permissions = ["*:*:*"];
        } else {
            $permissions = RoleMenu::find()->alias('r')
                ->innerJoin(['m' => Menu::tableName()], 'm.menu_id=r.menu_id')
                ->andWhere(['r.role_id' => $admin->role_id])
                ->select('perms')->column();
        }
        return [
            'code' => 0,
            'msg' => '操作成功',
            'permissions' => $permissions,
            'roles' => $roleKeys,
            'user' => [
                "created_time" => $admin->created_time,
                "remark" => $admin->remark,
                "params" => [],
                "user_id" => $admin->id,
                "username" => $admin->username,
                "nickname" => $admin->nickname,
                "email" => $admin->email,
                "mobile" => $admin->mobile,
                "avatar" => $admin->avatar,
                "status" => "0",
                "login_ip" => $admin->login_ip,
                "roles" => $roles,
                "roleIds" => $roleIds,
                "role_id" => $admin->role_id,
                "admin" => $role && $role->admin ? true : false
            ]
        ];
    }

    /**
     * @Author: 动力宇宙 ganxiaohao
     * @Date: 2022-04-07
     * @Time: 21:35
     * @Note:更新个人信息
     */
    public function actionProfileUpdate()
    {

        $data = $this->request->post();
        /**
         * @var Admin $admin
         */
        $admin = \Yii::$app->admin->identity;
        if ($data['nickname']) {
            $admin->nickname = $data['nickname'];
        }
        if ($data['email']) {
            $admin->email = $data['email'];
        }
        if ($data['mobile']) {
            $admin->mobile = $data['mobile'];
        }
        if (!$admin->save()) {
            return ResponseHelper::send(ApiCode::CODE_FAIL, '保存失败');
        }
        return ResponseHelper::send(ApiCode::CODE_SUCCESS, '更新成功');
    }


    /**
     * @Author: 动力宇宙 ganxiaohao
     * @Date: 2022-04-07
     * @Time: 21:31
     * @Note:登录之后查询的个人信息
     * @return array
     */

    public function actionInfo()
    {
        /**
         * @var Admin $admin
         */
        $admin = \Yii::$app->admin->identity;
        $roles = Role::find()->alias('r')
            ->innerJoin(['a' => AdminRole::tableName()], 'a.role_id=r.id')
            ->andWhere(['a.admin_id' => $admin->id])
            ->select('a.*,a.id as role_id')
            ->asArray()->all();
        $roleIds = AdminRole::find()->where(['admin_id' => $admin->id])->select('role_id')->column();
        $roleKeys = Role::find()->alias('r')
            ->innerJoin(['a' => AdminRole::tableName()], 'a.role_id=r.id')
            ->andWhere(['a.admin_id' => $admin->id])
            ->select('r.role_key')
            ->column();

        $role = Role::findOne($admin->role_id);

        if ($admin->admin_type == Admin::ADMIN_TYPE_SUPER) {
            $permissions = array_values(["*:*:*"]);
        } else {
            $permissions = RoleMenu::find()->alias('r')
                ->innerJoin(['m' => Menu::tableName()], 'm.menu_id=r.menu_id')
                ->andWhere(['r.role_id' => $admin->role_id])
                ->select('perms')->column();
            $permissions = array_values(array_filter($permissions));
        }
        return [
            'code' => 0,
            'msg' => '操作成功',
            'permissions' => $permissions,
            'roles' => $roleKeys,
            'user' => [
                "created_time" => $admin->created_time,
                "remark" => $admin->remark,
                "params" => [],
                "user_id" => $admin->id,
                "username" => $admin->username,
                "nickname" => $admin->nickname,
                "email" => $admin->email,
                "mobile" => $admin->mobile,
                "avatar" => $admin->avatar,
                "status" => "0",
                "login_ip" => $admin->login_ip,
                "roles" => $roles,
                "roleIds" => $roleIds,
                "role_id" => $admin->role_id,
                "admin" => $role && $role->admin ? true : false
            ]
        ];
    }

    /**
     * Created by PhpStorm.
     * User：ganxi
     * Date：2022/4/4
     * Time：16:36
     * Note：管理员删除
     */
    public function actionDelete()
    {
        $form = new AdminDeleteForm();
        $form->attributes = $this->request->post();
        return $this->asJson($form->save());
    }


    /**
     * Created by PhpStorm.
     * User：ganxi
     * Date：2022/4/12
     * Time：13:38
     * Note：系统管理员列表
     */
    public function actionUserList()
    {
        $data = $this->request->get();
        $params = null;
        if ($data['params']) {
            $params = Json::decode($data['params']);
        }
        $query = Admin::find()
            ->andWhere(['created_by' => \Yii::$app->admin->identity->getId()])
            ->andWhere(['is_delete' => 0])
            ->andFilterWhere(['like', 'mobile', $data['mobile']])
            ->andFilterWhere(['like', 'username', $data['username']])
            ->andFilterWhere(['like', 'nickname', $data['nickname']]);

        if ($params) {
            $query->andWhere(['between', 'created_time', $params['beginTime'] . ' 00:00:01', $params['endTime'] . ' 23:59:59']);
        }
        $list = $query->orderBy('id asc')
            ->select('id,nickname,avatar,username,name,mobile,email,role_id,created_time')
            ->asArray()->all();
        return [
            "total" => 2,
            "rows" => $list,
            "code" => 0,
            "msg" => "查询成功"
        ];
    }

    /**
     * Created by PhpStorm.
     * User：ganxi
     * Date：2022/4/4
     * Time：16:38
     * Note：管理员列表
     */
    public function actionList()
    {
        $form = new AdminListForm();
        $form->attributes = $this->request->get();
        return $form->getList();
    }


    /**
     * Created by PhpStorm.
     * User：ganxi
     * Date：2022/4/4
     * Time：16:40
     * Note：所有菜单
     */
    public function actionMenuList()
    {
        $form = new MenuListForm();
        $form->attributes = $this->request->get();
        return $form->getList();
    }


    /**
     * @Author: 动力宇宙 ganxiaohao
     * @Date: 2020-09-29
     * @Time: 13:36
     * @Note:退出登录
     * @return \yii\web\Response
     */
    public function actionLogout()
    {
        $form = new LogoutForm();
        return $this->asJson($form->logout());
    }

    /**
     * @Author: 动力宇宙 ganxiaohao
     * @Date: 2020-09-29
     * @Time: 13:36
     * @Note: 修改密码
     */
    public function actionPasswordChange()
    {
        $form = new PasswordChangeForm();
        $form->attributes = $this->request->post();
        return $this->asJson($form->save());
    }

    /**
     * Created by PhpStorm.
     * User：ganxi
     * Date：2022/4/12
     * Time：13:37
     * Note：api列表
     */
    public function actionApiList()
    {
        $form = new ApiListForm();
        $form->attributes = $this->request->get();
        return $this->asJson($form->getList());
    }

    /**
     * Created by PhpStorm.
     * User：ganxi
     * Date：2022/4/12
     * Time：13:37
     * Note：创建API
     */
    public function actionCreateApi()
    {
        $form = new ApiForm();
        $form->attributes = $this->request->post();
        return $this->asJson($form->save());
    }

    /**
     * Created by PhpStorm.
     * User：ganxi
     * Date：2022/4/12
     * Time：13:37
     * Note：更新API
     */
    public function actionUpdateApi()
    {
        $form = new ApiForm();
        $form->attributes = $this->request->post();
        return $this->asJson($form->save());
    }

    /**
     * Created by PhpStorm.
     * User：ganxi
     * Date：2022/4/12
     * Time：13:37
     * Note：api详情
     */
    public function actionApi()
    {
        $form = new ApiForm();
        $form->attributes = $this->request->get();
        return $this->asJson($form->getInfo());
    }

    /**
     * Created by PhpStorm.
     * User：ganxi
     * Date：2022/4/12
     * Time：13:37
     * Note：删除api
     */
    public function actionDeleteApi()
    {
        $form = new ApiForm();
        $form->attributes = $this->request->post();
        return $this->asJson($form->delete());
    }

    /**
     * Created by PhpStorm.
     * User：ganxi
     * Date：2022/4/12
     * Time：13:37
     * Note：删除所有api
     */
    public function actionDeleteApis()
    {
        $form = new ApiForm();
        $form->attributes = $this->request->post();
        return $this->asJson($form->deleteByIds());
    }

    /**
     * Created by PhpStorm.
     * User：ganxi
     * Date：2022/4/12
     * Time：13:38
     * Note：获取所有api
     */
    public function actionAllApis()
    {
        $form = new ApiForm();
        return $this->asJson($form->getList());
    }


}