<?php if (!defined('THINK_PATH')) exit();?><form class="form-horizontal" action="<?php echo U('product/eventdialog');?>" method="post" name="form1" id="form1">
    <input type="hidden" name="product_id" value="<?php echo ($product_id); ?>"/>
    <input type="hidden" name="event_id" value="<?php echo ($event['event_id']); ?>"/>
    <table class="table">
        <tr>
            <td width="40%" class="tdleft">事件</td>
            <td>
                <select id="workstate_id" name="workstate_id">
                    <option value="请假">请假</option>
                    <option value="公司培训">公司培训</option>
                    <option value="司外订单">司外订单</option>
                </select>
            </td>
        </tr>
        <tr>
            <td width="40%" class="tdleft">开始时间</td>
            <td>
                <input name="start_date" id="start_date" class="Wdate" type="text" onFocus="on_start_time_focus();" value="<?php echo (todate($event['start_date'],'Y-m-d')); ?>"/>
            </td>
        </tr>
        <tr>
            <td width="40%" class="tdleft">结束时间</td>
            <td>
                <input name="end_date" id="end_date" class="Wdate" type="text" onFocus="on_end_time_focus();" value="<?php echo (todate($event['end_date'],'Y-m-d')); ?>"/>
            </td>
        </tr>
        <tr>
            <td width="40%" class="tdleft">备注</td>
            <td>
                <textarea rows="6" id="description" name="description"><?php echo ($event['description']); ?></textarea>
            </td>
        </tr>
    </table>
</form>


<script type="text/javascript">

    $(function(){
        $("#workstate_id option[value='<?php echo ($event['workstate_id']); ?>']").prop("selected", true);
    });

    function on_end_time_focus() {
        var mindate = $("#start_date").val();
        WdatePicker({
            dateFmt:'yyyy-MM-dd',
            minDate: mindate,
        });
    }

    function on_start_time_focus() {
        WdatePicker({
            dateFmt:'yyyy-MM-dd',
        });
    }

</script>