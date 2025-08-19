<?= $this->include('layout_vertical/header');?>
<!--CONTENT CONTAINER-->
<div id="content-container">
	<div id="page-head">
		<ol class="breadcrumb">
		<li><a href="#"><i class="demo-pli-home"></i></a></li>
		<li><a href="#">SAF</a></li>
		<li class="active">SAF Document Verification List</li>
		</ol>
	</div>
	<!--Page content-->
	<div id="page-content">
		<div class="panel panel-bordered panel-dark">
			<div class="panel-heading">
				<div class="panel-control">
					<a href="<?=base_url()?>/safdtl/full/<?=md5($form['saf_dtl_id']);?>" target="_blank" class="btn btn-default">View full SAF details </a>
					|
					<a href="<?php echo base_url('documentverification/index') ?>" class="btn btn-default">Back</a>
				</div>
				<h3 class="panel-title">Basic Details</h3>
			</div>
			<div class="panel-body" style="padding-bottom: 0px;">
				<div class="row">
					<label class="col-sm-2">Assessment Type:</label>
					<div class="col-sm-4 pad-btm">
						 <b><?=(isset($form['assessment_type']))?$form['assessment_type']:'<span class="text-danger">N/A</span>';?></b>
					</div>
				</div>
				<div class="row">
					<label class="col-sm-2">Holding No.:</label>
					<div class="col-sm-4 pad-btm">
						 <b><?=(isset($form['holding_no']))?$form['holding_no']:'<span class="text-danger">N/A</span>';?></b>
					</div>
				</div>
				<div class="row">
					<label class="col-sm-2">SAF No.:</label>
					<div class="col-sm-4 pad-btm">
						 <b><?=(isset($form['saf_no']))?$form['saf_no']:'<span class="text-danger">N/A</span>';?></b>
					</div>
				</div>
				<div class="row">
					<label class="col-sm-2">Ward No:</label>
					<div class="col-sm-4 pad-btm">
						 <b><?=(isset($form['ward_no']))?$form['ward_no']:'<span class="text-danger">N/A</span>';?></b>
					</div>
				</div>
			</div>
		</div>
        <form method="post" class="form-horizontal" action="">
            <!-------Owner Details-------->
			<div class="panel panel-bordered panel-dark">
				<div class="panel-heading">
					<h3 class="panel-title">Owner Details</h3>
				</div>
				<div class="panel-body" style="padding-bottom: 0px;">
					<div class="table-responsive">
						<table class="table table-bordered text-sm">
							<thead class="thead-light" style="background-color: lightgray;">
								<tr>
									<th>Name</th>
									<th>Relation</th>
									<th>Guardian Name</th>
									<th>Mobile No.</th>

								</tr>
							</thead>
							<tbody>
							<?php
							if(isset($owner_list)):
								 if(empty($owner_list)):
							?>
								<tr>
									<td style="text-align:center;"> Data Not Available...</td>
								</tr>
							<?php else: ?>
							<?php
								$i=1;
								$ib=1;
								$cnt=0;
								foreach($owner_list as $value): 
								$j=$i++;
								$ibb=$ib++;
								?>
								<tr>
									<td><?php echo $value['owner_name']; ?></td>
									<td><?php echo $value['relation_type']; ?></td>
									<td><?php echo $value['guardian_name']; ?></td>
									<td><?php echo $value['mobile_no']; ?></td>
								</tr>
								<tr class="success">
									<th>Document Name</th>
									<th>Applicant Image</th>
									<th>Verify/Reject</th>
									<th>Reject Remarks</th>
								</tr>
								<tr>
									<td>Applicant Image</td>
									<td><a href="<?=base_url();?>/writable/uploads/<?=$value["applicant_img"];?>" target="_blank"><img id="imageresource" src="<?=base_url();?>/writable/uploads/<?=$value["applicant_img"];?>" style="width: 40px; height: 40px;"></a></td>
									<td>
									<?php
									if($value['applicant_img_verify_status']=="0") {
									?>
									<select name="app_img_verify[]" id="app_img_verify<?=$j;?>" class="form-control app_img_verify" onchange="app_img_remarks_details(<?=$j;?>);">
										<option value="">Select</option>
										<option value="1">Verify</option>
										<option value="2">Reject</option>
									</select>
									<br/>
									<input type="hidden" name="applicant_img_id[]" value="<?=$value['applicant_img_id'];?>"/>
									<input type="hidden" id="applicant_img_verify_status<?=$j;?>" value="0"/>
									<?php
									}
									else if($value['applicant_img_verify_status']=="1")
									{
										echo "<span class='text-danger'>Verified</span>";
									}
									else if($value['applicant_img_verify_status']=="2")
									{
										echo "<span class='text-danger'>Rejected</span>";
									}
									?>
									</td>
									<td>
									<?php
									if($value['applicant_img_verify_status']=="0")
									{
									?>
									<textarea type="text" placeholder="Enter Remarks" id="app_img_remarks<?=$j;?>" name="app_img_remarks[]" class="form-control app_img_remarks" ></textarea>
									<?php
									}
									?>
									</td>
								</tr>
								<tr class="success">
									<th>Document Name</th>
									<th>Applicant Document</th>
									<th>Verify/Reject</th>
									<th>Reject Remarks</th>

								</tr>
								<tr style="border-bottom: 5px solid #000;">
									<td><?=$value["applicant_doc_name"];?></td>
									
									<td>
										
										<a href="<?=base_url();?>/writable/uploads/<?=$value["applicant_doc"];?>" target="_blank"> <img id="imageresource" src="<?=base_url();?>/public/assets/img/pdf_logo.png" style="width: 40px; height: 40px;"></a>
										
									 </td>
								  <td>
								  <?php
								  if($value['applicant_doc_verify_status']=="0")
								  {
								  ?>

									  <select name="app_doc_verify[]" id="app_doc_verify<?=$j;?>" class="form-control app_doc_verify" onchange="app_doc_remarks_details(<?=$j;?>);">
										  <option value="">Select</option>
										  <option value="1">Verify</option>
										  <option value="2">Reject</option>
									  </select>
									  <br/>
									  <input type="hidden" name="applicant_doc_id[]" value="<?=$value['applicant_doc_id'];?>"/>
									  <input type="hidden" id="applicant_doc_verify_status<?=$j;?>" value="0"/>
									  <?php
									  }
									  else if($value['applicant_doc_verify_status']=="1")
									  {
										  echo "<span class='text-danger'>Verified</span>";
									  }
									  else if($value['applicant_doc_verify_status']=="2")
									  {
										  echo "<span class='text-danger'>Rejected</span>";
									  }
									  ?>
									</td>
									<td>
										<?php
									  if($value['applicant_doc_verify_status']=="0")
									  {
									  ?>
										<textarea type="text" placeholder="Enter Remarks" id="app_doc_remarks<?=$j;?>" name="app_doc_remarks[]" class="form-control app_doc_remarks" ></textarea>
										<?php
									}
									?>
									</td>


								</tr>

								<?php endforeach; ?>
								<?php endif; ?>
								<?php endif; ?>
								<input type="hidden" name="count_app" id="count_app" value="<?=$app_cnt;?>"/>
								<input type="hidden" name="count_change_app" id="count_change_app" value="0"/>
							</tbody>
						</table>
					</div>
				 </div>
			 </div>
			<?php
			if($Saf_detail['prop_type_mstr_id']!='4')
			{
			?>
			<div class="panel-group">
				<div class="panel panel-bordered panel-dark">
					<div class="panel-heading">
						<h3 class="panel-title"><a data-toggle="collapse" href="#collapse3">Occupancy Details</a></h3>
					</div>
					<div id="collapse3" class="panel-collapse collapse">
						<div class="panel-body">
							<div class="table-responsive">
								<table class="table table-bordered" style="font-family:Arial, Helvetica, sans-serif; font-size:12px;">
									<thead class="thead-light" style="background-color: blanchedalmond;">
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
					</div>
				</div>
			</div>
			<?php
			}
			?>
			<div class="panel-group">
				<div class="panel panel-bordered panel-dark">

					<div class="panel-heading">
						<h3 class="panel-title"><a data-toggle="collapse" href="#collapse31">Tax Details</a></h3>
					</div>
					<div id="collapse31" class="panel-collapse collapse">
						<div class="panel-body">
							<div class="table-responsive">												
								<table class="table table-bordered" style="font-family:Arial, Helvetica, sans-serif; font-size:12px;">
									<thead class="thead-light" style="background-color: blanchedalmond;">
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
					</div>
				</div>
			</div>
			<?php if(isset($fieldVerificationmstr_detail)){
					if(!empty($fieldVerificationmstr_detail)){
			?>
			<div class="panel-group">
				<div class="panel panel-bordered panel-dark">
					<div class="panel-heading">
						<h3 class="panel-title"><a data-toggle="collapse" href="#collapse1">Tax Collector Verification Details</a></h3>
					</div>
					<div id="collapse1" class="panel-collapse collapse">
						<div class="panel-body" style="padding-bottom: 0px;">
							<div class="row">
								<span class="text-danger"><u><b>Verification Details</b></u></span>
							</div>
							<div class="row">
								<div class="table-responsive">
									<table class="table table-bordered" style="font-family:Arial, Helvetica, sans-serif; font-size:12px;">
										<thead class="thead-light" style="background-color: blanchedalmond;">
											<tr>
												<td colspan="3" align="center" ><strong>Self Assessed </strong></td>
												<td colspan="3" align="center" ><strong>Verification Report </strong></td>
											</tr>
										</thead>
                                        <tbody>
                                            <tr>
                                                <td>Property Type </td>
                                                <td><b>:</b></td>
                                                <td><?=$property_type?></td>
                                                <td>Property Type</td>
                                                <td><b>:</b></td>
                                                <td><?=$vproperty_type?></td>
                                            </tr>
                                            <tr>
                                                <td>Ward No.</td>
                                                <td><b>:</b></td>
                                                <td><?=$ward_no?></td>
                                                <td>Property Type</td>
                                                <td><b>:</b></td>
                                                <td><?=$vward_no?></td>
                                            </tr>
                                            <tr>
                                                <td>Width of Road</td>
                                                <td><b>:</b></td>
                                                <td><?=$road_type?></td>
                                                <td>Width of Road</td>
                                                <td><b>:</b></td>
                                                <td><?=$vroad_type?></td>
                                            </tr>
                                            <tr>
                                                <td>Area of Plot</td>
                                                <td><b>:</b></td>
                                                <td><?=$area_of_plot?></td>
                                                <td>Area of Plot</td>
                                                <td><b>:</b></td>
                                                <td><?=$varea_of_plot?></td>
                                            </tr>
                                            <tr>
                                                <td>Rainwater harvesting provision</td>
                                                <td><b>:</b></td>
                                                <td><?php echo $is_water_harvesting=='t'?'Yes':'No';?></td>
                                                <td>Rainwater harvesting provision</td>
                                                <td><b>:</b></td>
                                                <td><?php echo $vis_water_harvesting=='t'?'Yes':'No';?></td>
                                            </tr>

                                            <tr>
                                                <td>Does Property Have Mobile Tower?</td>
                                                <td><b>:</b></td>
                                                <td><?php echo $is_mobile_tower=='t'?'Yes':'No';?></td>
                                                <td>Does Property Have Mobile Tower?</td>
                                                <td><b>:</b></td>
                                                <td><?php echo $vis_mobile_tower=='t'?'Yes':'No';?></td>
                                            </tr>
                                            <tr>
                                                <td>Does Property Have Hoarding Board?</td>
                                                <td><b>:</b></td>
                                                <td><?php echo $is_hoarding_board=='t'?'Yes':'No';?></td>
                                                <td>Does Property Have Hoarding Board?</td>
                                                <td><b>:</b></td>
                                                <td><?php echo $vis_hoarding_board=='t'?'Yes':'No';?></td>
                                            </tr>
                                            <tr>
                                                <td>Is property have Petrol Pump ?</td>
                                                <td><b>:</b></td>
                                                <td><?php echo $is_petrol_pump=='t'?'Yes':'No';?></td>
                                                <td>Is property have Petrol Pump ?</td>
                                                <td><b>:</b></td>
                                                <td><?php echo $vis_petrol_pump=='t'?'Yes':'No';?></td>
                                            </tr>
                                        </tbody>
					                </table>
                                </div>
							</div>
							<?php 
					//if($val_ptype["property_type"]<>"Vacant Land"){ ?>
							<div class="row">
								<span class="text-danger"><u><b>Self Assessed Details</b></u></span>
							</div>
							<div class="row">
								<div class="table-responsive">
                                    <table class="table table-bordered" style="font-family:Arial, Helvetica, sans-serif; font-size:12px;">
                                        <thead class="thead-light" style="background-color: blanchedalmond;">
                                            <tr>
                                                <th>Floor No.</th>
                                                <th>Use Type</th>
                                                <th>Occupancy Type</th>
                                                <th>Construction Type</th>
                                                <th>Built Up Area (in Sq. Ft.)</th>
                                                <th>Carpet Area (in Sq. Ft.)</th>
                                                <th>Date of Completion</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            <?php
                                        foreach ($floor_details as  $valfloor) {
                                            ?>	

                                            <tr style="text-align: center">
                                                <td height="31"><?=$valfloor['floor_name']?></td>
                                                <td><?=$valfloor['usage_type']?></td>
                                                <td><?=$valfloor['occupancy_name']?></td>
                                                <td><?=$valfloor['construction_type']?> </td>
                                                <td><?=$valfloor['builtup_area']?> </td>    	
                                                <td><?=$valfloor['carpet_area']?></td>			
                                                <td><?=date('m-Y',strtotime($valfloor['date_from']))?></td>
                                            </tr>	
                                            <?php } ?>
                                        </tbody>
					                </table>
                                </div>
							</div>
							<div class="row">
								<span class="text-danger"><u><b>Verified Details</b></u></span>
							</div>
							<div class="row">
								<div class="table-responsive">
                                    <table class="table table-bordered" style="font-family:Arial, Helvetica, sans-serif; font-size:12px;">
                                        <thead class="thead-light" style="background-color: blanchedalmond;">
                                            <tr>
                                                <th>Floor No.</th>
                                                <th>Use Type</th>
                                                <th>Occupancy Type</th>
                                                <th>Construction Type</th>
                                                <th>Built Up Area (in Sq. Ft.)</th>
                                                <th>Carpet Area (in Sq. Ft.)</th>
                                                <th>Date of Completion</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            <?php              
                                             foreach ($vfloor_details as  $valfloor1) {		
                                            ?>	

                                            <tr style="text-align: center">
                                                <td height="31"><?=$valfloor1['floor_name']?></td>
                                                <td><?=$valfloor1['usage_type']?></td>
                                                <td><?=$valfloor1['occupancy_name']?></td>
                                                <td><?=$valfloor1['construction_type']?> </td>
                                                <td><?=$valfloor1['builtup_area']?> </td>    	
                                                <td><?=$valfloor1['carpet_area']?></td>			
                                                <td><?=date('m-Y',strtotime($valfloor1['date_from']))?></td>
                                            </tr>	
                                            <?php } ?>
                                        </tbody>
					                </table>
                                </div>
							</div>
							<?php //} ?>
						</div>
					</div>
				</div>
			</div>
			<?php }} ?>
			<?php if(isset($ufieldVerificationmstr_detail)){
					if(!empty($ufieldVerificationmstr_detail)){
			?>
			<div class="panel panel-default">
				<div class="panel panel-bordered panel-dark">
					<div class="panel-heading">
						<h3 class="panel-title"><a data-toggle="collapse" href="#collapse2">ULB Tax Collector Verification Details</a></h3>
					</div>
					<div id="collapse2" class="panel-collapse collapse">
						<div class="panel-body" style="padding-bottom: 0px;">
							<div class="row">
								<span class="text-danger"><u><b>Verification Details</b></u></span>
							</div>
							<div class="row">
								<div class="table-responsive">
                                    <table class="table table-bordered" style="font-family:Arial, Helvetica, sans-serif; font-size:12px;">
                                        <thead class="thead-light" style="background-color: blanchedalmond;">
                                            <tr>
                                                <td colspan="3" align="center" ><strong>Self Assessed </strong></td>
                                                <td colspan="3" align="center" ><strong>Verification Report </strong></td>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            <tr>
                                                <td>Property Type </td>
                                                <td><b>:</b></td>
                                                <td><?=$property_type?></td>
                                                <td>Property Type</td>
                                                <td><b>:</b></td>
                                                <td><?=$uvproperty_type?></td>
                                            </tr>
                                            <tr>
                                                <td>Ward No.</td>
                                                <td><b>:</b></td>
                                                <td><?=$ward_no?></td>
                                                <td>Property Type</td>
                                                <td><b>:</b></td>
                                                <td><?=$uvward_no?></td>
                                            </tr>
                                            <tr>
                                                <td>Width of Road</td>
                                                <td><b>:</b></td>
                                                <td><?=$road_type?></td>
                                                <td>Width of Road</td>
                                                <td><b>:</b></td>
                                                <td><?=$uvroad_type?></td>
                                            </tr>
                                            <tr>
                                                <td>Area of Plot</td>
                                                <td><b>:</b></td>
                                                <td><?=$area_of_plot?></td>
                                                <td>Area of Plot</td>
                                                <td><b>:</b></td>
                                                <td><?=$uvarea_of_plot?></td>
                                            </tr>
                                            <tr>
                                                <td>Rainwater harvesting provision</td>
                                                <td><b>:</b></td>
                                                <td><?php echo $is_water_harvesting=='t'?'Yes':'No';?></td>
                                                <td>Rainwater harvesting provision</td>
                                                <td><b>:</b></td>
                                                <td><?php echo $uvis_water_harvesting=='t'?'Yes':'No';?></td>
                                            </tr>

                                            <tr>
                                                <td>Does Property Have Mobile Tower?</td>
                                                <td><b>:</b></td>
                                                <td><?php echo $is_mobile_tower=='t'?'Yes':'No';?></td>
                                                <td>Does Property Have Mobile Tower?</td>
                                                <td><b>:</b></td>
                                                <td><?php echo $uvis_mobile_tower=='t'?'Yes':'No';?></td>
                                            </tr>
                                            <tr>
                                                <td>Does Property Have Hoarding Board?</td>
                                                <td><b>:</b></td>
                                                <td><?php echo $is_hoarding_board=='t'?'Yes':'No';?></td>
                                                <td>Does Property Have Hoarding Board?</td>
                                                <td><b>:</b></td>
                                                <td><?php echo $uvis_hoarding_board=='t'?'Yes':'No';?></td>
                                            </tr>
                                            <tr>
                                                <td>Is property have Petrol Pump ?</td>
                                                <td><b>:</b></td>
                                                <td><?php echo $is_petrol_pump=='t'?'Yes':'No';?></td>
                                                <td>Is property have Petrol Pump ?</td>
                                                <td><b>:</b></td>
                                                <td><?php echo $uvis_petrol_pump=='t'?'Yes':'No';?></td>
                                            </tr>
                                        </tbody>
					                </table>
                                </div>
							</div>
							<?php 
					//if($val_ptype["property_type"]<>"Vacant Land"){ ?>
							<div class="row">
								<span class="text-danger"><u><b>Self Assessed Details</b></u></span>
							</div>
							<div class="row">
								<div class="table-responsive">
                                    <table class="table table-bordered" style="font-family:Arial, Helvetica, sans-serif; font-size:12px;">
                                        <thead class="thead-light" style="background-color: blanchedalmond;">
                                            <tr>
                                                <th>Floor No.</th>
                                                <th>Use Type</th>
                                                <th>Occupancy Type</th>
                                                <th>Construction Type</th>
                                                <th>Built Up Area (in Sq. Ft.)</th>
                                                <th>Carpet Area (in Sq. Ft.)</th>
                                                <th>Date of Completion</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            <?php
                                        foreach ($floor_details as  $valfloor) {
                                            ?>	

                                            <tr style="text-align: center">
                                                <td height="31"><?=$valfloor['floor_name']?></td>
                                                <td><?=$valfloor['usage_type']?></td>
                                                <td><?=$valfloor['occupancy_name']?></td>
                                                <td><?=$valfloor['construction_type']?> </td>
                                                <td><?=$valfloor['builtup_area']?> </td>    	
                                                <td><?=$valfloor['carpet_area']?></td>			
                                                <td><?=date('m-Y',strtotime($valfloor['date_from']))?></td>
                                            </tr>	
                                            <?php } ?>
                                        </tbody>
					                </table>
                                </div>
							</div>
							<div class="row">
								<span class="text-danger"><u><b>Verified Details</b></u></span>
							</div>
							<div class="row">
								<div class="table-responsive">
                                    <table class="table table-bordered" style="font-family:Arial, Helvetica, sans-serif; font-size:12px;">
                                        <thead class="thead-light" style="background-color: blanchedalmond;">
                                            <tr>
                                                <th>Floor No.</th>
                                                <th>Use Type</th>
                                                <th>Occupancy Type</th>
                                                <th>Construction Type</th>
                                                <th>Built Up Area (in Sq. Ft.)</th>
                                                <th>Carpet Area (in Sq. Ft.)</th>
                                                <th>Date of Completion</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            <?php              
                                             foreach ($uvfloor_details as  $valfloor1) {		
                                            ?>	

                                            <tr style="text-align: center">
                                                <td height="31"><?=$valfloor1['floor_name']?></td>
                                                <td><?=$valfloor1['usage_type']?></td>
                                                <td><?=$valfloor1['occupancy_name']?></td>
                                                <td><?=$valfloor1['construction_type']?> </td>
                                                <td><?=$valfloor1['builtup_area']?> </td>    	
                                                <td><?=$valfloor1['carpet_area']?></td>			
                                                <td><?=date('m-Y',strtotime($valfloor1['date_from']))?></td>
                                            </tr>	
                                            <?php } ?>
                                        </tbody>
					                </table>
                                </div>
							</div>
							<?php //} ?>
						</div>
					</div>
				</div>
			</div>
			<?php }} ?>
            
            <!--------SAF Form------------>
            <div class="panel panel-bordered panel-dark">
                <div class="panel-heading">
                    <h3 class="panel-title">SAF Form</h3>
                </div>
                <div class="panel-body" style="padding-bottom: 0px;">
                    <div class="table-responsive">
                        <table class="table table-bordered" style="font-family:Arial, Helvetica, sans-serif; font-size:12px;">
                            <thead class="thead-light" style="background-color: lightgray;">
                                <tr>
                                    <th style="width:160px;">Document Name</th>
                                    <th style="width:160px;">Document Image</th>
                                </tr>
                            </thead>
                            <tbody>
                                <tr>
                                    <td><?=$saf_form_doc_nm;?></td>
                                    <td>
                                        <a href="<?=base_url();?>/writable/uploads/<?=$saf_form_doc_name;?>" target="_blank" > <img id="imageresource" src="<?=base_url();?>/public/assets/img/pdf_logo.png" style="width: 40px; height: 40px;"></a>
                                    </td>
                                </tr>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>


            <!--------prop doc------------>
			
            <div class="panel panel-bordered panel-dark">
                <div class="panel-heading">
                    <h3 class="panel-title">Property Document</h3>
                </div>
                <div class="panel-body" style="padding-bottom: 0px;">
                    <div class="table-responsive">
                        <table class="table table-bordered" style="font-family:Arial, Helvetica, sans-serif; font-size:12px;">
                            <thead class="thead-light" style="background-color: lightgray;">
                                <tr>
                                    <th style="width:160px;">Document Name</th>
                                    <th style="width:160px;">Document Image</th>
                                    <th style="width:160px;"></th>
                                    <th style="width:200px;"></th>
                                </tr>
                            </thead>
                            <tbody>
								<?php
								
                                if($prpSaf['prop_type_mstr_id']==1) {
                                ?>
                                <tr>
                                    <td><?=$super_doc_nm;?></td>
                                    <td>
                                        <a href="<?=base_url();?>/writable/uploads/<?=$super_doc_name;?>" target="_blank" > <img id="imageresource" src="<?=base_url();?>/public/assets/img/pdf_logo.png" style="width: 40px; height: 40px;"></a>
                                    </td>
                                    <td>
                                        <?php
                                        if($super_doc_verify_status=="0") {
                                        ?>
                                            <input type="hidden" name="super_document_id" value="<?=$super_doc_id;?>"/>
                                            <input type="hidden" id="super_verify_status" value="0"/>
                                            <select name="super_verify" id="super_verify" class="form-control">
                                                <option value="">Select</option>
                                                <option value="1">Verify</option>
                                                <option value="2">Reject</option>
                                            </select>
                                            <br/>
                                        <?php
                                        }
                                        else if($super_doc_verify_status=="1") {
                                            echo "<span class='text-danger'>Verified</span>";
                                        } else if($super_doc_verify_status=="2") {
                                            echo "<span class='text-danger'>Rejected</span>";
                                        }
                                        ?>
                                    </td>
                                    <td><textarea type="text" placeholder="Enter Remarks" id="super_remarks" name="super_remarks" class="form-control" ></textarea></td>
                                </tr>
                                <?php
                                }elseif($prpSaf['prop_type_mstr_id']==3) {
                                ?>
                                <tr>
                                    <td><?=$flat_doc_nm;?></td>
                                    <td>
                                        <a href="<?=base_url();?>/writable/uploads/<?=$flat_doc_name;?>" target="_blank" > <img id="imageresource" src="<?=base_url();?>/public/assets/img/pdf_logo.png" style="width: 40px; height: 40px;"></a>
                                    </td>
                                    <td>
                                        <?php
                                        if($flat_doc_verify_status=="0") {
                                        ?>
                                            <input type="hidden" name="flat_document_id" value="<?=$flat_doc_id;?>"/>
                                            <input type="hidden" id="flat_verify_status" value="0"/>
                                            <select name="flat_verify" id="flat_verify" class="form-control">
                                                <option value="">Select</option>
                                                <option value="1">Verify</option>
                                                <option value="2">Reject</option>
                                            </select>
                                            <br/>
                                        <?php
                                        }
                                        else if($flat_doc_verify_status=="1") {
                                            echo "<span class='text-danger'>Verified</span>";
                                        } else if($flat_doc_verify_status=="2") {
                                            echo "<span class='text-danger'>Rejected</span>";
                                        }
                                        ?>
                                    </td>
                                    <td><textarea type="text" placeholder="Enter Remarks" id="flat_remarks" name="flat_remarks" class="form-control" ></textarea></td>
                                </tr>
                                <?php
                                } else {
                                ?>
                                <tr>
                                    <td><?=$tr_doc_nm;?></td>
                                    <td>
                                        <a href="<?=base_url();?>/writable/uploads/<?=$tr_doc_name;?>" target="_blank" > <img id="imageresource" src="<?=base_url();?>/public/assets/img/pdf_logo.png" style="width: 40px; height: 40px;"></a>
                                    </td>
                                    <td>
                                        <?php
                                        if($tr_doc_verify_status=="0") {
                                        ?>
                                            <input type="hidden" name="tr_document_id" value="<?=$tr_doc_id;?>"/>
                                            <input type="hidden" id="tr_verify_status" value="0"/>
                                            <select name="tr_verify" id="tr_verify" class="form-control">
                                                <option value="">Select</option>
                                                <option value="1">Verify</option>
                                                <option value="2">Reject</option>
                                            </select>
                                            <br/>
                                        <?php
                                        }
                                        else if($tr_doc_verify_status=="1") {
                                            echo "<span class='text-danger'>Verified</span>";
                                        } else if($tr_doc_verify_status=="2") {
                                            echo "<span class='text-danger'>Rejected</span>";
                                        }
                                        ?>
                                    </td>
                                    <td><textarea type="text" placeholder="Enter Remarks" id="tr_remarks" name="tr_remarks" class="form-control" ></textarea></td>
                                </tr>
                                <tr>
                                    <td><?=$pr_doc_nm;?></td>
                                    <td>
										<a href="<?=base_url();?>/writable/uploads/<?=$pr_doc_name;?>" target="_blank"> <img src="<?=base_url();?>/public/assets/img/pdf_logo.png" style="width: 40px; height: 40px;"/></a>
                                    </td>
                                    <td>
                                        <?php
                                        if($pr_doc_verify_status=="0") {
                                        ?>

                                        <input type="hidden" name="pr_document_id" value="<?=$pr_doc_id;?>"/>
                                        <input type="hidden" id="pr_verify_status" value="0"/>
                                        <select name="pr_verify" id="pr_verify" class="form-control">
                                            <option value="">Select</option>
                                            <option value="1">Verify</option>
                                            <option value="2">Reject</option>
                                        </select>
                                        <br/>
                                        <?php
                                        } else if($pr_doc_verify_status=="1") {
                                            echo "<span class='text-danger'>Verified</span>";
                                        } else if($pr_doc_verify_status=="2") {
                                            echo "<span class='text-danger'>Rejected</span>";
                                        }
                                        ?>
                                    </td>
                                    <td><textarea type="text" placeholder="Enter Remarks" id="pr_remarks" name="pr_remarks" class="form-control" ></textarea></td>
                                </tr>
                                <!--
								<tr>
                                    <td><?=$no_electric_connection_doc_nm;?></td>
                                    <td>
										<a href="<?=base_url();?>/writable/uploads/<?=$no_electric_connection_doc_name;?>" target="_blank"> <img src="<?=base_url();?>/public/assets/img/pdf_logo.png" style="width: 40px; height: 40px;"/></a>
                                    </td>
                                    <td>
                                        <?php
                                        if($no_electric_connection_doc_verify_status=="0") {
                                        ?>

                                        <input type="hidden" name="no_electric_document_id" value="<?=$no_electric_connection_doc_id;?>"/>
                                        <input type="hidden" id="no_electric_verify_status" value="0"/>
                                        <select name="no_electric_verify" id="no_electric_verify" class="form-control">
                                            <option value="">Select</option>
                                            <option value="1">Verify</option>
                                            <option value="2">Reject</option>
                                        </select>
                                        <br/>
                                        <?php
                                        } else if($no_electric_connection_doc_verify_status=="1") {
                                            echo "<span class='text-danger'>Verified</span>";
                                        } else if($no_electric_connection_doc_verify_status=="2") {
                                            echo "<span class='text-danger'>Rejected</span>";
                                        }
                                        ?>
                                    </td>
                                    <td><textarea type="text" placeholder="Enter Remarks" id="no_electric_remarks" name="no_electric_remarks" class="form-control" ></textarea></td>
                                </tr> -->
                                <?php
                                }
                                ?>
								
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
            <!------------>
            <div class="panel panel-bordered panel-dark">
                <div class="panel-body" style="padding-bottom: 0px;">
                    <div class="form-group">
                        <label class="col-md-2" >Remarks</label>
                        <div class="col-md-10">
                            <textarea type="text" placeholder="Enter Remarks" id="remarks" name="remarks" class="form-control" ></textarea>
                        </div>
                    </div>
                    <?php
                    if(isset($form['verification_status'])) {
                        if($form['verification_status']=="0") {
                    ?>
                        <div class="form-group">
                            <label class="col-md-2" >&nbsp;</label>
                            <div class="col-md-10">
                                <button class="btn btn-danger" id="btn_generate_memo_submit" name="btn_generate_memo_submit" type="submit" style="display:none;">Generate Memo</button>
                                <button class="btn btn-danger" id="btn_app_submit" name="btn_app_submit" type="submit">Back To Citizen</button>
                            </div>
                        </div>
                    <?php
                        }
                    }
                    ?>
                    </div>
                </div>
            </div>
    </form>
    <!-- End page content-->
