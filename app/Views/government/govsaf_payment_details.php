
<?= $this->include('layout_vertical/header');?>
<style>
 .row{line-height:25px;}
</style>
<!--DataTables [ OPTIONAL ]-->
<!--CONTENT CONTAINER-->
            <!--===================================================-->
            <div id="content-container">
                <div id="page-head">

                    <!--Page Title-->
                    <!--~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~-->
                    <div id="page-title">
                        <!--<h1 class="page-header text-overflow">Designation List</h1>//-->
                    </div>
                    <!--~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~-->
                    <!--End page title-->


                    <!--Breadcrumb-->
                    <!--~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~-->
                    <ol class="breadcrumb">
					<li><a href="#"><i class="demo-pli-home"></i></a></li>
					<li><a href="#">Government SAF</a></li>
					<li class="active">Payment Details</li>
                    </ol>
                    <!--~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~-->
                    <!--End breadcrumb-->

                </div>

				<div id="page-content">
					<div class="panel panel-bordered panel-dark">
						<div class="panel-heading">
							<div class="panel-control">
								<button onclick="goBack()" class="btn btn-info">Go Back</button>
							</div>
							<h3 class="panel-title">Payment Details</h3>
						</div>
						<div class="panel-body">
							<div class="row">
								<div class="col-sm-2">
									<b>Acknowledgement No.</b>
								</div>
								<div class="col-sm-1">
									<b> : </b>
								</div>
								<div class="col-sm-3">
									<?=$paybasic_details['application_no']?$paybasic_details['application_no']:"N/A"; ?>
								</div>
								
								<div class="col-sm-2">
									<b>Application Type</b>
								</div>
								<div class="col-sm-1">
									<b> : </b>
								</div>
								<div class="col-sm-3">
									<?=$paybasic_details['application_type']?$paybasic_details['application_type']:"N/A"; ?>
								</div>
							</div>
							<div class="row">
								<div class="col-md-2">
									<b>Ownership Type</b>
								</div>
								<div class="col-sm-1">
									<b> : </b>
								</div>
								<div class="col-md-3">
									<?=$paybasic_details['ownership_type']?$paybasic_details['ownership_type']:"N/A"; ?>
								</div>
								
								<div class="col-md-2">
									<b>Building Address</b>
								</div>
								<div class="col-sm-1">
									<b> : </b>
								</div>
								<div class="col-md-3">
									<?=$paybasic_details['building_colony_address']?$paybasic_details['building_colony_address']:"N/A"; ?>
								</div>
							</div>
							
							<div class="row">
								<div class="col-md-2">
									<b>Designation</b>
								</div>
								<div class="col-sm-1">
									<b> : </b>
								</div>
								<div class="col-md-3">
									<?=$paybasic_details['designation']?$paybasic_details['designation']:"N/A"; ?>
								</div>
								<div class="col-md-2">
									<b>Address</b>
								</div>
								<div class="col-sm-1">
									<b> : </b>
								</div>
								<div class="col-md-3">
									<?=$paybasic_details['address']?$paybasic_details['address']:"N/A"; ?>
								</div>
							</div>
						</div>
					</div>
										
					<div class="panel panel-bordered panel-dark">
						<div class="panel-heading">
							<h3 class="panel-title">Payment Details</h3>
						</div>
						<div class="table-responsive">
							<table class="table table-bordered" style="font-family:Arial, Helvetica, sans-serif; font-size:12px;">
								<thead class="bg-trans-dark text-dark">
									<th scope="col"> Sl No.</th>
									<th scope="col"> Transaction No</th>
									<th scope="col"> Date</th>
									<th scope="col"> Payment From</th>
									<th scope="col"> Payment Upto</th>
									<th scope="col"> Amount</th>
									<th scope="col"> Status</th>
									<th scope="col"> View</th>
								</thead>
								<tbody>
									<?php if(isset($pymnt_detail)):
									$i=1;
									?>
									<?php foreach($pymnt_detail as $payment_detail): 
									?>
									<tr>
										<td><?php echo $i++; ?></td>
										<td><?php echo $payment_detail['tran_no']; ?></td>
										<td><?php echo $payment_detail['tran_date']; ?></td>
										<td><?php echo $payment_detail['from_qtr']." / ".$payment_detail['from_fy']; ?></td>
										<td><?php echo $payment_detail['upto_qtr']." / ".$payment_detail['from_upto']; ?></td>
										<td><?php echo $payment_detail['payable_amt']; ?></td>
										<td><?=$payment_detail['status']==1?"Payment Done":"N/A"; ?></td>
										<td><a href="<?php echo base_url('govsafDetailPayment/govsaf_payment_receipt/'.md5($payment_detail['id']));?>" type="button" id="customer_view_detail" class="btn btn-primary" style="color:white;">View</a></td>
									</tr>
									<?php endforeach; ?>
									<?php else: ?>
									<tr>
										<td colspan="9" style="text-align:center;color:red;"> Data Not Available...</td>
									</tr>
									<?php endif; ?>
								</tbody>
							</table>
						</div>
					</div>
									
					<div class="panel">
						<div class="panel-body text-center">
							<a href="<?php echo base_url('govsafDetailPayment/gov_saf_application_details/'.$id);?>" type="button" class="btn btn-primary btn-labeled">Application Details</a>
						</div>
					</div>
				</div>
			
			
  
<?= $this->include('layout_vertical/footer');?>

<script>
function goBack() {
  window.history.back();
}
</script>
