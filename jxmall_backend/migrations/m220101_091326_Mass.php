<?php

use yii\db\Schema;
use yii\db\Migration;

class m220101_091326_Mass extends \app\core\Migration
{

    public function init()
    {
        $this->db = 'db';
        parent::init();
    }

    public function safeUp()
    {
        $tableOptions = 'ENGINE=InnoDB CHARSET=utf8mb4';

        $this->createTable('{{%address}}', [
            'id' => $this->primaryKey(11),
            'province_code' => $this->string(15)->null()->defaultValue(''),
            'province_name' => $this->string(45)->null()->defaultValue(''),
            'city_code' => $this->string(15)->null()->defaultValue(''),
            'city_name' => $this->string(45)->null()->defaultValue(''),
            'county_code' => $this->string(15)->null()->defaultValue(''),
            'county_name' => $this->string(45)->null()->defaultValue(''),
            'town_code' => $this->string(15)->null()->defaultValue(''),
            'town_name' => $this->string(45)->null()->defaultValue(''),
        ], $tableOptions);


        $this->createTable('{{%admin}}', [
            'id' => $this->primaryKey(11),
            'name' => $this->string(45)->notNull(),
            'mall_count' => $this->smallInteger(6)->notNull()->defaultValue(0)->comment('创建商城数量'),
            'is_expire' => $this->tinyInteger(1)->notNull()->defaultValue(0)->comment('账号是否过期'),
            'end_at' => $this->integer(11)->null()->defaultValue(0),
            'is_has_expire' => $this->tinyInteger(1)->notNull()->defaultValue(0)->comment('是否会存在过期'),
            'admin_type' => $this->tinyInteger(1)->notNull()->defaultValue(0)->comment('0总管理员  1 分账户 2员工'),
            'password' => $this->string(255)->null()->defaultValue('')->comment('密码'),
            'username' => $this->string(45)->unique()->notNull()->comment('用户名'),
            'access_token' => $this->string(254)->null()->defaultValue(''),
            'login_ip' => $this->string(45)->null()->defaultValue('')->comment('登陆地IP'),
            'auth_key' => $this->string(255)->null()->defaultValue('')->comment('auth_key'),
            'mobile' => $this->string(15)->null()->defaultValue(''),
            'created_by' => $this->integer(11)->notNull()->defaultValue(0),
            'we7_uid' => $this->integer(11)->null()->defaultValue(0),
            'mch_id' => $this->integer(11)->notNull()->defaultValue(0),
        ], $tableOptions);


        $this->createTable('{{%admin_action_log}}', [
            'id' => $this->primaryKey(11),
            'mall_id' => $this->integer(11)->notNull()->defaultValue(0),
            'admin_id' => $this->integer(11)->null()->defaultValue(0),
            'model' => $this->string(128)->null()->defaultValue(''),
            'model_id' => $this->integer(11)->notNull()->defaultValue(0),
            'before_update' => $this->text()->null()->defaultValue(null),
            'after_update' => $this->text()->null()->defaultValue(null),
            'remarks' => $this->string(255)->null()->defaultValue(''),
            'log_at' => $this->datetime()->null()->defaultValue(null)->comment('更新时间'),
           
        ], $tableOptions);


        $this->createTable('{{%admin_mall}}', [
            'id' => $this->primaryKey(11),
            'mall_id' => $this->integer(11)->notNull(),
            'admin_id' => $this->integer(11)->notNull(),
            'role' => $this->string(10)->notNull()->comment('founder     manager   operator'),
           
        ], $tableOptions);


        $this->createTable('{{%attr_group}}', [
            'id' => $this->primaryKey(11),
            'mall_id' => $this->integer(11)->notNull(),
            'goods_id' => $this->integer(11)->notNull(),
            'attr_group_id' => $this->integer(11)->notNull(),
            'attr_group_name' => $this->string(45)->null()->defaultValue(''),
            'attr_list' => $this->string(2048)->null()->defaultValue(''),
           
        ], $tableOptions);


        $this->createTable('{{%balance_log}}', [
            'id' => $this->primaryKey(11),
            'mall_id' => $this->integer(11)->notNull(),
            'user_id' => $this->integer(11)->notNull(),
            'type' => $this->tinyInteger(1)->notNull()->defaultValue(0)->comment('0 支出 1 收入'),
            'content' => $this->string(255)->null()->defaultValue(''),
            'money' => $this->decimal(10, 2)->notNull()->defaultValue('0.00')->comment('使用金额'),
            'current' => $this->decimal(10, 2)->notNull()->defaultValue('0.00')->comment('当前金额'),
           
        ], $tableOptions);


        $this->createTable('{{%bank}}', [
            'id' => $this->primaryKey(11),
            'mall_id' => $this->integer(11)->notNull(),
            'user_id' => $this->integer(11)->notNull()->comment('用户ID'),
            'name' => $this->string(45)->notNull()->comment('用户姓名'),
            'account' => $this->string(45)->notNull()->comment('用户账户'),
            'bank_name' => $this->string(45)->notNull()->defaultValue('')->comment('银行名称'),
            'bank_branch_name' => $this->string(45)->null()->defaultValue('')->comment('支行名称'),
            'bank_code' => $this->string(45)->notNull()->defaultValue('')->comment('银行编号'),
           
        ], $tableOptions);

        $this->createIndex('user_id', '{{%bank}}', ['user_id'], false);

        $this->createTable('{{%cart}}', [
            'id' => $this->primaryKey(11),
            'mall_id' => $this->integer(11)->notNull(),
            'user_id' => $this->integer(11)->notNull(),
            'goods_id' => $this->integer(11)->notNull(),
            'goods_attr_id' => $this->integer(11)->notNull()->defaultValue(0),
            'num' => $this->integer(11)->notNull()->defaultValue(0),
           
        ], $tableOptions);


        $this->createTable('{{%cash}}', [
            'id' => $this->primaryKey(11),
            'mall_id' => $this->integer(11)->notNull(),
            'user_id' => $this->integer(11)->notNull(),
            'cash_price' => $this->decimal(10, 2)->notNull()->defaultValue('0.00'),
            'service_price' => $this->decimal(10, 2)->notNull()->defaultValue('0.00'),
            'price' => $this->decimal(10, 2)->notNull()->defaultValue('0.00'),
            'status' => $this->tinyInteger(1)->notNull()->defaultValue(0)->comment('0 带处理 1通过  2 拒绝'),
            'is_price' => $this->tinyInteger(1)->notNull()->defaultValue(0)->comment('是否打款'),
            'cash_type' => $this->string(10)->null()->defaultValue('')->comment('提现方式  wechat balance bank'),
            'type' => $this->tinyInteger(1)->notNull()->defaultValue(0)->comment('0 佣金体现  1balance'),
            'bank_name' => $this->string(45)->null()->defaultValue('')->comment('开户行'),
            'account' => $this->string(45)->null()->defaultValue('')->comment('账户'),
            'receipt' => $this->string(255)->null()->defaultValue('')->comment('打款凭证'),
            'name' => $this->string(45)->null()->defaultValue('')->comment('开户人姓名'),
            'is_manual' => $this->tinyInteger(0)->notNull()->defaultValue(0),
            'bank_code' => $this->string(20)->null()->defaultValue('')->comment('银行编号'),
           
        ], $tableOptions);

        $this->createIndex('user_id', '{{%cash}}', ['user_id'], false);

        $this->createTable('{{%cat}}', [
            'id' => $this->primaryKey(11),
            'name' => $this->string(45)->notNull(),
            'mall_id' => $this->integer(11)->notNull()->defaultValue(0),
            'cover_pic' => $this->string(255)->null()->defaultValue('')->comment('缩略图'),
            'adv_pic' => $this->string(255)->null()->defaultValue('')->comment('广告图'),
            'is_show' => $this->tinyInteger(1)->notNull()->defaultValue(0),
            'type' => $this->tinyInteger(1)->notNull()->defaultValue(0)->comment('0 顶级分类 1 次级分类'),
            'link' => $this->string(128)->null()->defaultValue(''),
            'sort' => $this->integer(11)->notNull()->defaultValue(0)->comment('排序'),
            'mch_id' => $this->integer(11)->notNull()->defaultValue(0)->comment('商户ID'),
            'parent_id' => $this->integer(11)->null()->defaultValue(0),
           
        ], $tableOptions);


        $this->createTable('{{%charge_log}}', [
            'id' => $this->primaryKey(11),
            'mall_id' => $this->integer(11)->notNull(),
            'admin_id' => $this->integer(11)->notNull(),
            'user_id' => $this->integer(11)->notNull(),
            'type' => $this->tinyInteger(1)->notNull()->defaultValue(0)->comment('0余额 1积分'),
            'num' => $this->decimal(10, 2)->notNull()->defaultValue('0.00'),
            'before_num' => $this->decimal(10, 2)->notNull()->defaultValue('0.00')->comment('充值之前'),
            'after_num' => $this->decimal(10, 2)->notNull()->defaultValue('0.00')->comment('充值之后'),
            'remarks' => $this->string(255)->null()->defaultValue('')->comment('备注'),
           
        ], $tableOptions);


        $this->createTable('{{%common_order}}', [
            'id' => $this->primaryKey(11),
            'mall_id' => $this->integer(11)->notNull(),
            'user_id' => $this->integer(11)->notNull(),
            'order_id' => $this->integer(11)->notNull(),
            'is_pay' => $this->tinyInteger(1)->notNull()->defaultValue(0),
            'pay_time' => $this->integer(11)->null()->defaultValue(0),
            'status' => $this->tinyInteger(1)->notNull()->defaultValue(0)->comment(' 1 订单已完成 2 无效'),
            'order_type' => $this->string(45)->notNull()->defaultValue('mall')->comment('mall 商城订单'),
            'mch_id' => $this->integer(11)->notNull()->defaultValue(0),
            'order_no' => $this->string(45)->notNull()->comment('订单编号'),
            'order_class' => $this->string(128)->null()->defaultValue('')->comment('订单对应的class'),
            'pay_price' => $this->decimal(10, 2)->notNull()->defaultValue('0.00'),
            'total_price' => $this->decimal(10, 2)->notNull()->defaultValue('0.00'),
            'pay_type' => $this->tinyInteger(1)->notNull()->defaultValue(0)->comment('0、微信支付 1余额 2、后台支付'),
            'union_no' => $this->string(45)->null()->defaultValue('')->comment('合并下单号'),
            'platform' => $this->string(10)->null()->defaultValue('')->comment('wechat mpwx'),
            'refund_no' => $this->string(45)->null()->defaultValue('')->comment('退款单号'),
            'income' => $this->decimal(10, 2)->notNull()->defaultValue('0.00')->comment('实际收入'),
           
        ], $tableOptions);

        $this->createIndex('order_no', '{{%common_order}}', ['order_no'], true);
        $this->createIndex('mall_id', '{{%common_order}}', ['mall_id'], false);
        $this->createIndex('user_id', '{{%common_order}}', ['user_id'], false);

        $this->createTable('{{%common_order_detail}}', [
            'id' => $this->primaryKey(11),
            'mall_id' => $this->integer(11)->notNull(),
            'common_order_id' => $this->integer(11)->notNull(),
            'user_id' => $this->integer(11)->notNull(),
            'price' => $this->decimal(10, 2)->notNull()->defaultValue(0)->comment('单价'),
            'goods_id' => $this->integer(11)->notNull(),
            'is_price' => $this->tinyInteger(1)->notNull()->defaultValue(0),
            'price_time' => $this->integer(11)->null()->defaultValue(0),
            'common_order_no' => $this->string(45)->null()->defaultValue('')->comment('公共订单ID'),
            'attr' => $this->string(512)->null()->defaultValue(''),
            'num' => $this->integer(11)->notNull()->defaultValue(0),
            'status' => $this->tinyInteger(1)->notNull()->defaultValue(0)->comment('0 正常 1 有效  2 无效'),
            'coupon_cut_price' => $this->decimal(10, 2)->null()->defaultValue('0.00')->comment('优惠券减掉的金额'),
            'user_coupon_id' => $this->integer(11)->null()->defaultValue(0)->comment('用户优惠券ID'),
            'integral_cut_price' => $this->decimal(10, 2)->null()->defaultValue('0.00')->comment('积分减掉的金额'),
            'use_integral' => $this->integer(11)->null()->defaultValue(0)->comment('使用了多少积分'),
            'pay_price' => $this->decimal(10, 2)->null()->defaultValue('0.00')->comment('实际支付金额'),
            'total_price' => $this->decimal(10, 2)->null()->defaultValue('0.00')->comment('总计价格'),
            'express_log_id' => $this->integer(11)->null()->defaultValue(0)->comment('发货记录'),
            'is_send' => $this->tinyInteger(1)->null()->defaultValue(0)->comment('是否发货'),
        ], $tableOptions);

        $this->createIndex('common_order_id', '{{%common_order_detail}}', ['common_order_id'], false);
        $this->createIndex('goods_id', '{{%common_order_detail}}', ['goods_id'], false);

        $this->createTable('{{%coupon}}', [
            'id' => $this->primaryKey(11),
            'mall_id' => $this->integer(11)->notNull(),
            'name' => $this->string(45)->notNull(),
            'is_limit_total' => $this->tinyInteger(1)->notNull()->defaultValue(0)->comment('总数限制'),
            'discount' => $this->decimal(10, 2)->notNull()->defaultValue('0.00')->comment('折扣多少'),
            'price' => $this->decimal(10, 2)->notNull()->defaultValue('0.00')->comment('金额满'),
            'time_type' => $this->tinyInteger(1)->notNull()->defaultValue(0)->comment('时间类型 0 领取后几天  1日期范围'),
            'total' => $this->integer(11)->notNull(),
            'day' => $this->integer(11)->notNull(),
            'start_at' => $this->integer(11)->null()->defaultValue(0),
            'end_at' => $this->integer(11)->null()->defaultValue(0),
            'is_join' => $this->tinyInteger(1)->notNull()->defaultValue(1)->comment('是否加入领券中心'),
            'user_total' => $this->integer(11)->notNull()->defaultValue(1)->comment('每个用户最多领取'),
            'is_goods_limit' => $this->tinyInteger(1)->notNull()->comment('是否限制商品使用'),
            'type' => $this->integer(11)->notNull()->comment('0满减券  1 折扣'),
            'user_total_limit' => $this->tinyInteger(1)->notNull()->defaultValue(0)->comment('用户领取限制'),
            'mch_id' => $this->integer(11)->notNull()->defaultValue(0),
        ], $tableOptions);


        $this->createTable('{{%diy_page}}', [
            'id' => $this->primaryKey(11),
            'mall_id' => $this->integer(11)->notNull(),
            'is_home' => $this->tinyInteger(1)->notNull()->defaultValue(0),
            'data' => $this->text()->notNull(),
            'is_enable' => $this->tinyInteger(1)->notNull()->defaultValue(0),
            'name' => $this->string(45)->notNull()->comment('页面名称'),
        
        ], $tableOptions);


        $this->createTable('{{%eorder}}', [
            'id' => $this->primaryKey(11),
            'mall_id' => $this->integer(11)->notNull(),
            'name' => $this->string(45)->notNull(),
            'express_key' => $this->string(10)->notNull()->comment('快递公司key'),
            'account' => $this->string(45)->null()->defaultValue(''),
            'password' => $this->string(45)->null()->defaultValue(''),
            'month_code' => $this->string(45)->notNull(),
            'shop_name' => $this->string(45)->notNull()->comment('网点'),
            'tpl_style' => $this->string(45)->notNull()->comment('模板样式'),
            'pay_type' => $this->tinyInteger(1)->notNull()->defaultValue(0)->comment('付款方式'),
            'to_home' => $this->tinyInteger(1)->notNull()->defaultValue(1)->comment('到家揽间'),
            'is_default' => $this->tinyInteger(1)->notNull()->defaultValue(0),
            'express' => $this->string(255)->null()->defaultValue(''),
            'shop_code' => $this->string(45)->notNull(),
            'sender_name' => $this->string(45)->notNull()->comment('发件人名字'),
            'sender_mobile' => $this->string(45)->notNull()->comment('发件手机号'),
            'sender_province' => $this->string(45)->notNull()->comment('发件省份'),
            'sender_city' => $this->string(45)->notNull()->comment('发件人城市'),
            'sender_county' => $this->string(45)->notNull()->comment('发件人区域'),
            'sender_address' => $this->string(45)->notNull()->comment('发件人地址'),
           
        ], $tableOptions);


        $this->createTable('{{%eorder_detail}}', [
            'id' => $this->primaryKey(11),
            'mall_id' => $this->integer(11)->notNull(),
            'print_tpl' => $this->text()->notNull(),
            'express_no' => $this->string(45)->notNull(),
            'eorder_id' => $this->integer(11)->notNull()->defaultValue(0),
        ], $tableOptions);


        $this->createTable('{{%express_log}}', [
            'id' => $this->primaryKey(11),
            'mall_id' => $this->integer(11)->notNull(),
            'express_no' => $this->string(64)->notNull(),
            'express_name' => $this->string(45)->notNull(),
            'express_code' => $this->string(10)->null()->defaultValue(''),
            'name' => $this->string(45)->notNull(),
            'mobile' => $this->string(11)->notNull(),
            'address' => $this->string(128)->notNull(),
            'eorder_detail_id' => $this->integer(11)->notNull()->defaultValue(0)->comment('电子面单详情ID'),
            'order_id' => $this->integer(11)->notNull()->defaultValue(0)->comment('订单ID'),
            'order_detail_id' => $this->string(128)->null()->defaultValue('')->comment('订单详情ID'),
            'order_type' => $this->string(45)->notNull()->defaultValue('mall')->comment('订单类型 默认是商城订单'),
            'num' => $this->integer(11)->notNull()->defaultValue(0)->comment('商品数量'),
            'is_all' => $this->tinyInteger(1)->null()->defaultValue(1)->comment('0 分包 1整包'),
            'order_no' => $this->string(45)->null()->defaultValue(''),
            'eorder_id' => $this->integer(11)->notNull()->defaultValue(0),
   
        ], $tableOptions);


        $this->createTable('{{%express_setting}}', [
            'id' => $this->primaryKey(11),
            'mall_id' => $this->integer(11)->notNull(),
            'kdniao_appcode' => $this->string(64)->null()->defaultValue(''),
            'kdniao_customer' => $this->string(64)->null()->defaultValue(''),
           
        ], $tableOptions);


        $this->createTable('{{%file}}', [
            'id' => $this->primaryKey(11),
            'mall_id' => $this->integer(11)->notNull(),
            'mch_id' => $this->integer(11)->notNull()->defaultValue(0),
            'url' => $this->string(512)->null()->defaultValue(''),
            'thumb_url' => $this->string(512)->null()->defaultValue(''),
            'type' => $this->string(15)->notNull()->defaultValue('image'),
            'group_id' => $this->integer(11)->notNull()->defaultValue(0),
            'name' => $this->string(256)->null()->defaultValue('')->comment('文件名'),
            'user_id' => $this->integer(11)->notNull()->defaultValue(0)->comment('用户上传的'),
            'admin_id' => $this->integer(11)->notNull()->defaultValue(0)->comment('管理员上传的'),
            'is_site' => $this->tinyInteger(1)->null()->defaultValue(0),
            'path' => $this->string(256)->null()->defaultValue(''),
            'location' => $this->string(10)->notNull()->defaultValue('local'),
      
        ], $tableOptions);


        $this->createTable('{{%file_group}}', [
            'id' => $this->primaryKey(11),
            'mall_id' => $this->integer(11)->notNull(),
            'mch_id' => $this->integer(11)->notNull()->defaultValue(0),
            'name' => $this->string(45)->null()->defaultValue(''),
            'admin_id' => $this->integer(11)->notNull()->defaultValue(0),
            'is_site'=>$this->tinyInteger(1)->notNull()->defaultValue(0),
        ], $tableOptions);


        $this->createTable('{{%freight_price}}', [
            'id' => $this->primaryKey(11),
            'mall_id' => $this->integer(11)->notNull(),
            'tpl_id' => $this->integer(11)->notNull()->comment('模板ID'),
            'first_num' => $this->integer(11)->notNull()->defaultValue(0)->comment('数量可能是件数  可能是重量'),
            'first_price' => $this->decimal(10, 2)->notNull()->defaultValue('0.00'),
            'other_num' => $this->integer(11)->notNull()->defaultValue(0)->comment('续件数量可能是件数  可能是重量'),
            'other_price' => $this->decimal(10, 2)->notNull()->defaultValue('0.00'),
            'send_area' => $this->text()->null()->defaultValue(null),
            'is_union' => $this->tinyInteger(1)->notNull()->defaultValue(0)->comment('统一'),
            'send_codes' => $this->text()->null()->defaultValue(null),
        ], $tableOptions);


        $this->createTable('{{%freight_tpl}}', [
            'id' => $this->primaryKey(11),
            'mall_id' => $this->integer(11)->notNull(),
            'enabled' => $this->tinyInteger(1)->notNull()->defaultValue(0),
            'name' => $this->string(45)->notNull(),
            'is_default' => $this->tinyInteger(1)->notNull()->defaultValue(0)->comment('默认模板'),
            'price_type' => $this->tinyInteger(1)->notNull()->defaultValue(0)->comment('0 按件计费   1 按重量计费'),
          
        ], $tableOptions);


        $this->createTable('{{%goods}}', [
            'id' => $this->primaryKey(11),
            'name' => $this->string(255)->notNull()->comment('商品名'),
            'price' => $this->decimal(10, 2)->notNull()->defaultValue('0.00')->comment('商品价格'),
            'origin_price' => $this->decimal(10, 2)->notNull()->defaultValue('0.00')->comment('商品原价'),
            'no' => $this->string(255)->null()->defaultValue('')->comment('商品编号'),
            'status' => $this->tinyInteger(1)->notNull()->defaultValue(0)->comment('状态  1上架 0下'),
            'cover_pic' => $this->string(255)->notNull()->comment('缩略图'),
            'unit' => $this->string(10)->notNull()->defaultValue('件')->comment('单位'),
            'is_limit' => $this->tinyInteger(1)->notNull()->defaultValue(0)->comment('是否开启会员限购'),
            'weight' => $this->decimal(10, 2)->notNull()->defaultValue('0.00')->comment('重量'),
            'cost_price' => $this->decimal(10, 2)->notNull()->defaultValue('0.00')->comment('原价'),
            'mall_id' => $this->integer(11)->notNull(),
            'is_attr' => $this->tinyInteger(1)->notNull()->defaultValue(0)->comment('是否使用多规格'),
            'mch_id' => $this->integer(11)->notNull()->defaultValue(0)->comment('商户ID'),
            'stock' => $this->integer(11)->null()->defaultValue(0)->comment('库存'),
            'is_area_limit' => $this->tinyInteger(1)->notNull()->defaultValue(0)->comment('开启区域限购'),
            'sales' => $this->integer(11)->notNull()->defaultValue(0)->comment('销量'),
            'virtual_sales' => $this->integer(11)->notNull()->defaultValue(0)->comment('虚拟销量'),
            'share_title' => $this->string(45)->null()->defaultValue('')->comment('分享标题'),
            'sort' => $this->integer(11)->notNull()->defaultValue(0),
            'is_recommend' => $this->tinyInteger(1)->notNull()->defaultValue(0)->comment('是否推荐'),
            'express_type' => $this->tinyInteger(1)->notNull()->defaultValue(0)->comment('0 包邮  1 统一运费  2 运费模板'),
            'freight_price' => $this->decimal(10, 2)->notNull()->defaultValue('0.00')->comment('运费'),
            'freight_id' => $this->integer(11)->notNull()->defaultValue(0)->comment('运费模板ID'),
            'is_integral' => $this->tinyInteger(1)->notNull()->defaultValue(0)->comment('是否开启积分抵扣'),
            'use_integral' => $this->integer(11)->notNull()->defaultValue(0)->comment('使用积分'),
            'integral_price' => $this->decimal(10, 2)->notNull()->defaultValue('0.00')->comment('抵扣金额'),
            'use_integral_type' => $this->tinyInteger(1)->notNull()->defaultValue(0)->comment('0 固定积分   1 按比例'),
            'is_negotiable' => $this->tinyInteger(1)->notNull()->defaultValue(0)->comment('是否开启面议'),
            'subtitle' => $this->string(255)->null()->defaultValue('')->comment('商品小标题'),
           
        ], $tableOptions);


        $this->createTable('{{%goods_attr}}', [
            'id' => $this->primaryKey(11),
            'mall_id' => $this->integer(11)->notNull(),
            'goods_id' => $this->integer(11)->notNull(),
            'stock' => $this->integer(11)->notNull()->defaultValue(0),
            'price' => $this->decimal(10, 2)->notNull()->defaultValue('0.00'),
            'pic_url' => $this->string(255)->null()->defaultValue(''),
            'no' => $this->string(45)->null()->defaultValue(''),
            'weight' => $this->integer(11)->null()->defaultValue(0),
            'attr_list' => $this->string(2046)->null()->defaultValue(''),

        ], $tableOptions);


        $this->createTable('{{%goods_cat}}', [
            'id' => $this->primaryKey(11),
            'mall_id' => $this->integer(11)->notNull(),
            'goods_id' => $this->integer(11)->notNull()->defaultValue(0),
            'cat_id' => $this->integer(11)->notNull()->defaultValue(0),
            'level' => $this->tinyInteger(1)->notNull()->defaultValue(0)->comment('分类层级'),
           
        ], $tableOptions);


        $this->createTable('{{%goods_comment}}', [
            'id' => $this->primaryKey(11),
            'mall_id' => $this->integer(11)->notNull(),
            'user_id' => $this->integer(11)->notNull(),
            'order_id' => $this->integer(11)->notNull(),
            'goods_id' => $this->integer(11)->notNull(),
            'content' => $this->string(255)->null()->defaultValue(''),
            'pic_list' => $this->string(2048)->null()->defaultValue(''),
            'grade_level' => $this->tinyInteger(1)->notNull()->defaultValue(5),
           
        ], $tableOptions);


        $this->createTable('{{%goods_coupon}}', [
            'id' => $this->primaryKey(11),
            'mall_id' => $this->integer(11)->notNull(),
            'goods_id' => $this->integer(11)->notNull(),
            'coupon_id' => $this->integer(11)->notNull(),
           
        ], $tableOptions);


        $this->createTable('{{%goods_favorite}}', [
            'id' => $this->primaryKey(11),
            'mall_id' => $this->integer(11)->notNull(),
            'user_id' => $this->integer(11)->notNull(),
            'goods_id' => $this->integer(11)->notNull(),
            
        ], $tableOptions);


        $this->createTable('{{%goods_footmark}}', [
            'id' => $this->primaryKey(11),
            'mall_id' => $this->integer(11)->notNull(),
            'user_id' => $this->integer(11)->notNull(),
            'goods_id' => $this->integer(11)->notNull(),
            
        ], $tableOptions);


        $this->createTable('{{%goods_info}}', [
            'id' => $this->primaryKey(11),
            'mall_id' => $this->integer(11)->notNull(),
            'goods_id' => $this->integer(11)->notNull()->comment('对应的商品ID'),
            'attr_list' => $this->text()->null()->defaultValue(null)->comment('规格'),
            'detail' => $this->text()->null()->defaultValue(null)->comment('商品详情'),
            'limit_area_list' => $this->text()->null()->defaultValue(null)->comment('限制购买的区域'),
            'pic_list' => $this->text()->null()->defaultValue(null),
            'remarks' => $this->string(126)->null()->defaultValue('')->comment('备注'),
            'permission' => $this->text()->null()->defaultValue(null),
           
        ], $tableOptions);


        $this->createTable('{{%goods_pic}}', [
            'id' => $this->primaryKey(11),
            'mall_id' => $this->integer(11)->notNull(),
            'goods_id' => $this->integer(11)->notNull(),
            'pic_url' => $this->string(255)->notNull(),
         
        ], $tableOptions);


        $this->createTable('{{%integral_log}}', [
            'id' => $this->primaryKey(11),
            'mall_id' => $this->integer(11)->notNull(),
            'user_id' => $this->integer(11)->notNull(),
            'integral' => $this->integer(11)->notNull()->defaultValue(0),
            'content' => $this->string(255)->null()->defaultValue(''),
            'type' => $this->tinyInteger(1)->notNull()->defaultValue(0)->comment('0 支出 1 收益'),
            'current' => $this->integer(11)->notNull()->defaultValue(0),
            
        ], $tableOptions);


        $this->createTable('{{%level}}', [
            'id' => $this->primaryKey(11),
            'mall_id' => $this->integer(11)->notNull(),
            'name' => $this->string(45)->notNull(),
            'level' => $this->tinyInteger(3)->notNull(),
            'pic_url' => $this->string(255)->null()->defaultValue(''),
            'detail' => $this->string(256)->null()->defaultValue('')->comment('说明‘'),
            'enabled' => $this->tinyInteger(1)->notNull()->defaultValue(0),
            'is_auto' => $this->tinyInteger(1)->notNull()->defaultValue(0)->comment('是否自动升级'),
            'is_discount' => $this->tinyInteger(1)->notNull()->defaultValue(0),
            'discount' => $this->decimal(10, 2)->notNull(),
            
        ], $tableOptions);


        $this->createTable('{{%mall}}', [
            'id' => $this->primaryKey(11),
            'admin_id' => $this->smallInteger(6)->notNull()->comment('创建者管理员的ID'),
            'name' => $this->string(45)->notNull()->comment('商城名称'),
            'is_has_expire' => $this->tinyInteger(1)->notNull()->defaultValue(0)->comment('是否存在过期'),
            'end_at' => $this->integer(11)->null()->defaultValue(0)->comment('到期时间'),
            'is_expire' => $this->tinyInteger(1)->notNull()->defaultValue(0)->comment('是否过期'),
            'is_stop' => $this->tinyInteger(1)->notNull()->defaultValue(0)->comment('是否停用'),
            'logo' => $this->string(255)->null()->defaultValue('')->comment('logo'),
            'detail' => $this->string(255)->null()->defaultValue(''),
            'uniacid' => $this->integer(11)->null()->defaultValue(0),
            
        ], $tableOptions);


        $this->createTable('{{%mch_favorite}}', [
            'id' => $this->primaryKey(11),
            'mall_id' => $this->integer(11)->notNull(),
            'user_id' => $this->integer(11)->notNull(),
            'mch_id' => $this->integer(11)->notNull(),
            
        ], $tableOptions);


        $this->createTable('{{%option}}', [
            'id' => $this->primaryKey(11),
            'mall_id' => $this->integer(11)->notNull(),
            'name' => $this->string(45)->notNull(),
            'value' => $this->string(10240)->null()->defaultValue('')->comment('值'),
            
        ], $tableOptions);


        $this->createTable('{{%order}}', [
            'id' => $this->primaryKey(11),
            'mall_id' => $this->integer(11)->notNull(),
            'user_id' => $this->integer(11)->notNull(),
            'total_price' => $this->decimal(10, 2)->notNull()->defaultValue('0.00'),
            'express_price' => $this->decimal(10, 2)->notNull()->defaultValue('0.00'),
            'status' => $this->tinyInteger(1)->notNull()->defaultValue(0)->comment(' //0 待付款         //1 代发货         //2 待收货         //3 确认收货         //4 已完成         //5 取消         //6 申请售后'),
            'is_finish' => $this->tinyInteger(1)->notNull()->defaultValue(0),
            'finish_at' => $this->integer(11)->null()->defaultValue(0),
            'is_send' => $this->tinyInteger(1)->notNull()->defaultValue(0),
            'send_at' => $this->integer(11)->null()->defaultValue(0),
            'is_confirm' => $this->tinyInteger(1)->null()->defaultValue(0),
            'confirm_at' => $this->integer(11)->null()->defaultValue(0),
            'apply_cancel' => $this->tinyInteger(1)->notNull()->defaultValue(0),
            'apply_cancel_at' => $this->integer(11)->null()->defaultValue(0),
            'cancel_at' => $this->integer(11)->null()->defaultValue(0),
            'is_cancel' => $this->tinyInteger(1)->notNull()->defaultValue(0),
            'apply_refund' => $this->tinyInteger(1)->notNull()->defaultValue(0),
            'apply_refund_at' => $this->integer(11)->null()->defaultValue(0),
            'is_refund' => $this->tinyInteger(1)->notNull()->defaultValue(0)->comment('是否已经退款'),
            'refund_type' => $this->tinyInteger(1)->notNull()->defaultValue(0)->comment('0退款退货，1换货'),
            'refund_price' => $this->decimal(10, 2)->notNull()->defaultValue('0.00')->comment('退款金额'),
            'agree_refund' => $this->tinyInteger(1)->notNull()->defaultValue(0)->comment('统一售后申请'),
            'refund_operation_at' => $this->integer(11)->null()->defaultValue(0)->comment('操作时间'),
            'refund_at' => $this->integer(11)->null()->defaultValue(0)->comment('退款时间'),
            'mch_id' => $this->integer(11)->notNull()->defaultValue(0)->comment('商户ID'),
            'address' => $this->string(255)->null()->defaultValue(''),
            'province_code' => $this->string(20)->null()->defaultValue('')->comment('省ID'),
            'city_code' => $this->string(20)->null()->defaultValue(''),
            'county_code' => $this->string(20)->null()->defaultValue(''),
            'town_code' => $this->string(20)->null()->defaultValue(''),
            'pay_price' => $this->decimal(10, 2)->notNull()->defaultValue('0.00'),
            'order_no' => $this->string(45)->null()->defaultValue(''),
            'is_pay' => $this->tinyInteger(1)->notNull()->defaultValue(0)->comment('是否支付'),
            'pay_time' => $this->integer(11)->null()->defaultValue(0)->comment('支付时间'),
            'pay_type' => $this->tinyInteger(1)->notNull()->defaultValue(0)->comment('0 wechat 1 balance'),
            'name' => $this->string(45)->null()->defaultValue('')->comment('收货人'),
            'mobile' => $this->string(11)->null()->defaultValue('')->comment('收货人电话'),
            'remarks' => $this->string(255)->null()->defaultValue('')->comment('买家备注'),
            'send_type' => $this->tinyInteger(1)->notNull()->defaultValue(0)->comment('0 整单发货  1 分包发货'),
            'express_log_id' => $this->integer(11)->notNull()->defaultValue(0)->comment('快递记录'),
            'coupon_cut_price' => $this->decimal(10, 2)->notNull()->defaultValue('0.00'),
            'use_coupon_goods_id' => $this->integer(11)->notNull()->defaultValue(0),
            'user_coupon_id' => $this->integer(11)->notNull()->defaultValue(0),
            'is_use_integral' => $this->tinyInteger(1)->notNull()->defaultValue(0),
            'use_integral' => $this->integer(11)->notNull()->defaultValue(0),
            'integral_cut_price' => $this->decimal(10, 2)->notNull()->defaultValue('0.00'),
            
        ], $tableOptions);

        $this->createIndex('mall_id', '{{%order}}', ['mall_id'], false);
        $this->createIndex('user_id', '{{%order}}', ['user_id'], false);

        $this->createTable('{{%order_refund}}', [
            'id' => $this->primaryKey(11),
            'mall_id' => $this->integer(11)->notNull(),
            'user_id' => $this->integer(11)->notNull(),
            'order_id' => $this->integer(11)->notNull(),
            'order_detail_id' => $this->integer(11)->notNull(),
            'goods_id' => $this->integer(11)->notNull(),
            'pic_list' => $this->string(2048)->null()->defaultValue(''),
            'content' => $this->string(255)->null()->defaultValue(''),
            'type' => $this->tinyInteger(1)->notNull()->defaultValue(0)->comment('退款退货 1 换货'),
            'status' => $this->tinyInteger(1)->notNull()->defaultValue(0)->comment('0 未处理'),
            'refund_price' => $this->decimal(10, 2)->notNull()->defaultValue('0.00'),
            'order_no' => $this->string(45)->null()->defaultValue(''),
            'is_refund' => $this->tinyInteger(1)->notNull()->defaultValue(0)->comment('是否退款'),
            
        ], $tableOptions);


        $this->createTable('{{%plugin}}', [
            'id' => $this->primaryKey(11),
            'name' => $this->string(45)->notNull(),
            'label' => $this->string(45)->notNull(),
            'version' => $this->string(10)->notNull(),
            'type' => $this->tinyInteger(1)->notNull()->defaultValue(0)->comment('0 分润类 1营销类 2工具'),
            'install_type' => $this->tinyInteger(1)->notNull()->defaultValue(0)->comment('0本地 1在线'),
            'logo' => $this->string(255)->null()->defaultValue(''),
            
        ], $tableOptions);
        $this->createTable('{{%price_log}}', [
            'id' => $this->primaryKey(11),
            'mall_id' => $this->integer(11)->notNull(),
            'user_id' => $this->integer(11)->notNull(),
            'price' => $this->decimal(10, 2)->notNull()->defaultValue('0.00'),
            'remarks' => $this->string(128)->null()->defaultValue(''),
            'common_order_detail_id' => $this->integer(11)->notNull()->defaultValue(0),
            
        ], $tableOptions);

        $this->execute("CREATE TABLE  {{%queue}} (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `channel` varchar(255) NOT NULL,
  `job` blob NOT NULL,
  `pushed_at` int(11) NOT NULL,
  `ttr` int(11) NOT NULL,
  `delay` int(11) NOT NULL DEFAULT 0,
  `priority` int(11) unsigned NOT NULL DEFAULT 1024,
  `reserved_at` int(11) DEFAULT NULL,
  `attempt` int(11) DEFAULT NULL,
  `done_at` int(11) DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `channel` (`channel`),
  KEY `reserved_at` (`reserved_at`),
  KEY `priority` (`priority`)
) ENGINE=InnoDB");

        $this->createTable('{{%session}}', [
            'id' => $this->char(40)->notNull(),
            'expire' => $this->integer(11)->null()->defaultValue(0),
            'DATA' => $this->binary()->null()->defaultValue(null),
        ], $tableOptions);


        $this->createTable('{{%tabbar}}', [
            'id' => $this->primaryKey(11),
            'mall_id' => $this->integer(11)->notNull(),
            'setting' => $this->text()->notNull(),
            'color' => $this->string(15)->null()->defaultValue(''),
            
        ], $tableOptions);


        $this->createTable('{{%union_order}}', [
            'id' => $this->primaryKey(11),
            'mall_id' => $this->integer(11)->notNull(),
            'pay_price' => $this->decimal(10, 2)->notNull()->defaultValue('0.00'),
            'user_id' => $this->integer(11)->notNull(),
            'union_no' => $this->string(45)->null()->defaultValue(''),
            'is_pay' => $this->tinyInteger(1)->notNull()->defaultValue(0),
            'pay_type' => $this->tinyInteger(1)->notNull()->defaultValue(0)->comment('0 微信  1 余额'),
            'is_cancel' => $this->tinyInteger(1)->notNull()->defaultValue(0)->comment('是否取消'),
            
        ], $tableOptions);


        $this->createTable('{{%user}}', [
            'id' => $this->primaryKey(11),
            'mall_id' => $this->integer(11)->notNull(),
            'nickname' => $this->string(45)->notNull(),
            'avatar_url' => $this->string(255)->notNull(),
            'union_id' => $this->string(64)->null()->defaultValue(''),
            'mobile' => $this->string(15)->null()->defaultValue(''),
            'access_token' => $this->string(64)->null()->defaultValue(''),
            'auth_key' => $this->string(64)->null()->defaultValue(''),
            'login_ip' => $this->string(45)->null()->defaultValue(''),
            'price' => $this->decimal(10, 2)->notNull()->defaultValue('0.00'),
            'total_price' => $this->decimal(10, 2)->notNull()->defaultValue('0.00'),
            'money' => $this->decimal(10, 2)->notNull()->defaultValue('0.00'),
            'total_money' => $this->decimal(10, 2)->notNull()->defaultValue('0.00'),
            'is_inviter' => $this->tinyInteger(1)->notNull()->defaultValue(0)->comment('是否是邀请者'),
            'inviter_at' => $this->integer(11)->null()->defaultValue(0)->comment('成为邀请者的时间'),
            'integral' => $this->integer(11)->notNull()->defaultValue(0),
            'total_integral' => $this->integer(11)->notNull()->defaultValue(0),
            'platform' => $this->tinyInteger(1)->notNull()->defaultValue(0)->comment('0 微信 1 小程序 2app 3 mobile'),
            'remarks' => $this->string(255)->null()->defaultValue('')->comment('备注'),
            'parent_id' => $this->integer(11)->notNull()->defaultValue(0)->comment('推荐人'),
            'parent_at' => $this->integer(11)->null()->defaultValue(0)->comment('绑定时间'),
            'maybe_parent_id' => $this->integer(11)->notNull()->defaultValue(0)->comment('可能的上级'),
            'level_id' => $this->integer(11)->notNull()->defaultValue(0),
            'level_at' => $this->integer(11)->null()->defaultValue(0),
            'level' => $this->integer(11)->notNull()->defaultValue(0)->comment('会员等级,0普通用户'),
            'is_manual' => $this->tinyInteger(1)->notNull()->defaultValue(0),
            'is_subscribe' => $this->tinyInteger(1)->notNull()->defaultValue(0)->comment('是否关注'),
            
        ], $tableOptions);

        $this->createIndex('access_token', '{{%user}}', ['access_token'], true);

        $this->createTable('{{%user_action_log}}', [
            'id' => $this->primaryKey(11),
            'mall_id' => $this->integer(11)->notNull()->defaultValue(0),
            'user_id' => $this->integer(11)->null()->defaultValue(0),
            'before_update' => $this->text()->null()->defaultValue(null),
            'after_update' => $this->text()->null()->defaultValue(null),
            'remarks' => $this->string(255)->null()->defaultValue(''),
            'log_at' => $this->datetime()->null()->defaultValue(null)->comment('更新时间'),
            
        ], $tableOptions);


        $this->createTable('{{%user_address}}', [
            'id' => $this->primaryKey(11),
            'mall_id' => $this->integer(11)->notNull(),
            'user_id' => $this->integer(11)->notNull(),
            'address' => $this->string(255)->notNull(),
            'province_code' => $this->string(20)->null()->defaultValue(''),
            'city_code' => $this->string(20)->null()->defaultValue(''),
            'county_code' => $this->string(20)->null()->defaultValue(''),
            'town_code' => $this->string(20)->null()->defaultValue(''),
            'is_default' => $this->tinyInteger(1)->notNull()->defaultValue(0),
            'province_name' => $this->string(45)->null()->defaultValue(''),
            'city_name' => $this->string(45)->null()->defaultValue(''),
            'county_name' => $this->string(45)->null()->defaultValue(''),
            'town_name' => $this->string(45)->null()->defaultValue(''),
            'mobile' => $this->string(11)->null()->defaultValue('')->comment('手机号码'),
            'name' => $this->string(45)->null()->defaultValue('')->comment('收货人姓名'),
            'area' => $this->string(128)->null()->defaultValue('')->comment('所属区域'),
            'detail' => $this->string(128)->null()->defaultValue('')->comment('详细地质'),

        ], $tableOptions);


        $this->createTable('{{%user_coupon}}', [
            'id' => $this->primaryKey(11),
            'mall_id' => $this->integer(11)->notNull(),
            'user_id' => $this->integer(11)->notNull(),
            'price' => $this->decimal(10, 2)->null()->defaultValue(0),
            'discount' => $this->decimal(10, 2)->null()->defaultValue(0),
            'type' => $this->tinyInteger(1)->notNull()->defaultValue(0)->comment('0 满减  1折扣'),
            'is_goods_limit' => $this->tinyInteger(1)->notNull()->defaultValue(0),
            'coupon_id' => $this->integer(11)->notNull(),
            'start_at' => $this->integer(11)->null()->defaultValue(0),
            'end_at' => $this->integer(11)->null()->defaultValue(0),
            'status' => $this->tinyInteger(1)->notNull()->defaultValue(0)->comment('1使用  2 过期'),
            'mch_id' => $this->integer(11)->notNull()->defaultValue(0),

        ], $tableOptions);


        $this->createTable('{{%user_info}}', [
            'id' => $this->primaryKey(11),
            'mall_id' => $this->integer(11)->notNull(),
            'user_id' => $this->integer(11)->notNull(),
            'openid' => $this->string(64)->null()->defaultValue('')->comment('openid'),
            'platform' => $this->tinyInteger(1)->notNull()->defaultValue(0)->comment('0 wechat 1 mpwx  2 app 3 mobile'),
            'union_id' => $this->string(64)->null()->defaultValue('')->comment('union_id'),

        ], $tableOptions);

        $this->createIndex('openid', '{{%user_info}}', ['openid'], false);

        $this->createTable('{{%user_parent}}', [
            'id' => $this->primaryKey(11),
            'mall_id' => $this->integer(11)->notNull(),
            'user_id' => $this->integer(11)->notNull(),
            'parent_id' => $this->integer(11)->notNull(),
            'level' => $this->integer(11)->notNull(),
        ], $tableOptions);

        $this->createIndex('parent_id', '{{%user_parent}}', ['parent_id'], false);
        $this->createIndex('user_id', '{{%user_parent}}', ['user_id'], false);

    }