</div>
<!-- END CONTENT CONTAINER-->
<!-- modal start -->
<!-- Creates the bootstrap modal where the image will appear -->
<div class="modal fade" id="imagemodal" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
  <div class="modal-dialog">
    <div class="modal-content">
      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal"><span aria-hidden="true">&times;</span><span class="sr-only">Close</span></button>
        <h4 class="modal-title" id="myModalLabel">Image preview</h4>
      </div>
      <div class="modal-body">
        <img src="" id="imagepreview" style="width: 400px; height: 264px;" >
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
      </div>
    </div>
  </div>
</div>
//////modal end
<?= $this->include('layout_vertical/footer');?>
<script>
$(function() {
		$('.pop').on('click', function() {
            //alert($(this).find('img').attr('src'));
			$('#imagepreview').attr('src', $(this).find('img').attr('src'));
			$('#imagemodal').modal('show');   
		});		

});
</script>

<script>
    function app_img_remarks_details(il)
    {

     var app_img_verify =$('#app_img_verify'+il).val();
     var app_img_remarks =$('#app_img_remarks'+il).val();
     var app_doc_verify =$('#app_doc_verify'+il).val();
     var app_doc_remarks =$('#app_doc_remarks'+il).val();
     var applicant_img_verify_status =$('#applicant_img_verify_status'+il).val();
     var count_change_app = parseInt($("#count_change_app").val());
     var count_app = parseInt($("#count_app").val());
     var tr_verify = $("#tr_verify").val();
     var pr_verify = $("#pr_verify").val();
        //alert(app_img_verify);
     if(app_img_verify=="2")
        {
            if(count_change_app>0){
                if(applicant_img_verify_status==1){
                   $("#applicant_img_verify_status"+il).val(0);
                var str=count_change_app-1;
                $("#count_change_app").val(str);
                    }
            }

            $("#app_img_remarks"+il).show();
            $("#btn_generate_memo_submit").css("display","none");
            $("#btn_app_submit").css("display","block");
        }
        else if(app_img_verify=="1")
        {
            if(applicant_img_verify_status==0){
                    $("#applicant_img_verify_status"+il).val(1);
            var str=count_change_app+1;
            $("#count_change_app").val(str);
            $("#app_img_remarks"+il).hide();
                }
            if(str==count_app)
            {
                $("#btn_generate_memo_submit").css("display","block");
                $("#btn_app_submit").css("display","none");
            }
        }
    }
    function app_doc_remarks_details(il)
    {

     var app_img_verify =$('#app_img_verify'+il).val();
     var app_img_remarks =$('#app_img_remarks'+il).val();
     var app_doc_verify =$('#app_doc_verify'+il).val();
     var app_doc_remarks =$('#app_doc_remarks'+il).val();
     var applicant_doc_verify_status =$('#applicant_doc_verify_status'+il).val();
     var count_change_app = parseInt($("#count_change_app").val());
     var count_app = parseInt($("#count_app").val());
     var tr_verify = $("#tr_verify").val();
     var pr_verify = $("#pr_verify").val();
        //alert(app_img_verify);
     if(app_doc_verify=="2")
        {
            if(count_change_app>0){
                if(applicant_doc_verify_status==1){
                   $("#applicant_doc_verify_status"+il).val(0);
                var str=count_change_app-1;
                $("#count_change_app").val(str);
                    }
            }

            $("#app_doc_remarks"+il).show();
            $("#btn_generate_memo_submit").css("display","none");
            $("#btn_app_submit").css("display","block");
        }
        else if(app_doc_verify=="1")
        {
            if(applicant_doc_verify_status==0){
                    $("#applicant_doc_verify_status"+il).val(1);
            var str=count_change_app+1;
            $("#count_change_app").val(str);
             $("#app_doc_remarks"+il).hide();
                }
            if(str==count_app)
            {
                $("#btn_generate_memo_submit").css("display","block");
                $("#btn_app_submit").css("display","none");
            }
        }
    }
