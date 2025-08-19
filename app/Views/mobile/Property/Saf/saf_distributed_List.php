
<?=$this->include("layout_mobi/header");?>

<?php //print_var($posts); ?>
<!--DataTables [ OPTIONAL ]-->
<!--CONTENT CONTAINER-->
            <!--===================================================-->
	<div id="content-container">
		<div id="page-content">
			<div class="panel panel-bordered panel-dark">
				<?php
				if($safdetail){
				?>
				<div class="panel-heading">
					<div class="panel-control">
						<a href="<?=base_url('safdistribution/form_distribute')?>"> <button class="btn btn-info" >New Distribution</button></a>
					</div>
					<span href="#" data-toggle="collapse" data-target="#demo">
						<h3 class="panel-title"><b style="color:white;">Search Application
						<!-- <i class="fa fa-arrow-down text-right" aria-hidden="true" style="float: right;margin-top: 13px;margin-right: 10px;color: aqua;font-size: larger;"></i> -->
						</b>
					</h3>
					</span>
				</div>
				<div class="panel-body" id="demo">
				<?php }else{ ?>
					<div class="panel-heading">
						<div class="panel-control">
							<a href="<?=base_url('safdistribution/form_distribute')?>"> <button class="btn btn-info" >New Distribution</button></a> | <a href="<?=base_url('Mobi/mobileMenu/property');?>" class="btn btn-info">Back</a>
						</div>
						<h3 class="panel-title"><b style="color:white;">Search Application</b></h3>
					</div>
					<div class="panel-body" id="demo">
				<?php } ?>
				
					<div class="col-md-12">
						<form  method="post" role="form" class="php-email-form">
							<div class="col-md-12" style="font-size:14px;">
								<div class="row">
									<div class="col-md-2">
										<label for="exampleInputEmail1">From Date<span class="text-danger">*</span></label>
									</div>
									<div class="col-md-3">
										<div class="form-group">
											<input type="date" id="datefrom" name="datefrom" class="form-control" style="height:38px;" value="<?php if(isset($datefrom)){echo $datefrom;}else{ echo date('Y-m-d');} ?>">
										</div>
									</div>
									<div class="col-md-2">
										<label for="saf_no"> Upto Date <span class="text-danger">*</span></label>
									</div>
									<div class="col-md-3">
										<div class="form-group">
											<input type="date" id="dateto" name="dateto" class="form-control" style="height:38px;" value="<?php if(isset($dateto)){echo $dateto;}else{ echo date('Y-m-d');} ?>">
										</div>
									</div>								
								</div>
								
								<div class="row">
									<div class="col-md-2">
										<label for="exampleInputEmail1">Ward No.</label>
									</div>
									<div class="col-md-3">
										<div class="form-group">
											<select id="ward_mstr_id" name="ward_mstr_id" class="form-control m-t-xxs">
												<option value="">Select</option>
												<?php if($wardList): ?>
												<?php foreach($wardList as $postward): ?>
												<option value="<?=$postward['ward_mstr_id']?>" <?=(isset($ward_mstr_id))?$ward_mstr_id==$postward["ward_mstr_id"]?"SELECTED":"":"";?>><?=$postward['ward_no'];?></option>
												<?php endforeach; ?>
												<?php endif; ?>
											</select>
										</div>
									</div>
								</div>
								<div class="row">
									<div class="col-md-2"><b style="margin-left:50%;color:red;">OR</b></div>
								</div>
								<div class="row">
									<div class="col-md-2">
										<label for="saf_no">Enter Application No. <span class="text-danger">*</span></label>
									</div>
									<div class="col-md-3">
										<div class="form-group">
											<input type="text" id="saf_no" name="saf_no" class="form-control" style="height:38px;" placeholder="Enter Application No." value="<?=$saf_no; ?>">
										</div>
									</div>
								</div>

								<div class="row">
									<div class="col-md-12 pad-btm" align="center">
										<button type="submit" id="search" name="search" class="btn btn-primary" value="search">SEARCH</button>
									</div>                             
								</div>
							</div>
						</form>
					</div>
                </div>
            </div>
				
			<?php if($safdetail):
			$i=1;  ?>
			<div class="panel panel-bordered panel-dark">
				<div class="panel-heading">
					<h3 class="panel-title"><b style="color:white;">Citizen List </b></h3>
				</div>
				<div class="table-responsive">
					<table id="demo_dt_basic" class="table table-striped table-bordered"  width="100%">
						<thead class="bg-trans-dark text-dark">
								<th>Sl No. </th>
								<th>Ward No </th>
								<th>Form No </th>									
								<th>Owner(s) Name </th>
								<th>Owner(s) Mobile </th>
								<th>Address </th>
								<th>SAF No </th>										
								<th>Action </th>
						</thead>
						<tbody>
							
							<?php 
							
							foreach($safdetail as $post){ ?>
							<tr>
								<td><?=$i++; ?></td>
								<td><?=$post['ward_no'];?></td>
								<td><?=$post['form_no'];?></td>
								<td><?=$post['owner_name'];?></td>
								<td><?=$post['phone_no'];?></td>
								<td><?=$post['owner_address'];?></td>
								<td><?=$post['saf_no'];?></td>
								<td><a href="<?=base_url('safdistribution/form_distribute_view/'.md5($post['id']));?>" type="button" id="customer_view_detail" class="btn btn-primary" style="color:white;"><i class="fa fa-eye" aria-hidden="true"></i></a></td>
							</tr>
							<?php  }
							?>
						</tbody>
					</table>
					<?=pagination($count);?>
				</div>
			</div>
			<?php endif; ?>
		</div>
	</div>
  <br><br><!-- End Contact Section -->

<?=$this->include("layout_mobi/footer");?>