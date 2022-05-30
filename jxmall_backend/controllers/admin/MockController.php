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
use app\forms\admin\AdminAuthoritiesUpdateForm;
use app\forms\admin\AdminDeleteForm;
use app\forms\admin\AdminEditForm;
use app\forms\admin\AdminEditSelfInfoForm;
use app\forms\admin\AdminListForm;
use app\forms\admin\ApiForm;
use app\forms\admin\ApiListForm;
use app\forms\admin\AuthorityApiForm;
use app\forms\admin\LogoutForm;
use app\forms\admin\MenuDeleteForm;
use app\forms\admin\MenuListForm;
use app\forms\admin\PasswordChangeForm;
use app\forms\admin\PasswordResetForm;
use app\forms\admin\RemoveAuthorityBtnForm;
use app\forms\admin\UpdateCasbinForm;
use app\helpers\AuthorityHelper;
use app\helpers\ResponseHelper;
use app\models\Admin;
use app\models\AdminAuthority;
use app\models\AdminRole;
use app\models\Authority;
use app\models\AuthorityMenus;
use app\models\BaseMenu;
use app\models\Menu;
use app\models\MenuButton;
use app\models\MenuParameter;
use app\models\Role;
use app\models\RoleMenu;
use app\plugins\area\forms\mall\SettingForm;

class MockController extends Controller
{


    public function actionEdit()
    {

        $params = "username: admin
nickname: 111
password: admin
phonenumber: 13255555555
email: ganxiaohao520@163.com
status: 0
remark: ddddd
roleIds[0]: 2";


    }

    public function actionUser()
    {

        return [
            "msg" => "操作成功",
            "code" => 0,
            "roleIds" => [
                2
            ],
            "data" => [
                "searchValue" => null,
                "createBy" => "admin",
                "createTime" => "2021-09-09 17:25:29",
                "updateBy" => null,
                "updateTime" => null,
                "remark" => "测试员",
                "params" => [],
                "userId" => 2,
                "deptId" => 105,
                "username" => "ry",
                "nickname" => "若依",
                "email" => "ry@qq.com",
                "phonenumber" => "15666666666",
                "sex" => "1",
                "avatar" => "",
                "status" => "0",
                "delFlag" => "0",
                "loginIp" => "125.215.45.69",
                "loginDate" => "2022-04-06T13:34:05.000+08:00",
                "dept" => [
                    "searchValue" => null,
                    "createBy" => null,
                    "createTime" => null,
                    "updateBy" => null,
                    "updateTime" => null,
                    "remark" => null,
                    "params" => [],
                    "deptId" => 105,
                    "parent_id" => 101,
                    "ancestors" => null,
                    "deptName" => "测试部门",
                    "order_num" => "3",
                    "leader" => "若依",
                    "phone" => null,
                    "email" => null,
                    "status" => "0",
                    "delFlag" => null,
                    "parentName" => null,
                    "children" => []
                ],
                "roles" => [
                    [
                        "searchValue" => null,
                        "createBy" => null,
                        "createTime" => null,
                        "updateBy" => null,
                        "updateTime" => null,
                        "remark" => null,
                        "params" => [],
                        "role_id" => 2,
                        "role_name" => "普通角色",
                        "role_key" => "common",
                        "role_sort" => "2",
                        "dataScope" => "2",
                        "menu_check_strictly" => false,
                        "dept_check_strictly" => false,
                        "status" => "1",
                        "delFlag" => null,
                        "flag" => false,
                        "menuIds" => null,
                        "deptIds" => null,
                        "admin" => false
                    ]
                ],
                "roleIds" => null,
                "postIds" => null,
                "role_id" => null,
                "admin" => false
            ],
            "postIds" => [
                2
            ],
            "roles" => [
                [
                    "searchValue" => null,
                    "createBy" => null,
                    "createTime" => "2021-09-09 17:25:37",
                    "updateBy" => null,
                    "updateTime" => null,
                    "remark" => "普通角色",
                    "params" => [],
                    "role_id" => 2,
                    "role_name" => "普通角色",
                    "role_key" => "common",
                    "role_sort" => "2",
                    "dataScope" => "2",
                    "menu_check_strictly" => true,
                    "dept_check_strictly" => true,
                    "status" => "0",
                    "delFlag" => "0",
                    "flag" => false,
                    "menuIds" => null,
                    "deptIds" => null,
                    "admin" => false
                ]
            ],
            "posts" => [
                [
                    "searchValue" => null,
                    "createBy" => "admin",
                    "createTime" => "2021-09-09 17:25:33",
                    "updateBy" => null,
                    "updateTime" => null,
                    "remark" => "",
                    "params" => [],
                    "postId" => 1,
                    "postCode" => "ceo",
                    "postName" => "董事长",
                    "postSort" => "1",
                    "status" => "0",
                    "flag" => false
                ],
                [
                    "searchValue" => null,
                    "createBy" => "admin",
                    "createTime" => "2021-09-09 17:25:33",
                    "updateBy" => null,
                    "updateTime" => null,
                    "remark" => "",
                    "params" => [],
                    "postId" => 2,
                    "postCode" => "se",
                    "postName" => "项目经理",
                    "postSort" => "2",
                    "status" => "0",
                    "flag" => false
                ],
                [
                    "searchValue" => null,
                    "createBy" => "admin",
                    "createTime" => "2021-09-09 17:25:34",
                    "updateBy" => null,
                    "updateTime" => null,
                    "remark" => "",
                    "params" => [],
                    "postId" => 3,
                    "postCode" => "hr",
                    "postName" => "人力资源",
                    "postSort" => "3",
                    "status" => "0",
                    "flag" => false
                ],
                [
                    "searchValue" => null,
                    "createBy" => "admin",
                    "createTime" => "2021-09-09 17:25:34",
                    "updateBy" => null,
                    "updateTime" => null,
                    "remark" => "",
                    "params" => [],
                    "postId" => 4,
                    "postCode" => "user",
                    "postName" => "普通员工",
                    "postSort" => "4",
                    "status" => "0",
                    "flag" => false
                ]
            ]
        ];
    }

