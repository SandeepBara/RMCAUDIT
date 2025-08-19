<?= $this->include('layout_vertical/header');?>

<!--CONTENT CONTAINER-->
<div id="content-container">
    <!--Page content-->
    <div id="page-content">       
        <div class="panel panel-bordered panel-dark">
			<div class="panel-heading">
				<h3 class="panel-title">Search Application</h3>
			</div>
			<div class="panel-body">
				<form method="post" action="">
					<div class="row">
						<div class="col-lg-4 col-md-4 col-sm-12 col-xs-12 pad-btm">
						   Date From:
						   <input type="date" name="from_date" id="from_date" value="<?php echo $from_date;?>" class="form-control">
						</div>
					
						<div class="col-lg-4 col-md-4 col-sm-12 col-xs-12 pad-btm">
						   Date To:
						   <input type="date" name="upto_date" id="upto_date" value="<?php echo $upto_date;?>" class="form-control">
						</div>
					
						<div class="col-lg-4 col-md-4 col-sm-12 col-xs-12 pad-btm text-center">
							<br>
							<button type="submit" id="search" name="search" class="col-lg-6 col-md-6 col-sm-12 col-xs-12 btn btn-primary" value="search">Search</button>
						</div>
						
					</div>
				</form>
			</div>
		</div>
 
		<div class="panel panel-bordered panel-dark">
			<div class="panel-heading">
				<h3 class="panel-title">APPLICATION DETAILS</h3>
			</div>
			<div class="panel-body">
				<div class="row">
					<div class="table-responsive">
						<table id="demo_dt_basic" class="table table-striped table-bordered" cellspacing="0" width="100%">
							<thead>
								<tr>
									<th>#</th>
									<th>Ward No.</th>
									<th>Application No.</th>
									<th>Consumer Name</th>
									<th>Mobile No.</th>
									<th>Connection Type</th>
									<th>Property Type</th>
									<th>Apply Date</th>
									<th>Action</th>
								</tr>
							</thead>
							<tbody>
								<?php if(isset($consumer_dtls)):
									if(empty($consumer_dtls)):
								?>
								<tr>
									<td colspan="9" style="text-align: center;">Data Not Available!!</td>
								</tr>
								<?php else:
								$i=0;
								foreach ($consumer_dtls as $val):
								?>
								<tr>
									<td><?=++$i;?></td>
									<td><?=$val['ward_no'];?></td>
									<td><?=$val['application_no'];?></td>
									<td><?=$val['applicant_name'];?></td>
									<td><?=$val['mobile_no'];?></td>
									<td><?=$val['connection_type'];?></td>
									<td><?=$val['property_type'];?></td>
									<td><?=$val['apply_date'];?></td>
									<td>
										<a href="<?php echo base_url('waterfieldSiteInspection/field_verification/'.md5($val['id']).'/'.md5($val['level_pending_dtl_id']));?>" class="btn btn-primary">View</a>
									</td>
								</tr>
								<?php endforeach;?>
								<?php endif;  ?>
								<?php endif;  ?>
							</tbody>
						</table>
					</div>
				</div>
			</div>
		</div>
    </div>
</div>
    <!--End page content-->
<!--END CONTENT CONTAINER-->
<?= $this->include('layout_vertical/footer');?>
