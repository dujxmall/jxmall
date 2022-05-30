<?php

namespace app\models;

use app\events\UserEvent;
use app\helpers\CacheHelper;
use app\helpers\JobHelper;
use app\helpers\SerializeHelper;
use app\jobs\user\ParentChangeJob;
use app\jobs\user\UserActionJob;
use Yii;
use yii\web\IdentityInterface;

/**
 * This is the model class for table "{{%user}}".
 *
 * @property int $id
 * @property int $mall_id
 * @property string $nickname
 * @property string $avatar_url
 * @property string|null $union_id
 * @property string|null $mobile
 * @property string $access_token
 * @property string $auth_key
 * @property string|null $login_ip
 * @property float $price
 * @property float $total_price
 * @property float $money
 * @property float $total_money
 * @property int $is_delete
 * @property int|null $created_at
 * @property int|null $updated_at
 * @property int|null $deleted_at
 * @property int $is_inviter 是否是邀请者
 * @property int|null $inviter_at 成为邀请者的时间
 * @property int $parent_id
 * @property int $total_integral
 * @property int $integral
 * @property int $level
 * @property int $platform
 * @property string|null $remarks
 * @property int|null $parent_at
 * @property int $maybe_parent_id
 * @property int $level_id
 * @property int $level_at
 * @property int $is_manual
 * @property User $inviter
 * @property int $is_subscribe
 * @property string $birthday
 * @property int $year
 * @property int $month
 * @property int $day
 * @property string $password
 * @property string $openid
 * @property string $current_platform 用户当前所在平台
 */
