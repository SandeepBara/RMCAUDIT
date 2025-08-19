<?= $this->include('layout_vertical/header');?>
<style>
.row{line-height:25px;}
</style>

<!--CONTENT CONTAINER-->
            <!--===================================================-->
            <div id="content-container">
                <div id="page-head">
                    <!--Breadcrumb-->
                    <!--~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~-->
                    <ol class="breadcrumb">
                    <li><a href="#"><i class="demo-pli-home"></i></a></li>
                    <li><a href="#">Report</a></li>
                    <li class="active">Level Wise Pending Report</li>
                    </ol>
                    <!--~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~-->
                    <!--End breadcrumb-->
                </div>
                <!--Page content-->
                <!--===================================================-->
                <div id="page-content">
					<div class="panel panel-bordered panel-dark">
						<div class="panel-heading" style="background-color: #298da0;">
							<div class="panel-control">                  
								<a href="<?php echo base_url('levelwisependingform/exportreportlevelpending/');?>" class="btn btn-primary"><i class="fa fa-arrow-down" aria-hidden="true"></i> EXCEL EXPORT</a>
							</div>
							<h3 class="panel-title">Level Wise Pending Form</h3>
						</div>
						<div class="panel-body">
							<table class="table table-bordered table-responsive" id="level_list">
								<tr style="background-color: #e6e1e1;">
									<th>Sl No.</th>
									<th>Level</th>
									<th>Total No of Form(s)</th>
									<th></th>
									<th></th>
								</tr>
								<?php
									$i=0;
									foreach($levelpending as $rec)
									{
										?>
										<tr>
											<td><?=++$i;?></td>
											<td><?=$rec["user_type"];?></td>
											<td><?=$rec["levelform"];?></td>
											<td><a href="<?php echo base_url('levelwisependingform/levelformdetail/'.md5($rec["receiver_user_type_id"]));?>" type="button" class="btn btn-primary btn-labeled">View SAF Wise Details</a></td>
											<td><a href="<?php echo base_url('levelwisependingform/reportUserWiseLevelPending/'.$rec["user_type"]);?>" type="button" class="btn btn-primary btn-labeled">View Employee Wise Details</a></td>
										</tr>
										<?php
									}
									if(isset($levelpending_utc)) {
										foreach($levelpending_utc as $rec)
										{
											?>
											<tr>
												<td><?=++$i;?></td>
												<td><?=$rec["user_type"];?></td>
												<td><?=$rec["levelform"];?></td>
												<td><a href="<?php echo base_url('levelwisependingform/levelformdetail/'.md5($rec["receiver_user_type_id"]));?>" type="button" class="btn btn-primary btn-labeled">View SAF Wise Details</a></td>
												<td><a href="<?php echo base_url('levelwisependingform/reportUserWiseLevelPending/'.$rec["user_type"]);?>" type="button" class="btn btn-primary btn-labeled">View Employee Wise Details</a></td>
											</tr>
											<?php
										}
									}
									if(isset($levelpending_back_office)) {
										foreach($levelpending_back_office as $rec)
										{
											?>
											<tr>
												<td><?=++$i;?></td>
												<td><?=$rec["user_type"];?></td>
												<td><?=$rec["levelform"];?></td>
												<td><a href="<?php echo base_url('levelwisependingform/levelformdetail/'.md5($rec["receiver_user_type_id"]));?>" type="button" class="btn btn-primary btn-labeled">View SAF Wise Details</a></td>
												<td><a href="<?php echo base_url('levelwisependingform/reportUserWiseLevelPending/'.$rec["user_type"]);?>" type="button" class="btn btn-primary btn-labeled">View Employee Wise Details</a></td>
											</tr>
											<?php
										}
									}
								?>
								
							</table>
						</div>
					</div>
				</div>
			</div>
		
            <!--===================================================-->
            <!--END CONTENT CONTAINER-->
<?= $this->include('layout_vertical/footer');?>
