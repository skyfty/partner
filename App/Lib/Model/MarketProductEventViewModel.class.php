<?php 
	class MarketProductEventViewModel extends ViewModel{
		public $viewFields = array(
			'event'=>array(
                'event_id',
                'subject',
                'start_date',
                'end_date' ,
                'workstate_id',
                'description',
                'league_id',
                '_type'=>'LEFT'
            ),
            'market_product'=>array(
                'market_product_id',
                '_on'=>'market_product.market_product_id=event.market_product_id',
                '_type'=>'LEFT'
            ),
			'market'=>array(
                'market_id',
                'league_id',
                'idcode'=>"market_idcode",
                '_on'=>'market.market_id=market_product.market_id',
                '_type'=>'LEFT'
            ),
			'product'=>array(
                'product_id',
                'idcode',
                'name'=>'product_name',
                'telephone'=>'product_telephone',
                'wxopenid'=>'product_wxopenid',
                '_on'=>'product.product_id=event.product_id',
                '_type'=>'LEFT'
            )
        );

        public function related_event($event_id, $product_id, $market_product_id = null) {
            $data['event_id'] = $event_id;
            if ($market_product_id) {
                $data['market_product_id'] = $market_product_id;
                if (M('r_market_product_event')->add($data)) {
                    M('event')->where(array("event_id"=>$event_id))->setField("market_product_id", $market_product_id);
                }
            }
            if ($product_id) {
                $data['product_id'] = $product_id;
                if (M('r_product_event')->add($data)) {
                    M('event')->where(array("event_id"=>$event_id))->setField("product_id", $product_id);
                }
            }
        }

        public function delete_product_event($market_product_id) {
            $market_event = M('r_market_product_event')->where(array("market_product_id"=>array("in", $market_product_id)))->find();
            if ($market_event) {
                $this->delete_event($market_event['event_id']);
            }
        }

        public function delete_event($event_ids) {
            M('r_product_event')->where(array("event_id"=>array("in", $event_ids)))->delete();
            M('event')->where(array("event_id"=>array("in", $event_ids)))->delete();
            M('r_market_product_event')->where(array("event_id"=>array("in", $event_ids)))->delete();
        }

        public function reset_event($event_id, $isclose) {
            M('event')->where(array("event_id"=>$event_id))->setField("isclose", $isclose);
        }

        public static function format_date($workstate_id, $start_date, $end_date) {
            $data = array(
                'update_date'=>time(),
            );
            $data['workstate_id'] = $workstate_id;

            if ($start_date != -1) {
                $data['start_date'] = $start_date;
            }
            if ($end_date != -1) {
                $data['end_date'] = $end_date;
            }
            return $data;
        }

        public function change_event($event_id, $product_id, $workstate_id, $start_date, $end_date, $description = null) {
            $data = self::format_date($workstate_id, $start_date, $end_date);
            if ($description != null) {
                $data['description'] = $description;
            }
            $data['product_id'] = $product_id;

            return M('event')->where(array("event_id"=>$event_id))->save($data);
        }

        public function add_event($workstate_id, $start_date, $end_date, $description = "") {
            $data = self::format_date($workstate_id, $start_date, $end_date);
            $data['create_date'] = time();
            $data['description'] = $description;
            return M('event')->add($data);
        }

        public function change_market_event($market_product, $workstate_id, $start_date, $end_date, $description = null) {
            if ($start_date > 0) {
                $start_date = strtotime(date("Y-m-d", $start_date));
            }
            if ($end_date > 0) {
                $end_date = strtotime(date("Y-m-d 23:59:59", $end_date));
            }

            $where = array(
                "market_product_id"=>$market_product['market_product_id']
            );
            $wordevent = M("event")->where($where)->find();
            if ($wordevent) {
                if ($start_date != $wordevent['start_date'] || $end_date != $wordevent['end_date'] || $workstate_id != $wordevent['workstate_id'] || $market_product['product_id'] != $wordevent['product_id']) {
                    $this->change_event($wordevent['event_id'], $market_product['product_id'],  $workstate_id, $start_date, $end_date, $description);
                    if ($market_product['product_id'] != $wordevent['product_id']) {
                        M('r_product_event')->where(array("event_id"=>$wordevent['event_id']))->setField("product_id", $market_product['product_id']);
                    }
                }
            } else {
                $event_id = $this->add_event($workstate_id, $start_date, $end_date, $description);
                if ($event_id) {
                    $this->related_event($event_id, $market_product['product_id'], $market_product['market_product_id']);
                }
            }
            return true;
        }

	}