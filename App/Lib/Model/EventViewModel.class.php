<?php 
	class EventViewModel extends ViewModel{
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

        public function related_event($event_id, $product_id) {
            $data['event_id'] = $event_id;
            if ($product_id) {
                $data['product_id'] = $product_id;
                if (M('r_product_event')->add($data)) {
                    M('event')->where(array("event_id"=>$event_id))->setField("product_id", $product_id);
                }
            }
        }

        public function delete_event($event_id) {
            M('r_product_event')->where(array("event_id"=>$event_id))->delete();
            M('event')->where(array("event_id"=>$event_id))->delete();
        }

        public function reset_event($event_id, $isclose) {
            M('event')->where(array("event_id"=>$event_id))->setField("isclose", $isclose);
        }

        public static function format_date($workstate_id, $start_date, $end_date) {
            $data = array(
                'update_date'=>time(),
            );
            if ($workstate_id != "") {
                $data['workstate_id'] = $workstate_id;
            }
            if ($start_date != -1) {
                $data['start_date'] = $start_date;
            }
            if ($end_date != -1) {
                $data['end_date'] = $end_date;
            }
            return $data;
        }

        public function change_event($event_id, $workstate_id, $start_date, $end_date, $description = null) {
            $data = self::format_date($workstate_id, $start_date, $end_date);
            if ($description != null) {
                $data['description'] = $description;
            }
            return M('event')->where(array("event_id"=>$event_id))->save($data);
        }

        public function add_event($workstate_id, $start_date, $end_date, $description = "") {
            $data = self::format_date($workstate_id, $start_date, $end_date);
            $data['create_date'] = time();
            $data['description'] = $description;
            $data['league_id'] = session("league_id");

            return M('event')->add($data);
        }
	}