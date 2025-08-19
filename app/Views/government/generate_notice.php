<?= $this->include('layout_vertical/header'); ?>
<style>
#data_table_view, #data_table_view th, #data_table_view td {
        border: 1px solid black !important;
        border-collapse: collapse !important;
}
#data_table_view td{
    padding : 10px;
}
#data_table_view_first_row td{
    font-weight: 600;
}
</style>
<!--CONTENT CONTAINER-->
<div id="content-container">
    <!--Page content-->
    <div id="page-content" id="divIdPDF">

        <div class="panel panel-bordered panel-dark">
            <div class="panel-heading">
                <h3 class="panel-title">Generate Notice
                </h3>
            </div>
            <div class="panel-body">
                <div class="row">
                    <div class="col-md-3 pad-btm">Acknowledgement No</div>
                    <div class="col-md-3 pad-btm"><?=$application_no;?></div>
                    <div class="col-md-3 pad-btm">Holding No</div>
                    <div class="col-md-3 pad-btm"><?=($new_holding_no=="")?"N/A":$new_holding_no;?></div>
                </div>
                <div class="row">
                    <div class="col-md-3 pad-btm">Colony/ Building name</div>
                    <div class="col-md-3 pad-btm"><?=$building_colony_name;?></div>
                    <div class="col-md-3 pad-btm">Office Name</div>
                    <div class="col-md-3 pad-btm"><?=$office_name;?></div>
                    <div class="col-md-3 pad-btm">Address</div>
                    <div class="col-md-9 pad-btm"><?=$building_colony_address;?></div>
                </div>
                <div class="row">
                    <div class="col-md-3 pad-btm">Officer Name</div>
                    <div class="col-md-3 pad-btm"><?=$officer_name;?></div>
                    <div class="col-md-3 pad-btm">Mobile No</div>
                    <div class="col-md-3 pad-btm"><?=$mobile_no;?></div>
                </div>
            </div>
        </div>
		<?php if($t_balance > 0){?>
        <div class="panel panel-bordered panel-dark">
            <div class="panel-heading">
                <h3 class="panel-title">Demand Details
                </h3>
            </div>
            <div class="panel-body">
                <table id="data_table_view" style="width: 100%; font-weight: 300;margin-top:15px;">
                    <tr id="data_table_view_first_row">
                        <td >
                            Demand From
                        </td>
                        <td >
                            Demand Upto
                        </td>
                        <td >
                            Demand (In Rs.)
                        </td>
                        <td >
                            RWH Penalty (In Rs.)
                        </td>
                        <td >
                            Already Paid (In Rs.)
                        </td>
                        <td >
                            Total (In Rs.)
                        </td>
                    </tr>
                    <tr >
                        <td >
                            <?=$demand_from_qtr." / ".$demand_from_fy;?>
                        </td>
                        <td >
                            <?=$demand_upto_qtr." / ".$demand_upto_fy;?>
                        </td>
                        <td >
                            <?=$t_balance;?>
                        </td>
                        <td >
                            <?=$t_additional_holding_tax;?>
                        </td>
                        <td >
                            <?=$t_adjust_amount+$payment_dtl["AdvanceAmount"];?>
                        </td>
                        <td >
                            <?=$t_balance;?>
                        </td>
                    </tr>
                    <tr>
                        <td colspan="2" style="font-size: 16px; text-align: right;font-weight:600">Total</td>
                        <td style="font-weight:600"><?=$t_balance;?></td>
                        <td style="font-weight:600"><?=$t_additional_holding_tax;?></td>
                        <td style="font-weight:600"><?=$t_adjust_amount+$payment_dtl["AdvanceAmount"];?></td>
                        <td style="font-weight:600"><?=$t_balance;?></td>
                    </tr>
                    <tr style="font-weight:600">
                        <td colspan="4" >Penalty</td>
                        <td colspan="4" >Rs. <?=$payment_dtl["LateAssessmentPenalty"];?></td>
                    </tr>
                    <tr style="font-weight:600">
                        <td colspan="4" >1% Interest</td>
                        <td colspan="4" >Rs. <?=$payment_dtl["OnePercentPnalty"];?></td>
                    </tr>
                    <tr style="font-weight:600">
                        <td colspan="4" >Rebate</td>
                        <td colspan="4" >Rs. <?=$payment_dtl["RebateAmount"];?></td>
                    </tr>
                    <tr style="font-weight:600">
                        <td colspan="4" >Total Payable</td>
                        <td colspan="4" >
                        <?php $total_payable = $payment_dtl["LateAssessmentPenalty"]+$payment_dtl["OnePercentPnalty"]+$t_balance-$payment_dtl["AdvanceAmount"]-$payment_dtl["RebateAmount"]; 
                            if($total_payable<=0){$total_payable = 0;}else{$total_payable = $total_payable;}
                        ?>
                        Rs. <?=round($total_payable);?>.00</td>
                    </tr>
                    <tr style="font-weight:600">
                        <td colspan="4" >Total Demand (in words)</td>
                        <td colspan="4" >
                        <?=ucwords(getIndianCurrency(round($total_payable)));?> Only.</td>
                    </tr>
                </table>
            </div>
        </div>
		<?php } ?>
        <div class="panel panel-bordered panel-dark">
            <div class="panel-heading">
                <h3 class="panel-title">Notice Form</h3>
            </div>
            <div class="panel-body">
                <div class="row">
                    <div class="col-md-12">
                        <form method="post" id="form_saf_property" name="form_saf_property" action="">
                            <div class="row">
                                <div class="col-md-2 col-xs-6">
                                    <div class="form-group">
                                        <label class="control-label">Notice Date <span class="text-danger">*</span></label>
                                        <input type="date" id="notice_date" name="notice_date" class="form-control notice_date" value="<?= date('Y-m-d'); ?>" max="<?= date('Y-m-d'); ?>" required="required"/>
                                    </div>
                                </div>
                                <div class="col-md-2 col-xs-6">
                                    <div class="form-group">
                                        <label class="control-label">Notice Type</label>
                                        <select id="notice_type" name="notice_type" class="form-control demand_notice" required="required">
                                            <option value="">SELECT</option>
                                            <option value="Demand">Demand</option>
                                            <!-- <option value="Assessment">Assessment</option> -->
                                        </select>
                                    </div>
                                </div>
                                <div class="col-md-offset-1 col-md-5 col-xs-6">
                                    <div class="form-group">
                                        <label class="col-md-12 control-label" style="margin-top:16px;"></label>
                                        <button type="submit" id="gen_notice" name="gen_notice" class="btn btn-primary">Generate Notice</button>
                                    </div>
                                </div>
                            </div>
                        </form>
                        <br />
                        <br />
                        <br />
                        <br />
                        <?php if(isset($notice_dtl) && !empty($notice_dtl)){ ?>
                        <div class="">
                            <table class="table table-bordered" with="100%">
                                <thead>
                                    <tr>
                                        <th>Notice No.</th>
                                        <th>Notice Date</th>
                                        <th>Type</th>
                                        <th>Generate Date</th>
                                        <th>View</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php foreach($notice_dtl as $notice){ ?>
                                        <tr>
                                            <td>NOTICE/<?=$notice['notice_no'];?></td>
                                            <td><?=date('d-m-Y', strtotime($notice['notice_date']));?></td>
                                            <td><?=$notice['notice_type'];?> Notice</td>
                                            <td><?=date('d-m-Y', strtotime($notice['created_on']));?></td>
                                            <td><a onClick="PopupCenter('<?= base_url('govsafDetailPayment/GeneratedNotice/' . md5($notice['id'])); ?>', 'Notice', 1024, 786)" id="customer_view_detail" class="btn btn-primary">View</a></td>
                                        </tr>
                                    <?php }?>
                                </tbody>
                            </table>
                        </div>
                        <?php }?>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <!--End page content-->
</div>
<!--END CONTENT CONTAINER-->



<?= $this->include('layout_vertical/footer'); ?>