class User extends BaseActiveRecord implements IdentityInterface
{
    const PLATFORM_WECHAT = 0;
    const PLATFORM_MPWX = 1;
    const PLATFORM_WXAPP = 2;
    const PLATFORM_MOBILE = 3;

    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return '{{%user}}';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['mall_id', 'nickname', 'avatar_url'], 'required'],
            [['mall_id', 'year', 'month', 'day', 'is_subscribe', 'level_at', 'is_delete', 'created_at', 'updated_at', 'deleted_at', 'level_id', 'is_inviter', 'inviter_at', 'parent_id', 'total_integral', 'is_manual', 'integral', 'level', 'platform', 'parent_at', 'maybe_parent_id'], 'integer'],
            [['price', 'total_price', 'money', 'total_money'], 'number'],
            [['nickname', 'login_ip', 'birthday'], 'string', 'max' => 45],
            [['avatar_url', 'remarks'], 'string', 'max' => 255],
            [['union_id', 'access_token', 'auth_key', 'password', 'openid'], 'string', 'max' => 64],
            [['mobile','current_platform'], 'string', 'max' => 15],
            [['access_token'], 'unique'],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'mall_id' => 'Mall ID',
            'nickname' => 'Nickname',
            'avatar_url' => 'Avatar Url',
            'union_id' => 'Union ID',
            'mobile' => 'Mobile',
            'access_token' => 'Access Token',
            'auth_key' => 'Auth Key',
            'login_ip' => 'Login Ip',
            'price' => 'Price',
            'total_price' => 'Total Price',
            'money' => 'Money',
            'total_money' => 'Total Money',
            'is_delete' => 'Is Delete',
            'created_at' => 'Created At',
            'updated_at' => 'Updated At',
            'deleted_at' => 'Deleted At',
            'is_inviter' => '是否是邀请者',
            'inviter_at' => '成为邀请者的时间',
            'parent_id' => '推荐人ID',
            'integral' => '积分',
            'total_integral' => '累计积分',
            'level' => '等级',
            'platform' => '平台 0 微信 1 小程序 2 app',
            'remarks' => '备注',
            'maybe_parent_id' => '可能的上级',
            'parent_at' => '绑定上级的时间',
            'level_id' => '等级ID',
            'level_at' => '等级更新于',
            'is_manual' => 'is_manual',
            'is_subscribe' => '是否关注',
            'birthday' => '生日',
            'year' => '年',
            'month' => '月',
            'day' => '日',
            'openid' => '临时openid，用户登录就会刷新这个openid',
            'current_platform'=>'用户当前所在平台'
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
        // TODO: Implement findIdentityByAccessToken() method.
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
        return $this->auth_key == $authKey;
    }


    public function bindParent($parent_id)
    {
        $this->parent_id = $parent_id;
        $this->parent_at = time();
        if ($this->save()) {
            JobHelper::push(new ParentChangeJob(['user_id' => $this->id, 'parent_id' => $parent_id]));
        }
    }

    public function getInviter()
    {
        return $this->hasOne(User::class, ['id' => 'parent_id']);
    }

    /**
     * @Author: 动力宇宙 ganxiaohao
     * @Date: 2020-10-30
     * @Time: 23:06
     * @Note:上级推荐人
     */
    public function getParent()
    {
        $parent = self::findOne(['is_delete' => 0, 'id' => $this->parent_id]);
        if (!$parent) {
            return null;
        }
        return $parent;
    }

    /**
     * @Author: 动力宇宙 ganxiaohao
     * @Date: 2020-11-02
     * @Time: 11:18
     * @Note:
     * @param null $id
     * @return User
     */
    public static function getBaseInfo($id = null)
    {
        $user = self::find()->where(['is_delete' => 0, 'id' => $id])->select('id,avatar_url,nickname,level,is_inviter,platform,mall_id')->one();
        if (!$user) {
            return null;
        }
        return $user;
    }

    /**
     * @Author: 动力宇宙 ganxiaohao
     * @Date: 2020-11-02
     * @Time: 11:18
     * @Note:
     * @param null $id
     * @return User
     */
    public static function getUser($id = null)
    {
        $user = self::find()->where(['is_delete' => 0, 'id' => $id])->one();
        if (!$user) {
            return null;
        }
        return $user;
    }

    public function afterSave($insert, $changedAttributes)
    {

        if (!$insert) {
            if (isset($changedAttributes['parent_id'])) {
                $event = new UserEvent();
                $event->user_id = $this->id;
                Yii::$app->trigger(UserEvent::PARENT_CHANGE, $event);
            }
        }

        JobHelper::push(new UserActionJob(['user_id' => $this->id, 'mall_id' => $this->mall_id, 'insert' => $insert, 'changedAttributes' => $changedAttributes, 'attributes' => $this->attributes]),10);
        parent::afterSave($insert, $changedAttributes); // TODO: Change the autogenerated stub
    }

    private function saveLog($insert, $changedAttributes)
    {

        $arr = ['created_at', 'updated_at', 'deleted_at'];
        $afterUpdate = $this->attributes;
        $newBeforeUpdate = [];
        $newAfterUpdate = [];
        if ($insert) {
            $remark = '数据插入';
        } else {
            $remark = '数据更新';
        }
        if (isset($afterUpdate['is_delete']) && $afterUpdate['is_delete'] == 1) {
            $remark = '数据删除';
        }
        foreach ($changedAttributes as $key => $item) {
            if (in_array($key, $arr)) {
                unset($changedAttributes[$key]);
                continue;
            }
            if ($item != $afterUpdate[$key]) {
                try {
                    $newBeforeUpdate[$key] = SerializeHelper::decode($item);
                } catch (\Exception $e) {
                    $newBeforeUpdate[$key] = $item;
                }

                try {
                    $newAfterUpdate[$key] = SerializeHelper::decode($afterUpdate[$key]);
                } catch (\Exception $e) {
                    $newAfterUpdate[$key] = $afterUpdate[$key];
                }
            }
        }

        try {
            if (count($newAfterUpdate)) {
                JobHelper::push(new UserActionJob(
                    [
                        'newBeforeUpdate' => $newBeforeUpdate,
                        'newAfterUpdate' => $newAfterUpdate,
                        'remark' => $remark,
                        'user_id' => $this->id,
                        'mall_id' => $this->mall_id
                    ]
                ), 10);
            }
        } catch (\Exception $e) {

        }
    }

}