$(document).ready(function(){
    $(".app_img_remarks").hide();
    $(".app_doc_remarks").hide();
    $("#tr_remarks").hide();
    $("#pr_remarks").hide();
    $("#flat_remarks").hide();

    $("#tr_verify").on('change',function(){
		var count_change_app = parseInt($("#count_change_app").val());
        var count_app = parseInt($("#count_app").val());
        var tr_verify = $("#tr_verify").val();
        var tr_verify_status = $("#tr_verify_status").val();
        if(tr_verify=="2")
        {
            if(count_change_app>0){
                if(tr_verify_status==1){
                   $("#tr_verify_status").val(0);
                var str=count_change_app-1;
                $("#count_change_app").val(str);
                    }
            }
            $("#tr_remarks").show();
            $("#btn_generate_memo_submit").css("display","none");
            $("#btn_app_submit").css("display","block");
        }
        else if(tr_verify=="1")
        {
            if(tr_verify_status==0){
                    $("#tr_verify_status").val(1);
            var str=count_change_app+1;
            $("#count_change_app").val(str);
             $("#tr_remarks").hide();
                }
            if(str==count_app)
            {
                $("#btn_generate_memo_submit").css("display","block");
                $("#btn_app_submit").css("display","none");
            }
        }
    });
    $("#pr_verify").on('change',function(){
		var count_change_app = parseInt($("#count_change_app").val());
        var count_app = parseInt($("#count_app").val());
        var pr_verify = $("#pr_verify").val();
        var pr_verify_status = $("#pr_verify_status").val();
        if(pr_verify=="2")
        {
            if(count_change_app>0){
                if(pr_verify_status==1){
                    $("#pr_verify_status").val(0);
                    var str=count_change_app-1;
                    $("#count_change_app").val(str);
                }
            }
            $("#pr_remarks").show();
            $("#btn_generate_memo_submit").css("display","none");
            $("#btn_app_submit").css("display","block");
        } else if(pr_verify=="1") {
            if(pr_verify_status==0){
                $("#pr_verify_status").val(1);
                var str=count_change_app+1;
                $("#count_change_app").val(str);
                $("#pr_remarks").hide();
            }
            if(str==count_app)
            {
                $("#btn_generate_memo_submit").css("display","block");
                $("#btn_app_submit").css("display","none");
            }
        }
    });
	
	$("#no_electric_verify").on('change',function(){
		var count_change_app = parseInt($("#count_change_app").val());
        var count_app = parseInt($("#count_app").val());
        var no_electric_verify = $("#no_electric_verify").val();
        var no_electric_verify_status = $("#no_electric_verify_status").val();
        if(no_electric_verify=="2")
        {
            if(count_change_app>0){
                if(no_electric_verify_status==1){
                    $("#no_electric_verify_status").val(0);
                    var str=count_change_app-1;
                    $("#count_change_app").val(str);
                }
            }
            $("#no_electric_remarks").show();
            $("#btn_generate_memo_submit").css("display","none");
            $("#btn_app_submit").css("display","block");
        } else if(no_electric_verify=="1") {
            if(no_electric_verify_status==0){
                $("#no_electric_verify_status").val(1);
                var str=count_change_app+1;
                $("#count_change_app").val(str);
                $("#no_electric_remarks").hide();
            }
            if(str==count_app)
            {
                $("#btn_generate_memo_submit").css("display","block");
                $("#btn_app_submit").css("display","none");
            }
        }
    });
	
	$("#super_verify").on('change',function(){
		var count_change_app = parseInt($("#count_change_app").val());
        var count_app = parseInt($("#count_app").val());
        var super_verify = $("#super_verify").val();
        var pr_verify = $("#pr_verify").val();
        var super_verify_status = $("#super_verify_status").val();
        if(super_verify=="2")
        {
            if(count_change_app>0){
                if(super_verify_status==1){
                   $("#super_verify_status").val(0);
                    var str=count_change_app-1;
                    $("#count_change_app").val(str);
                }
            }
            $("#super_remarks").show();
            $("#btn_generate_memo_submit").css("display","none");
            $("#btn_app_submit").css("display","block");
        }
        else if(super_verify=="1")
        {
            if(super_verify_status==0){
                $("#super_verify_status").val(1);
                var str=count_change_app+1;
                $("#count_change_app").val(str);
                $("#super_remarks").hide();
            }
            if(str==count_app)
            {
                $("#btn_generate_memo_submit").css("display","block");
                $("#btn_app_submit").css("display","none");
            }
        }
    });
	
    $("#flat_verify").on('change',function(){
		var count_change_app = parseInt($("#count_change_app").val());
        var count_app = parseInt($("#count_app").val());
        var flat_verify = $("#flat_verify").val();
        var pr_verify = $("#pr_verify").val();
        var flat_verify_status = $("#flat_verify_status").val();
        if(flat_verify=="2")
        {
            if(count_change_app>0){
                if(flat_verify_status==1){
                   $("#flat_verify_status").val(0);
                    var str=count_change_app-1;
                    $("#count_change_app").val(str);
                }
            }
            $("#flat_remarks").show();
            $("#btn_generate_memo_submit").css("display","none");
            $("#btn_app_submit").css("display","block");
        }
        else if(flat_verify=="1")
        {
            if(flat_verify_status==0){
                $("#flat_verify_status").val(1);
                var str=count_change_app+1;
                $("#count_change_app").val(str);
                $("#flat_remarks").hide();
            }
            if(str==count_app)
            {
                $("#btn_generate_memo_submit").css("display","block");
                $("#btn_app_submit").css("display","none");
            }
        }
    });
    $("#btn_app_submit").click(function(){
        var proceed = true;
        $('#saf_receive_table').find('.app_img_verify').each(function(){
            $(this).css('border-color','');
            var ID = this.id.split('app_img_verify')[1];
            if(!$.trim($(this).val())){ $(this).css('border-color','red'); 	proceed = false; }
             if($(this).val()=='2'){
                 if ($("#app_img_remarks"+ID).val()=="") {
                     $("#app_img_remarks"+ID).css('border-color','red'); 	proceed = false;
                 }

             }
        });
        $('#saf_receive_table').find('.app_doc_verify').each(function(){
            $(this).css('border-color','');
            var IDD = this.id.split('app_doc_verify')[1];
            if(!$.trim($(this).val())){ $(this).css('border-color','red'); 	proceed = false; }
             if($(this).val()=='2'){
                 if ($("#app_doc_remarks"+IDD).val()=="") {
                     $("#app_doc_remarks"+IDD).css('border-color','red'); 	proceed = false;
                 }
             }
        });


        var remarks = $("#remarks").val();
        if(remarks=="")
        {
            $('#remarks').css('border-color','red');
            proceed = false;
        }

        var tr_verify = $("#tr_verify").val();
        if(tr_verify=="")
        {
            $('#tr_verify').css('border-color','red');
            proceed = false;
        }
        if(tr_verify=="2")
        {
            var tr_remarks = $("#tr_remarks").val();
            if(tr_remarks=="")
            {
                $('#tr_remarks').css('border-color','red');
                proceed = false;
            }
        }
        var pr_verify = $("#pr_verify").val();
        if(pr_verify=="")
        {
            $('#pr_verify').css('border-color','red');
            proceed = false;
        }
        if(pr_verify=="2")
        {
            var pr_remarks = $("#pr_remarks").val();
            if(pr_remarks=="")
            {
                $('#pr_remarks').css('border-color','red');
                proceed = false;
            }
        }
        return proceed;
    });
    $("#btn_generate_memo_submit").click(function(){
        var proceed = true;

        var remarks = $("#remarks").val();

        if(remarks=="")
        {
            $('#remarks').css('border-color','red');
            proceed = false;
        }
        $('#saf_receive_table').find('.app_img_verify').each(function(){
            $(this).css('border-color','');
            if(!$.trim($(this).val())){ $(this).css('border-color','red'); 	proceed = false; }

        });
        $('#saf_receive_table').find('.app_doc_verify').each(function(){
            $(this).css('border-color','');
            if(!$.trim($(this).val())){ $(this).css('border-color','red'); 	proceed = false; }

        });

        var tr_verify = $("#tr_verify").val();
        if(tr_verify=="")
        {
            $('#tr_verify').css('border-color','red');
            proceed = false;
        }

        var pr_verify = $("#pr_verify").val();
        if(pr_verify=="")
        {
            $('#pr_verify').css('border-color','red');
            proceed = false;
        }

        return proceed;
    });
});
</script>