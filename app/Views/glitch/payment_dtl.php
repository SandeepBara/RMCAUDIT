<div class="panel-heading">
    <h5 class="panel-title"><?php echo $payment_type; ?> Payment Details</h5>
    <input type="hidden" name="payable_type" value="<?php echo $payment_type; ?>">
</div>
<div class="panel-body">
    <table class="table table-bordered table-secondary">
        <tr>
            <th>Order ID</th>
            <td colspan="3"><?php echo $txn_dtl['order_id']; ?></td>
        </tr>
        <tr>
            <th>Prop_dtl ID</th>
            <td><?php echo $txn_dtl['prop_dtl_id']; ?></td>
            <th>Holding No.</th>
            <td><?php echo $txn_dtl['new_holding_no']; ?></td>
        </tr>
        <tr>
            <th>From</th>
            <td><?php echo $txn_dtl['from_qtr']; ?> / <?php echo $txn_dtl['upto_fy']; ?></td>
            <th>To</th>
            <td><?php echo $txn_dtl['upto_qtr']; ?> / <?php echo $txn_dtl['upto_fy']; ?></td>
        </tr>
        <tr>
            <th>Payable Date</th>
            <td><?php echo date('Y-m-d',strtotime($txn_dtl['created_on'])); ?></td>
            <th>Payable Amt</th>
            <td><input type="number" name="payable_amt" readonly step="0.01" value="<?php echo $txn_dtl['payable_amt']; ?>"></td>
        </tr>
        <tr>
            <th><a href="<?=base_url().'/propDtl/full/'.$txn_dtl['prop_dtl_id'];?>" class="btn btn-success btn-sm" target="_blank">View Property</a> </th>
            <td></td>
            <th colspan="2" class="text-center">
                <?php
                if($payment_status=="PAID")
                {
                    echo "<span class='badge badge-success'>".$payment_status."</span>";
                }else{?>
                    <button class="ladda-button btn-glitch btn-sm" data-color="mint" data-style="expand-right" data-size="xs" data-order_id="<?php echo $txn_dtl['order_id']; ?>" onclick="updatepaymentdetails($(this))">Update Payment</button>
                <?php }
                ?>
            </th>
        </tr>
    </table>
</div>
<?php
