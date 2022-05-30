<?php

namespace app\models;

use Yii;
use yii\web\IdentityInterface;

/**
 * This is the model class for table "{{%admin}}".
 *
 * @property int $id
 * @property string $name
 * @property int $mall_count 创建商城数量
 * @property int $is_delete
 * @property int|null $created_at
 * @property int|null $updated_at
 * @property int|null $deleted_at
 * @property int $is_expire 账号是否过期
 * @property int|null $end_at
 * @property int $is_has_expire 是否会存在过期
 * @property int $admin_type 0总管理员  1 分账户 2员工
 * @property string|null $password 密码
 * @property string $username 用户名
 * @property string $access_token
 * @property string $login_ip 登陆地IP
 * @property string|null $auth_key auth_key
 * @property string|null $mobile
 * @property int $created_by
 * @property integer $mch_id
 * @property string|null $nickname 昵称
 * @property string|null $avatar 头像
 * @property string|null $email email
 * @property integer $role_id 角色ID
 * @property string $created_time 创建时间
 * @property string $remark 备注
 */
class Admin extends BaseActiveRecord implements IdentityInterface
{
    const ADMIN_TYPE_SUPER = 0;
    const ADMIN_TYPE_FOUNDER = 1;
    const ADMIN_TYPE_OPERATOR = 2;
    const ADMIN_TYPE = [0 => '超级管理员', 1 => '子账户', 2 => '操作员'];

    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return '{{%admin}}';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['username'], 'required'],
            [['mall_count', 'role_id', 'is_expire', 'end_at', 'is_has_expire', 'admin_type', 'created_by', 'we7_uid', 'mch_id', 'is_delete', 'created_at', 'updated_at', 'deleted_at'], 'integer'],
            [['created_time'], 'safe'],
            [['name', 'username', 'login_ip', 'nickname', 'email'], 'string', 'max' => 45],
            [['password', 'auth_key', 'avatar','remark'], 'string', 'max' => 255],
            [['access_token'], 'string', 'max' => 254],
            [['mobile'], 'string', 'max' => 15],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'name' => 'Name',
            'mall_count' => '创建商城数量',
            'is_delete' => 'Is Delete',
            'created_at' => 'Created At',
            'updated_at' => 'Updated At',
            'deleted_at' => 'Deleted At',
            'is_expire' => '账号是否过期',
            'end_at' => 'End At',
            'is_has_expire' => '是否会存在过期',
            'admin_type' => '0总管理员  1 分账户 2员工',
            'password' => '密码',
            'username' => '用户名',
            'access_token' => 'Access Token',
            'login_ip' => '登陆地IP',
            'auth_key' => 'auth_key',
            'mobile' => 'Mobile',
            'created_by' => 'Created By',
            'mch_id' => '多商户ID',
            'nickname' => 'Nick Name',
            'avatar' => 'Header Img',
            'email' => 'Email',
            'remark'=>'remark'
        ];
    }

    /**
     * Finds an identity by the given ID.
     * @param string|int $id the ID to be looked for
     * @return IdentityInterface|null the identity object that matches the given ID.
     * Null should be returned if such an identity cannot be found
     * or the identity is not in an active state (disabled, deleted, etc.)
     */
    public static function findIdentity($id)
    {

        return self::findOne($id);
    }

    /**
     * Finds an identity by the given token.
     * @param mixed $token the token to be looked for
     * @param mixed $type the type of the token. The value of this parameter depends on the implementation.
     * For example, [[\yii\filters\auth\HttpBearerAuth]] will set this parameter to be `yii\filters\auth\HttpBearerAuth`.
     * @return IdentityInterface|null the identity object that matches the given token.
     * Null should be returned if such an identity cannot be found
     * or the identity is not in an active state (disabled, deleted, etc.)
     */
    public static function findIdentityByAccessToken($token, $type = null)
    {

        return self::findOne(['access_token' => $token]);
    }

    /**
     * Returns an ID that can uniquely identify a user identity.
     * @return string|int an ID that uniquely identifies a user identity.
     */
    public function getId()
    {
        // TODO: Implement getId() method.
        return $this->id;
    }

    /**
     * Returns a key that can be used to check the validity of a given identity ID.
     *
     * The key should be unique for each individual user, and should be persistent
     * so that it can be used to check the validity of the user identity.
     *
     * The space of such keys should be big enough to defeat potential identity attacks.
     *
     * This is required if [[User::enableAutoLogin]] is enabled. The returned key will be stored on the
     * client side as a cookie and will be used to authenticate user even if PHP session has been expired.
     *
     * Make sure to invalidate earlier issued authKeys when you implement force user logout, password change and
     * other scenarios, that require forceful access revocation for old sessions.
     *
     * @return string a key that is used to check the validity of a given identity ID.
     * @see validateAuthKey()
     */
    public function getAuthKey()
    {
        // TODO: Implement getAuthKey() method.
        return $this->auth_key;
    }

    /**
     * Validates the given auth key.
     *
     * This is required if [[User::enableAutoLogin]] is enabled.
     * @param string $authKey the given auth key
     * @return bool whether the given auth key is valid.
     * @see getAuthKey()
     */
    public function validateAuthKey($authKey)
    {
        // TODO: Implement validateAuthKey() method.
        return $this->auth_key == $this->auth_key;
    }

}
