<?php
class ProductEventViewModel extends ViewModel{
    public $viewFields = array(
        'r_product_event'=>array(
            '_type'=>'LEFT'
        ),
        'event'=>array(
            'event_id',
            'subject',
            'start_date',
            'end_date' ,
            'isclose' ,
            'workstate_id',
            'description',
            'league_id',
            '_on'=>'r_product_event.event_id=event.event_id',
            '_type'=>'LEFT'
        ),
        'product'=>array(
            'product_id',
            'idcode',
            'name'=>'product_name',
            'telephone'=>'product_telephone',
            'wxopenid'=>'product_wxopenid',
            '_on'=>'r_product_event.product_id=product.product_id',
            '_type'=>'LEFT'
        ),
        'product_category'=>array(
            'name'=>'category_name',
            '_on'=>'product.category_id=product_category.category_id',
            '_type'=>'LEFT'
        )
    );

    public function get_events($product_ids, $range_start, $range_end, $timezone) {
        if (!is_array($product_ids)) {
            $product_ids = array($product_ids);
        }
        $product_events = array();
        foreach($product_ids as $product_id) {
            //有开始时间和结束时间
            $where = array(
                'end_date'=>array("neq", 0),
                'start_date'=>array("neq", 0),
                "product.product_id"=>$product_id
            );
            $event_list = $this->where($where)->select();

            //没有结束时间
            $today_event = M('event')->where(array('product_id'=>$product_id,'march'=>1))->find();
            if ($today_event) {
                $today_event['end_date'] = time();
                $event_list[] = $today_event;
            }

            $coming_carry_event = M('event')->where(array('product_id'=>$product_id,'march'=>0, 'end_date'=>0))->select();
            foreach($coming_carry_event as $cv) {
                $cv['end_date'] = $cv['start_date'];
                $event_list[] = $cv;
            }
            import("@.ORG.Event");

            $output_arrays = array();
            foreach ($event_list as $e) {
                $e['start_date'] = date("Y-m-d",$e['start_date']);
                $e['end_date'] = date("Y-m-d",$e['end_date']);

                $event = new Event($e, $timezone);
                if ($event->isWithinDayRange($range_start, $range_end)) {
                    $output_arrays[] = $event->toArray();
                }
            }
            $product_events[$product_id] = $output_arrays;
        }

        return $product_events;
    }

}