    public function actionUserList()
    {

        $list = Admin::find()->andWhere(['is_delete' => 0])->orderBy('id asc')
            ->select('id,nickname,avatar,username,name,mobile,email,role_id,created_time')->asArray()->all();
        return [
            "total" => 2,
            "rows" => $list,
            "code" => 0,
            "msg" => "查询成功"
        ];
    }

    public function actionRoleEdit()
    {
        $data = $this->request->post();
        $menuIds = $data['menuIds'];
        $model = Role::getOne($data['role_id']);
        if (!$model) {
            $model = new Role();
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


    public function getMenuList($parent_id = 0)
    {
        $list = Menu::find()
            ->andWhere(['parent_id' => $parent_id])
            ->andWhere(['is_delete' => 0])
            ->orderBy('sort asc')
            ->select('')
            ->asArray()->all();
        foreach ($list as &$item) {
            $item['authoritys'] = null;
            $item['menuBtn'] = null;
            $item['ID'] = $item['id'];
            $item['parameters'] = [];
            $item['btns'] = null;
            $item['hidden'] = $item['hidden'] == 1 ? true : false;
            $item['parentId'] = $item['parent_id'];
            $item['meta'] = [
                "keepAlive" => $item['keep_alive'] == 1 ? true : false,
                "defaultMenu" => $item['default_menu'] == 1 ? true : false,
                "title" => $item['title'],
                "icon" => $item['icon'],
                'hidden' => $item['hidden'] == 1 ? true : false,
                'btns' => null,
                "closeTab" => $item['close_tab'] == 1 ? true : false,
            ];
            $item['menuBtn'] = [];
            $exist = BaseMenu::find()->andWhere(['is_delete' => 0, 'parent_id' => $item['id']])->exists();
            if ($exist) {
                $item['children'] = self::getMenuList($item['id']);
            }
        }
        return $list;
    }


    public function actionMenuTree()
    {


        return [
            "msg" => "操作成功",
            "code" => 0,
            "data" => [
                [
                    "id" => 1,
                    "label" => "系统管理",
                    "children" => [
                        [
                            "id" => 100,
                            "label" => "用户管理",
                            "children" => [
                                [
                                    "id" => 1001,
                                    "label" => "用户查询"
                                ],
                                [
                                    "id" => 1002,
                                    "label" => "用户新增"
                                ],
                                [
                                    "id" => 1003,
                                    "label" => "用户修改"
                                ],
                                [
                                    "id" => 1004,
                                    "label" => "用户删除"
                                ],
                                [
                                    "id" => 1005,
                                    "label" => "用户导出"
                                ],
                                [
                                    "id" => 1006,
                                    "label" => "用户导入"
                                ],
                                [
                                    "id" => 1007,
                                    "label" => "重置密码"
                                ]
                            ]
                        ],
                        [
                            "id" => 101,
                            "label" => "角色管理",
                            "children" => [
                                [
                                    "id" => 1008,
                                    "label" => "角色查询"
                                ],
                                [
                                    "id" => 1009,
                                    "label" => "角色新增"
                                ],
                                [
                                    "id" => 1010,
                                    "label" => "角色修改"
                                ],
                                [
                                    "id" => 1011,
                                    "label" => "角色删除"
                                ],
                                [
                                    "id" => 1012,
                                    "label" => "角色导出"
                                ]
                            ]
                        ],
                        [
                            "id" => 102,
                            "label" => "菜单管理",
                            "children" => [
                                [
                                    "id" => 1013,
                                    "label" => "菜单查询"
                                ],
                                [
                                    "id" => 1014,
                                    "label" => "菜单新增"
                                ],
                                [
                                    "id" => 1015,
                                    "label" => "菜单修改"
                                ],
                                [
                                    "id" => 1016,
                                    "label" => "菜单删除"
                                ]
                            ]
                        ],
                        [
                            "id" => 103,
                            "label" => "部门管理",
                            "children" => [
                                [
                                    "id" => 1017,
                                    "label" => "部门查询"
                                ],
                                [
                                    "id" => 1018,
                                    "label" => "部门新增"
                                ],
                                [
                                    "id" => 1019,
                                    "label" => "部门修改"
                                ],
                                [
                                    "id" => 1020,
                                    "label" => "部门删除"
                                ]
                            ]
                        ],
                        [
                            "id" => 104,
                            "label" => "岗位管理",
                            "children" => [
                                [
                                    "id" => 1021,
                                    "label" => "岗位查询"
                                ],
                                [
                                    "id" => 1022,
                                    "label" => "岗位新增"
                                ],
                                [
                                    "id" => 1023,
                                    "label" => "岗位修改"
                                ],
                                [
                                    "id" => 1024,
                                    "label" => "岗位删除"
                                ],
                                [
                                    "id" => 1025,
                                    "label" => "岗位导出"
                                ]
                            ]
                        ],
                        [
                            "id" => 105,
                            "label" => "字典管理",
                            "children" => [
                                [
                                    "id" => 1026,
                                    "label" => "字典查询"
                                ],
                                [
                                    "id" => 1027,
                                    "label" => "字典新增"
                                ],
                                [
                                    "id" => 1028,
                                    "label" => "字典修改"
                                ],
                                [
                                    "id" => 1029,
                                    "label" => "字典删除"
                                ],
                                [
                                    "id" => 1030,
                                    "label" => "字典导出"
                                ]
                            ]
                        ],
                        [
                            "id" => 106,
                            "label" => "参数设置",
                            "children" => [
                                [
                                    "id" => 1031,
                                    "label" => "参数查询"
                                ],
                                [
                                    "id" => 1032,
                                    "label" => "参数新增"
                                ],
                                [
                                    "id" => 1033,
                                    "label" => "参数修改"
                                ],
                                [
                                    "id" => 1034,
                                    "label" => "参数删除"
                                ],
                                [
                                    "id" => 1035,
                                    "label" => "参数导出"
                                ]
                            ]
                        ],
                        [
                            "id" => 107,
                            "label" => "通知公告",
                            "children" => [
                                [
                                    "id" => 1036,
                                    "label" => "公告查询"
                                ],
                                [
                                    "id" => 1037,
                                    "label" => "公告新增"
                                ],
                                [
                                    "id" => 1038,
                                    "label" => "公告修改"
                                ],
                                [
                                    "id" => 1039,
                                    "label" => "公告删除"
                                ]
                            ]
                        ],
                        [
                            "id" => 108,
                            "label" => "日志管理",
                            "children" => [
                                [
                                    "id" => 500,
                                    "label" => "操作日志",
                                    "children" => [
                                        [
                                            "id" => 1040,
                                            "label" => "操作查询"
                                        ],
                                        [
                                            "id" => 1041,
                                            "label" => "操作删除"
                                        ],
                                        [
                                            "id" => 1042,
                                            "label" => "日志导出"
                                        ]
                                    ]
                                ],
                                [
                                    "id" => 501,
                                    "label" => "登录日志",
                                    "children" => [
                                        [
                                            "id" => 1043,
                                            "label" => "登录查询"
                                        ],
                                        [
                                            "id" => 1044,
                                            "label" => "登录删除"
                                        ],
                                        [
                                            "id" => 1045,
                                            "label" => "日志导出"
                                        ]
                                    ]
                                ]
                            ]
                        ]
                    ]
                ],
                [
                    "id" => 2,
                    "label" => "系统监控",
                    "children" => [
                        [
                            "id" => 109,
                            "label" => "在线用户",
                            "children" => [
                                [
                                    "id" => 1046,
                                    "label" => "在线查询"
                                ],
                                [
                                    "id" => 1047,
                                    "label" => "批量强退"
                                ],
                                [
                                    "id" => 1048,
                                    "label" => "单条强退"
                                ]
                            ]
                        ],
                        [
                            "id" => 110,
                            "label" => "定时任务",
                            "children" => [
                                [
                                    "id" => 1049,
                                    "label" => "任务查询"
                                ],
                                [
                                    "id" => 1050,
                                    "label" => "任务新增"
                                ],
                                [
                                    "id" => 1051,
                                    "label" => "任务修改"
                                ],
                                [
                                    "id" => 1052,
                                    "label" => "任务删除"
                                ],
                                [
                                    "id" => 1053,
                                    "label" => "状态修改"
                                ],
                                [
                                    "id" => 1054,
                                    "label" => "任务导出"
                                ]
                            ]
                        ],
                        [
                            "id" => 111,
                            "label" => "数据监控"
                        ],
                        [
                            "id" => 112,
                            "label" => "服务监控"
                        ],
                        [
                            "id" => 113,
                            "label" => "缓存监控"
                        ]
                    ]
                ],
                [
                    "id" => 3,
                    "label" => "系统工具",
                    "children" => [
                        [
                            "id" => 114,
                            "label" => "表单构建"
                        ],
                        [
                            "id" => 115,
                            "label" => "代码生成",
                            "children" => [
                                [
                                    "id" => 1055,
                                    "label" => "生成查询"
                                ],
                                [
                                    "id" => 1056,
                                    "label" => "生成修改"
                                ],
                                [
                                    "id" => 1058,
                                    "label" => "导入代码"
                                ],
                                [
                                    "id" => 1057,
                                    "label" => "生成删除"
                                ],
                                [
                                    "id" => 1059,
                                    "label" => "预览代码"
                                ],
                                [
                                    "id" => 1060,
                                    "label" => "生成代码"
                                ]
                            ]
                        ],
                        [
                            "id" => 116,
                            "label" => "系统接口"
                        ]
                    ]
                ],
                [
                    "id" => 4,
                    "label" => "若依官网"
                ]
            ]
        ];
    }


    public function actionAddMenu()
    {
        $data = $this->request->post();
        $model = new Menu();
        $model->menu_name = $data['menu_name'];
        $model->icon = $data['icon'];
        $model->menu_type = $data['menu_type'];
        $model->order_num = $data['order_num'];
        $model->is_cache = $data['is_cache'];
        $model->is_frame = $data['is_frame'];
        $model->hidden = $data['hidden'];
        $model->status = $data['status'];
        $model->path = $data['path'];
        $model->component = $data['component'];
        $model->query = $data['query'];
        $model->perms = $data['perms'];
        $model->parent_id = $data['parent_id'];
        $model->menu_id = uniqid();
        $model->save();
        return ['code' => 0, 'msg' => '保存成功'];
    }



    public function actionMenu()
    {
        $menu = Menu::findOne(['menu_id' => $this->request->get('menu_id')]);
        return [
            "msg" => "操作成功",
            "code" => 0,
            'data' => $menu->attributes
        ];

    }

    public function actionMenusList()
    {
        $list = Menu::find()->orderBy('order_num asc')->asArray()->all();
        return [
            "msg" => "操作成功",
            "code" => 0,
            'data' => $list
        ];
    }

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

    public function actionRoleMenuTreeSelect()
    {
        $role_id = $this->request->get('role_id');
        $menuIds = RoleMenu::find()->andWhere(['role_id' => $role_id])->select('menu_id')->column();
        $list = Menu::find()->andWhere(['parent_id' => 0])->orderBy('order_num asc')->select('menu_id as id,menu_name as label')->asArray()->all();
        foreach ($list as &$item) {
            $children1 = Menu::find()->andWhere(['parent_id' => $item['id']])->orderBy('order_num asc')->select('menu_id as id,menu_name as label')->asArray()->all();
            foreach ($children1 as &$item1) {
                $children2 = Menu::find()->andWhere(['parent_id' => $item1['id']])->orderBy('order_num asc')->select('menu_id as id,menu_name as label')->asArray()->all();
                $item1['children'] = $children2;
            }
            unset($item1);
            $item['children'] = $children1;
        }
        unset($item);
        return [
            "msg" => "操作成功",
            "code" => 0,
            "menus" => $list,
            "checkedKeys" => $menuIds
        ];
    }

    public function actionRoles()
    {

        $list = Role::find()->andWhere(['is_delete' => 0])->orderBy('admin desc')->orderBy('id asc')->select('*,id as role_id')->asArray()->all();
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


    public function actionRouters()
    {
        $menuIds = RoleMenu::find()->andWhere(['role_id' => 1])->asArray()->all();
        $list = Menu::find()->andWhere(['menu_type' => 'M', 'parent_id' => 0])
            ->andWhere(['menu_id' => $menuIds])
            ->select('menu_id,menu_name,icon,hidden,is_cache,link,path,component')
            ->orderBy('order_num asc')->asArray()->all();
        $arr = [];
        foreach ($list as $item) {
            $row = [];
            $row['hidden'] = $item['hidden'] ? true : false;
            $row['redirect'] = 'noRedirect';
            $row['alwaysShow'] = true;
            $row['component'] = 'Layout';
            $row['name'] = ucwords($item['path']);
            $row['path'] = '/' . $item['path'];
            $meta = [
                'title' => $item['menu_name'],
                'icon' => $item['icon'],
                'noCache' => $item['is_cache'] ? false : true,
                'link' => $item['link'] ?? null
            ];
            $row['meta'] = $meta;
            $children = Menu::find()->andWhere(['menu_type' => 'C', 'parent_id' => $item['menu_id']])
                ->andWhere(['menu_id' => $menuIds])
                ->orderBy('order_num asc')
                ->select('menu_id,menu_name,icon,hidden,is_cache,link,path,component')
                ->asArray()->all();
            $arr2 = [];
            foreach ($children as $child) {
                $row2 = [];
                $row2['hidden'] = $child['hidden'] ? true : false;
                $row2['name'] = ucwords($child['path']);
                $row2['path'] = $child['path'];
                $row2['component'] = $child['component'];
                $meta = [
                    'title' => $child['menu_name'],
                    'icon' => $child['icon'],
                    'noCache' => $child['is_cache'] ? true : false,
                    'link' => $child['link'] ?? null
                ];

                $row2['meta'] = $meta;
                $arr2[] = $row2;
            }
            unset($row2);
            $row['children'] = $arr2;
            $arr[] = $row;
        }
        unset($row);
        return [
            "msg" => "操作成功",
            "code" => 0,
            'data' => $arr
        ];
    }

    public function actionInfo()
    {
        $admin = Admin::findOne(1);
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
        return [
            'code' => 0,
            'msg' => '操作成功',
            'permissions' => ["*:*:*"],
            'roles' => $roleKeys,
            'user' => [
                "created_time" => $admin->created_time,
                "remark" => $admin->remark,
                "params" => [],
                "userId" => 1,
                "deptId" => 103,
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
     * Date：2022/4/4
     * Time：16:59
     * Note：更新用户角色
     */
    public function actionSetUserAuthorities()
    {
        $form = new AdminAuthoritiesUpdateForm();
        $form->attributes = $this->request->post();
        return $this->asJson($form->save());
    }

    /**
     * Created by PhpStorm.
     * User：ganxi
     * Date：2022/4/4
     * Time：16:38
     * Note：用户列表
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
     * Time：16:39
     * Note：角色的菜单
     */
    public function actionMenus()
    {

        //根据角色获取菜单
        //所有的菜单
        $menusIs = AuthorityMenus::find()->alias('a')
            ->innerJoin(['m' => BaseMenu::tableName()], 'm.id=a.base_menu_id')
            ->andWhere(['a.authority_id' => \Yii::$app->admin->identity->authority_id])
            ->select('m.id')->column();
        $list = AuthorityHelper::getMenuListByIds($menusIs, 0);
        return ResponseHelper::send(ApiCode::CODE_SUCCESS, '', ['menus' => $list]);
    }

    /**
     * Created by PhpStorm.
     * User：ganxi
     * Date：2022/4/4
     * Time：16:40
     * Note：角色菜单
     */
    public function actionMenuList()
    {
        $form = new MenuListForm();
        $form->attributes = $this->request->get();
        return $this->asJson($form->getList());
    }


    /**
     * Created by PhpStorm.
     * User：ganxi
     * Date：2022/4/4
     * Time：16:42
     * Note：添加菜单
     */
    public function actionAddBaseMenu()
    {

        $data = $this->request->post();
        $model = new BaseMenu();
        $model->name = $data['name'];
        $model->hidden = $data['hidden'] === true ? 1 : 0;
        $model->parent_id = $data['parent_id'];
        $model->component = $data['component'];
        $model->title = $data['title'];
        $model->icon = $data['icon'];
        $model->default_menu = $data['defaultMenu'] ? 1 : 0;
        $model->keep_alive = $data['keepAlive'] ? 1 : 0;
        $model->close_tab = $data['closeTab'] ? 1 : 0;
        $model->path = $data['path'];
        $meta = $data['meta'];
        $model->title = $meta['title'];
        $model->icon = $meta['icon'];
        $model->default_menu = $meta['defaultMenu'] === true ? 1 : 0;
        $model->keep_alive = $meta['keepAlive'] === true ? 1 : 0;
        $model->close_tab = $meta['closeTab'] === true ? 1 : 0;
        if (!$model->save()) {
            return ResponseHelper::send(ApiCode::CODE_FAIL, '保存失败！');
        }
        $parameters = $data['parameters'];
        $menuBtn = $data['menuBtn'];
        if ($parameters && is_array($parameters)) {
            MenuParameter::deleteAll(['base_menu_id' => $model->id]);
            foreach ($parameters as $item) {
                $par = new MenuParameter();
                $par->base_menu_id = $model->id;
                $par->key = $item['key'];
                $par->value = $item['value'];
                $par->type = $item['type'];
                $par->save();
            }
        }
        if ($menuBtn && is_array($menuBtn)) {
            MenuButton::deleteAll(['base_menu_id' => $model->id]);
            foreach ($menuBtn as $item) {
                $btn = new MenuButton();
                $btn->base_menu_id = $model->id;
                $btn->name = $item['name'];
                $btn->description = $item['description'];
                $btn->save();
            }
        }

        return ResponseHelper::send(ApiCode::CODE_SUCCESS, '保存成功');
    }

    /**
     * Created by PhpStorm.
     * User：ganxi
     * Date：2022/4/4
     * Time：16:43
     * Note：获取基础菜单列表
     */
    public function actionBaseMenuTree()
    {
        return ResponseHelper::send(ApiCode::CODE_SUCCESS, '', ['menus' => AuthorityHelper::getMenuList(0)]);
    }

    /**
     * 角色菜单
     * 这里要递归操作，未完成
     */
    public function actionAddMenuAuthority()
    {
        $data = $this->request->post();
        $menus = $data['menus'];
        $authority_id = $data['authority_id'];
        $authority = Authority::findOne(['authority_id' => $authority_id, 'is_delete' => 0]);
        if (!$authority) {
            return ResponseHelper::send(ApiCode::CODE_FAIL, '角色不存在！');
        }
        AuthorityMenus::deleteAll(['authority_id' => $authority_id]);
        \Yii::warning($menus);
        foreach ($menus as $i => $menu) {
            $menuCur = BaseMenu::getOne($menu['id']);
            $model = new AuthorityMenus();
            $model->authority_id = $authority_id;
            $model->base_menu_id = $menu['id'];
            if (!$model->save()) {
                \Yii::warning($model->getFirstErrors());
            }

            if ($i == 0) {
                $authority->default_router = $menuCur->path;
                $authority->save();
            }
            if ($menuCur && $menuCur->parent_id) {
                $exist = AuthorityMenus::findOne(['base_menu_id' => $menuCur->parent_id, 'authority_id' => $authority_id, 'is_delete' => 0]);
                if (!$exist) {
                    $model = new AuthorityMenus();
                    $model->authority_id = $authority_id;
                    $model->base_menu_id = $menuCur->parent_id;
                    if (!$model->save()) {
                        \Yii::warning($model->getFirstErrors());
                    }
                }
            }

        }
        return ResponseHelper::send(ApiCode::CODE_SUCCESS, '添加成功');
    }

    public function actionDeleteBaseMenu()
    {
        $form = new MenuDeleteForm();
        $form->attributes = $this->request->post();
        return $form->delete();
    }


    /**
     * 通过角色ID获取角色菜单权限
     */
    public function actionMenuAuthority()
    {

        //根据角色获取菜单
        $data = $this->request->post();
        $authority_id = $data['authority_id'];
        //所有的菜单
        $list = AuthorityMenus::find()->alias('a')
            ->innerJoin(['m' => BaseMenu::tableName()], 'm.id=a.base_menu_id')
            ->andWhere(['a.authority_id' => $authority_id])
            ->select('m.*')
            ->asArray()->all();
        foreach ($list as &$item) {
            $item['parent_id'] = $item['parent_id'];
            $item['menuId'] = $item['id'];
        }
        unset($item);
        return ResponseHelper::send(ApiCode::CODE_SUCCESS, '', ['menus' => $list]);
    }


    public function actionUpdateBaseMenu()
    {
        $data = $this->request->post();
        $model = BaseMenu::getOne($data['id']);
        if (!$model) {
            return ResponseHelper::send(ApiCode::CODE_FAIL, '菜单不存在！');
        }
        $model->name = $data['name'];
        $model->hidden = $data['hidden'] === true ? 1 : 0;
        $model->parent_id = $data['parent_id'];
        $model->component = $data['component'];
        $model->title = $data['title'];
        $model->icon = $data['icon'];
        $model->sort = $data['sort'];
        $model->default_menu = $data['defaultMenu'] ? 1 : 0;
        $model->keep_alive = $data['keepAlive'] ? 1 : 0;
        $model->close_tab = $data['closeTab'] ? 1 : 0;
        $model->path = $data['path'];
        $meta = $data['meta'];
        $model->title = $meta['title'];
        $model->icon = $meta['icon'];
        $model->default_menu = $meta['defaultMenu'] === true ? 1 : 0;
        $model->keep_alive = $meta['keepAlive'] === true ? 1 : 0;
        $model->close_tab = $meta['closeTab'] === true ? 1 : 0;
        if (!$model->save()) {
            return ResponseHelper::send(ApiCode::CODE_FAIL, '保存失败！');
        }
        $parameters = $data['parameters'];
        $menuBtn = $data['menuBtn'];
        if ($parameters && is_array($parameters)) {
            MenuParameter::deleteAll(['base_menu_id' => $model->id]);
            foreach ($parameters as $item) {
                $par = new MenuParameter();
                $par->base_menu_id = $model->id;
                $par->key = $item['key'];
                $par->value = $item['value'];
                $par->type = $item['type'];
                $par->save();
            }
        }
        if ($menuBtn && is_array($menuBtn)) {
            MenuButton::deleteAll(['base_menu_id' => $model->id]);
            foreach ($menuBtn as $item) {
                $btn = new MenuButton();
                $btn->base_menu_id = $model->id;
                $btn->name = $item['name'];
                $btn->description = $item['description'];
                $btn->save();
            }
        }
        return ResponseHelper::send(ApiCode::CODE_SUCCESS, '保存成功');
    }

    public function actionBaseMenu()
    {
        $data = $this->request->post();
        $id = $data['id'];
        $model = BaseMenu::getOne($id);
        $info = $model->attributes;
        $meta = [
            'keepAlive' => $model->keep_alive ? true : false,
            "defaultMenu" => $model->default_menu ? true : false,
            "title" => $model->title,
            "icon" => $model->icon,
            "closeTab" => $model->close_tab ? true : false
        ];
        $info['title'] = $model->title;
        $info['meta'] = $meta;
        $info['hidden'] = $model->hidden ? true : false;
        $info['parent_id'] = $model->parent_id;
        $info['menuBtn'] = [];
        $info['authoritys'] = null;
        $parameters = MenuParameter::find()->andWhere(['base_menu_id' => $id, 'is_delete' => 0])->orderBy('id asc')->asArray()->all();
        $info['parameters'] = $parameters;

        $buttons = MenuButton::find()->andWhere(['base_menu_id' => $id, 'is_delete' => 0])->orderBy('id asc')->asArray()->all();
        $info['menuBtn'] = $buttons;
        return ResponseHelper::send(ApiCode::CODE_SUCCESS, '', ['menu' => $info]);
    }

    public function actionSetUserInfo()
    {
        $form = new AdminEditForm();
        $form->attributes = $this->request->post();
        return $this->asJson($form->save());
    }

    public function actionUserInfo()
    {
        $info = Admin::find()->andWhere(['username' => 'admin', 'is_delete' => 0])
            ->select('username,nickname as nickname,avatar as headerImg,base_color as baseColor,active_color as activeColor,authority_id,side_mode as sideMode,mobile as phone,email')->asArray()->one();
        $authority = Authority::findOne(['authority_id' => $info['authority_id'], 'is_delete' => 0]);
        if ($authority) {
            $info['authority'] = $authority->attributes;
            $info['authority']['defaultRouter'] = $authority->default_router;
            $info['authority']['children'] = null;
            $info['authority']['menus'] = null;
        }
        $list = Authority::find()->andWhere(['parent_id' => $info['authority_id'], 'is_delete' => 0])->orderBy('id asc')->asArray()->all();
        $info['authorities'] = $list;
        return ResponseHelper::send(ApiCode::CODE_SUCCESS, '', ['userInfo' => $info]);
    }

    public function actionRegister()
    {

        $form = new AdminEditForm();
        $form->attributes = $this->request->post();
        return $this->asJson($form->save());
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
     * Date：2022/4/4
     * Time：17:16
     * Note：更新自己的信息
     */
    public function actionSelfInfo()
    {
        $form = new AdminEditSelfInfoForm();
        $form->attributes = $this->request->post();
        return $form->save();
    }


    public function actionApiList()
    {
        $form = new ApiListForm();
        $form->attributes = $this->request->get();
        return $this->asJson($form->getList());
    }

    public function actionCreateApi()
    {
        $form = new ApiForm();
        $form->attributes = $this->request->post();
        return $this->asJson($form->save());
    }

    public function actionUpdateApi()
    {
        $form = new ApiForm();
        $form->attributes = $this->request->post();
        return $this->asJson($form->save());
    }

    public function actionApi()
    {
        $form = new ApiForm();
        $form->attributes = $this->request->get();
        return $this->asJson($form->getInfo());
    }

    public function actionDeleteApi()
    {
        $form = new ApiForm();
        $form->attributes = $this->request->post();
        return $this->asJson($form->delete());
    }

    public function actionDeleteApis()
    {
        $form = new ApiForm();
        $form->attributes = $this->request->post();
        return $this->asJson($form->deleteByIds());
    }

    public function actionAllApis()
    {
        $form = new ApiForm();

        return $this->asJson($form->getList());
    }

    public function actionUpdateCasbin()
    {
        $form = new UpdateCasbinForm();
        $form->attributes = $this->request->post();
        return $this->asJson($form->save());
    }

    public function actionAuthorityApiByAuthorityId()
    {
        $form = new AuthorityApiForm();
        $form->attributes = $this->request->get();
        return $form->getList();
    }

    public function actionCanRemoveAuthorityBtn()
    {

        $form = new RemoveAuthorityBtnForm();
        $form->attributes = $this->request->get();
        return $this->asJson($form->search());
    }

}