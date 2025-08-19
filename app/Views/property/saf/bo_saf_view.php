<?= $this->include('layout_vertical/header');?>
<!--DataTables [ OPTIONAL ]-->
<style>	
.row{line-height:25px;}
</style>
<link href="<?=base_url();?>/assets/datatables/css/jquery.dataTables.min.css" rel="stylesheet">
<link href="<?=base_url();?>/assets/datatables/css/buttons.dataTables.min.css" rel="stylesheet">
<link href="<?=base_url();?>/assets/datatables/css/responsive.bootstrap.min.css" rel="stylesheet">
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
					<li><a href="#">SAF</a></li>
					<li class="active">BO SAF View</li>
                    </ol>
                    <!--~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~-->
                    <!--End breadcrumb-->
                </div>
                <!--Page content-->
                <!--===================================================-->
                <div id="page-content">
					<div class="panel panel-bordered panel-dark">
						<div class="panel-body">
							<div class="row">
								<div class="col-sm-6 text-danger">
									<b>Application No. :&nbsp; <?=$basic_details['saf_no']; ?></b>
								</div>
								<div class="col-sm-6 text-danger">
									<b>Application Status : &nbsp;<?=$SAFLevelPending; ?></b>
								</div>
							</div>
						</div>
						<div class="panel-heading">
							<div class="panel-control">
								<a href="<?php echo base_url('bo_saf/index') ?>" class="btn btn-default">Back</a>
							</div>
							<h3 class="panel-title">Basic Details</h3>
						</div>
						<div class="panel-body">
							<div class="row">
								<div class="col-sm-3">
									<b>Ward No. </b>
								</div>
								<div class="col-sm-3">
									<?=$basic_details['ward_no']?$basic_details['ward_no']:"N/A"; ?>
								</div>
								
								<div class="col-sm-3">
									<b>Application No. </b>
								</div>
								<div class="col-sm-3">
									<?=$basic_details['saf_no']?$basic_details['saf_no']:"N/A"; ?>
								</div>
							</div>
							<div class="row">
								<div class="col-md-3">
									<b>Property Type </b>
								</div>
								<div class="col-md-3">
									<?=$basic_details['property_type']?$basic_details['property_type']:"N/A"; ?>
								</div>
								
								<div class="col-md-3">
									<b>Ownership Type </b>
								</div>
								<div class="col-md-3">
									<?=$basic_details['ownership_type']?$basic_details['ownership_type']:"N/A"; ?>
								</div>
							</div>
							<div class="row">
								<div class="col-md-3">
									<b>Address </b>
								</div>
								<div class="col-md-3">
									<?=$basic_details['prop_address']?$basic_details['prop_address']:"N/A"; ?>
								</div>
							</div>
							<div class="row">
								<div class="col-md-3">
									<b>Area Of Plot </b>
								</div>
								<div class="col-md-3">
									<?=$basic_details['area_of_plot']?$basic_details['area_of_plot']:"N/A"; ?>(In dismil)
								</div>
								
								<div class="col-md-3">
									<b>Assessment Type </b>
								</div>
								<div class="col-md-3">
									<?=$basic_details['assessment_type']?$basic_details['assessment_type']:"N/A"; ?>
								</div>
							</div>
							<div class="row">
								<div class="col-md-3">
									<b> Khata </b>
								</div>
								<div class="col-md-3">
									<?=$basic_details['khata_no']?$basic_details['khata_no']:"N/A"; ?>
								</div>
								
								<div class="col-md-3">
									<b> Plot No. </b>
								</div>
								<div class="col-md-3">
									<?=$basic_details['plot_no']?$basic_details['plot_no']:"N/A"; ?>
								</div>
							</div>
							<div class="row">
								<div class="col-md-3">
									<b>Mauja Name </b>
								</div>
								<div class="col-md-3">
									<?=$basic_details['village_mauja_name']?$basic_details['village_mauja_name']:"N/A"; ?>
								</div>
								
								<div class="col-md-3">
									<b>Rainwater Harvesting Provision </b>
								</div>
								<div class="col-md-3">
									<?php if($basic_details['is_water_harvesting']=='f'){
										echo "No";
									} else if($basic_details['is_water_harvesting']=='t'){
										echo "Yes";
									}
									?>
								</div>
							</div>
						</div>
					</div>
					<div class="panel panel-bordered panel-dark">
						<div class="panel-heading">
							<h3 class="panel-title">Owner Details</h3>
						</div>
						<div class="table-responsive">
							<table class="table table-bordered" style="font-family:Arial, Helvetica, sans-serif; font-size:12px;">
								<thead class="bg-trans-dark text-dark">
									<tr>
										<th scope="col">Owner Name</th>
										<th scope="col">R/W Guardian</th>
										<th scope="col">Guardian's Name</th>
										<th scope="col">Mobile No</th>
									</tr>
								</thead>
								<tbody>
									<?php if($owner_details==""){ ?>
										<tr>
											<td style="text-align:center;"> Data Not Available...</td>
										</tr>
									<?php }else{ ?>
									<?php foreach($owner_details as $owner_details): ?>
										<tr>
											<td><?php echo $owner_details['owner_name']; ?></td>
											<td><?php echo $owner_details['relation_type']; ?></td>
											<td><?php echo $owner_details['guardian_name']; ?></td>
											<td><?php echo $owner_details['mobile_no']; ?></td>
										</tr>
									<?php endforeach; ?>
									<?php } ?>
								</tbody>
							</table>
						</div>
					</div>
					<div class="panel panel-bordered panel-dark">
						<div class="panel-heading">
							<h3 class="panel-title">Occupancy Details</h3>
						</div>
						<div class="table-responsive">
							<table class="table table-bordered" style="font-family:Arial, Helvetica, sans-serif; font-size:12px;">
								<thead class="bg-trans-dark text-dark">
									<th scope="col">Sl No.</th>
									<th scope="col">Floor</th>
									<th scope="col">Use Type</th>
									<th scope="col">Occupancy Type</th>
									<th scope="col">Construction Type</th>
									<th scope="col">Total Area (in Sq. Ft.)</th>
									<th scope="col">Total Taxable Area (in Sq. Ft.)</th>
								</thead>
								<tbody>
									<?php if($occupancy_detail):
									$i=1;
									?>
									<?php foreach($occupancy_detail as $occupancy_detail): ?>
									<tr>
										<td><?php echo $i++; ?></td>
										<td><?php echo $occupancy_detail['floor_name']; ?></td>
										<td><?php echo $occupancy_detail['usage_type']; ?></td>
										<td><?php echo $occupancy_detail['occupancy_name']; ?></td>
										<td><?php echo $occupancy_detail['construction_type']; ?></td>
										<td><?php echo $occupancy_detail['builtup_area']; ?></td>
										<td><?php echo $occupancy_detail['carpet_area']; ?></td>
									</tr>
								<?php endforeach; ?>
								<?php endif; ?>
								</tbody>
							</table>
						</div>
					</div>
					<div class="panel panel-bordered panel-dark">
						<div class="panel-heading">
							<h3 class="panel-title">Tax Details</h3>
						</div>
						<div class="table-responsive">
							
							<table class="table table-bordered" style="font-family:Arial, Helvetica, sans-serif; font-size:12px;">
								<thead class="bg-trans-dark text-dark">
									<th scope="col">ARV</th>
									<th scope="col">Effected From</th>
									<th scope="col">Holding Tax</th>
									<th scope="col">Water Tax</th>
									<th scope="col">Conservancy/Latrine Tax</th>
									<th scope="col">Education Cess</th>
									<th scope="col">Health Cess</th>
									<th scope="col">Quarterly Tax</th>
								</thead>
								<tbody>
									<tr>
										<?php if($tax_list):
											$qtr_tax=0; ?>
										<?php foreach($tax_list as $tax_list): 
											$qtr_tax=$tax_list['holding_tax']+$tax_list['water_tax']+$tax_list['latrine_tax']+$tax_list['education_cess']+$tax_list['health_cess'];
										?>
									<tr>
										<td><?php echo $tax_list['arv']; ?></td>
										<td>Quarter : <?php echo $tax_list['qtr']; ?> / Year : <?php echo $tax_list['fy']; ?></td>
										<td><?php echo $tax_list['holding_tax']; ?></td>
										<td><?php echo $tax_list['water_tax']; ?></td>
										<td><?php echo $tax_list['latrine_tax']; ?></td>
										<td><?php echo $tax_list['education_cess']; ?></td>
										<td><?php echo $tax_list['health_cess']; ?></td>
										<td><?php echo $qtr_tax; ?></td>     
									</tr>
									<?php endforeach; ?>
									<?php else: ?>
									<tr>
										<td colspan="7" style="text-align:center;"> Data Not Available...</td>
									</tr>
									<?php endif; ?>
								</tbody>
							</table>
						</div>
					</div>
					
					<div class="panel">
						<div class="panel-body text-center">
							<?php if($basic_details['doc_upload_status']=='0'){ ?>
								<a href="<?php echo base_url('safdoc/index/'.$id);?>" type="button" class="btn btn-purple btn-labeled">Document Upload</a>
							<?php } else { ?>
								<a href="<?php echo base_url('safdoc/view/'.$id);?>" type="button" class="btn btn-purple btn-labeled">Document Upload View</a>
							<?php } ?>
						</div>
					</div>
				</div>
                <!--===================================================-->
                <!--End page content-->
            </div>
            <!--===================================================-->
            <!--END CONTENT CONTAINER-->
<?= $this->include('layout_vertical/footer');?>