<?=$this->include("layout_mobi/header");?>
<!--CONTENT CONTAINER-->
<div id="content-container">
    <!--Page content-->
    <div id="page-content">
        <div class="panel panel-bordered panel-dark">
           
			<div class="panel-heading flex" style="display: flex;">
				<div style="flex:1;">
					<h3 class="panel-title"><b style="color:white;">Search By</b></h3>
				</div>
				<a class="panel-control btn_wait_load" href ="<?=base_url('WaterMobileIndex/water_reports_menu')?>"><i class="fa fa-arrow-left" aria-hidden="true"></i>Back</a>

			</div>
            <div class="panel-body">
				<form class="form-horizontal" method="post" action="<?=base_url('TCTransactionReport/datewise_ward_transaction_report/');?>">
					<div class="row">
						<div class="col-sm-6">
							<label class="control-label" for="from_date"><b>From Date</b> <span class="text-danger">*</span></label>
						</div>
						<div class="col-sm-6 pad-btm">
							<input type="date" id="from_date" name="from_date" class="form-control"  value="<?=(isset($from_date))?$from_date:date('Y-m-d');?>">
						</div>
					</div>
					<div class="row">
						<div class="col-sm-6">
							<label class="control-label" for="to_date"><b>To Date</b><span class="text-danger">*</span> </label>
						</div>
						<div class="col-sm-6 pad-btm">
							<input type="date" id="to_date" name="to_date" class="form-control" value="<?=(isset($to_date))?$to_date:date('Y-m-d');?>">
						</div>
					</div>
					<div class="row">
						<div class="col-sm-6">
							<label class="control-label" for="Payment Mode"><b>Ward</b><span class="text-danger">*</span> </label>
						</div>
						<div class="col-sm-6 pad-btm">
							<select id="ward_id" name="ward_id" class="form-control">
							   <option value="">ALL</option> 
								<?php foreach($wardList as $value):?>
								<option value="<?=$value['id']?>" <?=(isset($ward_id))?($ward_id==$value["id"]?"SELECTED":""):"";?>><?=$value['ward_no'];?>
								</option>
								<?php endforeach;?>
							</select>
						</div>
					</div>
					<div class="row">
						<div class="col-sm-6">
							<button class="btn btn-success" style="width:100%" id="btn_search" name="btn_search" type="submit">Submit</button>
						</div>
					</div>
				</form>
            </div>
        </div>
		<div class="panel panel-bordered panel-dark">
			<div class="panel-heading">
				<h5 class="panel-title"><b>Daily Collection List</b></h5>
			</div>
			<div class="panel-body" style="padding-bottom: 0px;">
				<div class="table-responsive">
					<table id="demo_dt_basic" class="table table-striped table-bordered" cellspacing="0" width="100%">
						<thead>
							<tr>
								<th>#</th>
								<th>Transaction No</th>
								<th>Transaction Date</th>
								<th>Holding/Saf No</th>
								<th>Payment Type</th>
								<th>Owner Name</th>
								<th>Effected From</th>
								<th>Effected To</th>
								<th>Amount</th>
								<th></th>
							</tr>
						</thead>
							<tbody>
								<?php if(!isset($transactionList)): ?>
								<tr>
									<td colspan="9" style="text-align: center;">Data Not Available!!</td>
								</tr>
								<?php else:
								if(empty($transactionList)): ?>
								<tr>
									<td colspan="9" style="text-align: center;">Data Not Available!!</td>
								</tr>
								<?php else:
								$i=0;
								foreach ($transactionList as $value):
								?>
								<tr>
									<td><?=++$i;?></td>
									<td><?=$value['tran_no']!=""?$value['tran_no']:"";?></td>
									<td><?=$value['tran_date']!=""?$value['tran_date']:"";?></td>
									<td>
										<?=$value['holding']!=""?$value['holding']:"";?>
									</td>
									<td><?=$value['transaction_mode']!=""?$value['transaction_mode']:"";?></td>
									<td><?=$value['owner']!=""?$value['owner']:"";?></td>
									<td><?=$value['fy']!=""?$value['fy']:"";?></td>
									<td><?=$value['upto_fy']!=""?$value['upto_fy']:"";?></td>
									<td><?=$value['payable_amt']!=""?$value['payable_amt']:"";?></td>
									
									<?php  if($value['tran_type']=='Saf') {  ?>
									<td><a href="<?php echo base_url().'/mobisafDemandPayment/saf_payment_receipt/'.md5($val['apply_connection_id']??$value['id']);?>" class="btn btn-info btn_wait_load">View</a></td>
									<?php }else { ?>
									<td><a href="<?php echo base_url().'/mobi/payment_tc_receipt/'.md5($val['apply_connection_id']??$value['id']);?>" class="btn btn-info btn_wait_load">View</a></td>
									<?php } ?>
								</tr>
 
								<?php endforeach;?>
								<?php endif;  ?>
								<?php endif;  ?>
							</tbody>
							<?php if(!empty($transactionList)):
							?>
								<tfoot>
									<tr>
										<td colspan="8" style="text-align: right;">Total</td>
										<td><?=(isset($total))?$total:"";?></td>
									</tr>
								</tfoot>
							<?php endif;  ?>
					</table>
				</div>
			</div>
		</div>
    </div>
    <!--End page content-->
</div>
<!--END CONTENT CONTAINER-->
<?=$this->include("layout_mobi/footer");?>

<script type="text/javascript">
    $(document).ready(function(){

        $('#btn_search').click(function(){
            var from_date = $('#from_date').val();
            var to_date = $('#to_date').val();
            if(from_date=="")
            {
                $("#from_date").css({"border-color":"red"});
                $("#from_date").focus();
                return false;
            }
            if(to_date=="")
            {
                $("#to_date").css({"border-color":"red"});
                $("#to_date").focus();
                return false;
            }
            if(to_date<from_date)
            {
                alert("To Date Should Be Greater Than Or Equals To From Date");
                $("#to_date").css({"border-color":"red"});
                $("#to_date").focus();
                return false;
            }else{
            	$('#btn_search').html('Please Wait');
            	return true;
            }
        });
        $("#from_date").change(function(){$(this).css('border-color','');});
        $("#to_date").change(function(){$(this).css('border-color','');});
    });

</script>                