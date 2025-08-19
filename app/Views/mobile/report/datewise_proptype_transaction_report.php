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
				<div style="flex:1;text-align:right"><button onclick="history.go(-1)" class="btn btn-info btn_wait_load">Back</button></div>

			</div>
            <div class="panel-body">
				<form class="form-horizontal" method="post" action="<?=base_url('TCTransactionReport/datewise_proptype_transaction_report/');?>">
					<div class="row">
						<div class="col-sm-6">
							<label class="control-label" for="from_date"><b>From Date</b> <span class="text-danger">*</span></label>
						</div>
						<div class="col-sm-6 pad-btm">
							<input type="date" id="from_date" name="from_date" class="form-control" value="<?=(isset($from_date))?$from_date:date('Y-m-d');?>">
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
							<label class="control-label" for="Payment Mode"><b>Property Type</b><span class="text-danger">*</span> </label>
						</div>
						<div class="col-sm-6 pad-btm">
							<select id="tran_type" name="tran_type" class="form-control">
								<option value="">ALL</option>
								<option value="Saf" <?=(isset($tran_type))?(($tran_type=="Saf")?"selected":""):"";?>>SAF</option>
								<option value="Property" <?=(isset($tran_type))?(($tran_type=="Property")?"selected":""):"";?>>PROPERTY</option>
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
							</tr>
						</thead>
						<tbody>
							<?php if(!isset($transactionList)): ?>
							<tr>
								<td colspan="9" style="text-align: center;">Data Not Available!!</td>
							</tr>
							<?php else:
							if(empty($transactionList)):
							?>
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
							</tr>

							<?php endforeach;?>
							<?php endif;  ?>
							<?php endif;  ?>
						</tbody>
						<?php
						if(!empty($transactionList)):
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
            	$('#btn_search').html('Please Wait...');
            	return true; 
            }
        });
        $("#from_date").change(function(){$(this).css('border-color','');});
        $("#to_date").change(function(){$(this).css('border-color','');});
    });

</script>                