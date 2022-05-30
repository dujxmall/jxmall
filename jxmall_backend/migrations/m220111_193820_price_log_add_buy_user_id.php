<?php

use yii\db\Schema;
use yii\db\Migration;

class m220111_193820_price_log_add_buy_user_id extends Migration
{

    public function init()
    {
        $this->db = 'db';
        parent::init();
    }


    public function safeUp()
    {
     //   $this->addColumn(\app\models\PriceLog::tableName(), 'buy_user_id', $this->integer(11)->comment('购买者的ID')->notNull()->defaultValue(0));
       // $this->addColumn(\app\models\PriceLog::tableName(), 'order_no', $this->string(45)->comment('订单号')->notNull()->defaultValue(''));

        $oids = \app\models\CommonOrder::find()->andWhere(['is_delete' => 0, 'is_pay' => 1])->select('id')->column();

        $list = \app\models\CommonOrderDetail::find()->andWhere(['common_order_id' => $oids])->all();

        /**
         * @var \app\models\CommonOrderDetail[] $list
         */
        foreach ($list as $item) {
            $this->update(\app\models\PriceLog::tableName(), ['buy_user_id' => $item->user_id,'order_no'=>$item->common_order_no], ['common_order_detail_id' => $item->id]);
        }
    }

    public function safeDown()
    {

    }
}
