<?= $this->include('layout_vertical/header');?>
<!--DataTables [ OPTIONAL ]-->
<link href="<?=base_url();?>/assets/datatables/css/jquery.dataTables.min.css" rel="stylesheet">
<link href="<?=base_url();?>/assets/datatables/css/buttons.dataTables.min.css" rel="stylesheet">
<link href="<?=base_url();?>/assets/datatables/css/responsive.bootstrap.min.css" rel="stylesheet">

<style>
	
.row{line-height:25px;}
</style>
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
					<li><a href="#">Water</a></li>
					<li class="active">Water Connection List</li>
                    </ol>
                    <!--~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~-->
                    <!--End breadcrumb-->
                </div>
                <!--Page content-->
                <!--===================================================-->
                <div id="page-content">
                    <div class="panel panel-bordered panel-dark">
						<div class="panel-heading">
							<div class="panel-control">
								<a href="<?php echo base_url('waterdocument/index') ?>" class="btn btn-default">Back</a>
							</div>
							<h3 class="panel-title">Basic Details</h3>
						</div>
						<div class="panel-body">
							<div class="row">
								<div class="col-sm-2">
									<b>Ward No. :</b>
								</div>
								<div class="col-sm-3">
									<?=$ward['ward_no']?$ward['ward_no']:"N/A"; ?>
								</div>
								
								<div class="col-sm-2">
									<b>Category :</b>
								</div>
								<div class="col-sm-3">
									<?=$basic_details['category']?$basic_details['category']:"N/A"; ?>
								</div>
							</div>
							<div class="row">
								<div class="col-sm-2">
									<b>Application No. :</b>
								</div>
								<div class="col-sm-3">
									<?=$basic_details['application_no']?$basic_details['application_no']:"N/A"; ?>
								</div>
								
								<div class="col-sm-2">
									<b>Connection Through :</b>
								</div>
								<div class="col-sm-3">
									<?=$basic_details['connection_through']?$basic_details['connection_through']:"N/A"; ?>
								</div>
							</div>
							<div class="row">
								<div class="col-sm-2">
									<b>Connection Type :</b>
								</div>
								<div class="col-sm-3">
									<?=$basic_details['connection_type']?$basic_details['connection_type']:"N/A"; ?>
								</div>
								
								<div class="col-sm-2">
									<b>Property Type :</b>
								</div>
								<div class="col-sm-3">
									<?=$basic_details['property_type']?$basic_details['property_type']:"N/A"; ?>
								</div>
							</div>
							<div class="row">
								<div class="col-sm-2">
									<b>Pipeline Type :</b>
								</div>
								<div class="col-sm-3">
									<?=$basic_details['pipeline_type']?$basic_details['pipeline_type']:"N/A"; ?>
								</div>
								
								<div class="col-sm-2">
									<b>Area :</b>
								</div>
								<div class="col-sm-3">
									<?=$basic_details['area_sqft']?$basic_details['area_sqft'].'sqft':"N/A"; ?>
								</div>
							</div>
							<div class="row">
								<div class="col-sm-2">
									<b>Address :</b>
								</div>
								<div class="col-sm-3">
									<?=$basic_details['address']?$basic_details['address']:"N/A"; ?>
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
									  <th scope="col">Guardian's Name</th>
									  <th scope="col">Mobile No</th>
									</tr>
								</thead>
								<tbody>
									<?php if($owner_details==""){ ?>
										<tr>
											<td colspan="3" style="text-align:center;"> Data Not Available...</td>
										</tr>
									<?php }else{ ?>
									<?php foreach($owner_details as $owner_details): ?>
										<tr>
										  <td><?php echo $owner_details['applicant_name']; ?></td>
										  <td><?php echo $owner_details['father_name']; ?></td>
										  <td><?php echo $owner_details['mobile_no']; ?></td>
										</tr>
									<?php endforeach; ?>
									<?php } ?>
								</tbody>
							</table>
						</div>
					</div>

					<div class="panel">
						<div class="panel-body text-center">
							<?php
							if($basic_details['doc_status']=='0')
							{ 
							?>
							<a href="<?php echo base_url('waterdocument/doc_upload/'.$id);?>" type="button" class="btn btn-purple btn-labeled">Document Upload</a>
							<?php
							}
							else
							{ 
							?>
							<a href="<?php echo base_url('waterdocument/docview/'.$id);?>" type="button" class="btn btn-purple btn-labeled">Document Upload View</a>
							<?php
							}
							?>
						</div>
					</div>
                </div>
                <!--===================================================-->
                <!--End page content-->

            </div>
            <!--===================================================-->
            <!--END CONTENT CONTAINER-->
<?= $this->include('layout_vertical/footer');?>