    public function safeDown()
    {

        $this->dropTable('{{%address}}');
        $this->dropTable('{{%admin}}');
        $this->dropTable('{{%admin_action_log}}');
        $this->dropTable('{{%admin_mall}}');
        $this->dropTable('{{%attr_group}}');
        $this->dropTable('{{%balance_log}}');
        $this->dropTable('{{%bank}}');
        $this->dropTable('{{%cart}}');
        $this->dropTable('{{%cash}}');
        $this->dropTable('{{%cat}}');
        $this->dropTable('{{%charge_log}}');
        $this->dropTable('{{%common_order}}');
        $this->dropTable('{{%common_order_detail}}');
        $this->dropTable('{{%coupon}}');
        $this->dropTable('{{%diy_page}}');
        $this->dropTable('{{%eorder}}');
        $this->dropTable('{{%eorder_detail}}');
        $this->dropTable('{{%express_log}}');
        $this->dropTable('{{%express_setting}}');
        $this->dropTable('{{%file}}');
        $this->dropTable('{{%file_group}}');
        $this->dropTable('{{%freight_price}}');
        $this->dropTable('{{%freight_tpl}}');
        $this->dropTable('{{%goods}}');
        $this->dropTable('{{%goods_attr}}');
        $this->dropTable('{{%goods_cat}}');
        $this->dropTable('{{%goods_comment}}');
        $this->dropTable('{{%goods_coupon}}');
        $this->dropTable('{{%goods_favorite}}');
        $this->dropTable('{{%goods_footmark}}');
        $this->dropTable('{{%goods_info}}');
        $this->dropTable('{{%goods_pic}}');
        $this->dropTable('{{%integral_log}}');
        $this->dropTable('{{%level}}');
        $this->dropTable('{{%mall}}');
        $this->dropTable('{{%mch_favorite}}');
        $this->dropTable('{{%option}}');
        $this->dropTable('{{%order}}');
        $this->dropTable('{{%order_refund}}');
        $this->dropTable('{{%plugin}}');
        $this->dropTable('{{%price_log}}');
        $this->dropTable('{{%queue}}');
        $this->dropTable('{{%session}}');
        $this->dropTable('{{%tabbar}}');
        $this->dropTable('{{%union_order}}');
        $this->dropTable('{{%user}}');
        $this->dropTable('{{%user_action_log}}');
        $this->dropTable('{{%user_address}}');
        $this->dropTable('{{%user_coupon}}');
        $this->dropTable('{{%user_info}}');
        $this->dropTable('{{%user_parent}}');
    }
}
