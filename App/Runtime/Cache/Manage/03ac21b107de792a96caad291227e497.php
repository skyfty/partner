<?php if (!defined('THINK_PATH')) exit();?><form class="form-horizontal" action="<?php echo U('product/doverify');?>" method="post" id="myform">
    <table class="table">
        <input type='hidden' name="product_id" value="<?php echo ($product['product_id']); ?>"/>
        <input type='hidden' name="role_id" value="<?php echo (session('role_id')); ?>"/>
        <input type='hidden' name="refer_url" value="<?php echo ($refer_url); ?>"/>
        <input type='hidden' name="assort" value="<?php echo ($assort); ?>"/>
        <tr>
            <th colspan="4"><?php echo L('BASIC_INFO');?></th>
        </tr>
        <tr>
            <td></td>
            <td colspan="3">
                <input type="radio" name="state" value="-1" <?php if($state > 0): ?>checked<?php endif; ?>/>未提交 <br/>
                <input type="radio" name="state" value="1" <?php if($state > 0): ?>checked<?php endif; ?>/>审核通过 <br/>
                <input type="radio" name="state" value="0" <?php if($state == -1): ?>checked<?php endif; ?>/>审核不通过

            </td>
        </tr>
        <tr>
            <td class="tdleft"><?php echo L('REMARK');?></td>
            <td colspan="3"><textarea rows="6" class="span4" name="describe" id="describe"><?php echo ($describe); ?></textarea></td>
        </tr>
    </table>
</form>