<?= $this->include('layout_vertical/header');?>
<!--DataTables [ OPTIONAL ]-->
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
					<li><a href="#">ULB TC SAF</a></li>
					<li class="active">ULB TC SAF View</li>
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
                                <a href="<?php echo base_url('ulbtc_saf/index') ?>" class="btn btn-default">Back</a>
                            </div>
                            <h3 class="panel-title">Basic Details</h3>
                        </div>
                <div class="panel-body" style="padding-bottom: 0px;">
                    <div class="row">
                        <label class="col-sm-2">Holding No.:</label>
                        <div class="col-sm-4 pad-btm">
                             <b><?=(isset($previous_prop_dtl['holding_no']))?$previous_prop_dtl['holding_no']:'<span class="text-danger">N/A</span>';?></b>
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
                        <!-------Owner Details-------->
                        <div class="panel panel-bordered panel-dark">
                            <div class="panel-heading">
                                <h3 class="panel-title">Owner Details</h3>
                            </div>
                            <div class="panel-body" style="padding-bottom: 0px;">
                                <div class="table-responsive">
                                    <table class="table table-bordered" style="font-family:Arial, Helvetica, sans-serif; font-size:12px;">
                                        <thead class="thead-light" style="background-color: blanchedalmond;">
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
                                                <?php foreach($owner_list as $value): ?>
                                                    <tr>
															  <td><?php echo $value['owner_name']; ?></td>
															  <td><?php echo $value['relation_type']; ?></td>
															  <td><?php echo $value['guardian_name']; ?></td>
															  <td><?php echo $value['mobile_no']; ?></td>
															</tr>
                                                    <tr class="success">
                                                                <th>Document Name</th>
                                                                <th>Applicant Image</th>
                                                                <th>Status</th>
                                                                <th></th>

                                                            </tr>
                                                            <tr>
                                                                <td>Applicant Image</td>
                                                                <td><a href="#" class="pop"><img id="imageresource" src="<?=base_url();?>/writable/uploads/<?=$value["applicant_img"];?>" style="width: 40px; height: 40px;"></a></td>
                                                                <td>
                                                                    <?php
                                                                    if($value['applicant_img_verify_status']=="0")
                                                                    {
                                                                    ?>

                                                                    <select name="app_img_verify[]" id="app_img_verify<?=$j;?>" class="form-control app_img_verify" onchange="app_img_remarks_details(<?=$j;?>);">
                                                                        <option value="">Select</option>
                                                                        <option value="1">Verify</option>
                                                                        <option value="2">Reject</option>
                                                                    </select>
                                                                    <br/>
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
                                                                <td></td>

                                                            </tr>
                                                            <tr class="success">
                                                                <th>Document Name</th>
                                                                <th>Applicant Document</th>
                                                                <th>Status</th>
                                                                <th></th>
                                                                <th></th>
                                                            </tr>
                                                            <tr style="border-bottom: 5px solid #000;">
                                                                <td><?=$value["applicant_doc_name"];?></td>
                                                                <?php
                                                                $exp_app_doc=explode('.',$value["applicant_doc"]);
                                                                $exp_app_doc_ext=$exp_app_doc[1];
                                                                ?>
                                                                <td>
                                                                    <?php
                                                                    if($exp_app_doc_ext=='pdf')
                                                                    {
                                                                    ?>
                                                                    <a href="<?=base_url();?>/writable/uploads/<?=$value["applicant_doc"];?>" target="_blank"> <img id="imageresource" src="<?=base_url();?>/public/assets/img/pdf_logo.png" style="width: 40px; height: 40px;"></a>
                                                                    <?php
                                                                    }
                                                                    else
                                                                    {
                                                                    ?>
                                                                    <a href="#" class="pop"><img id="imageresource" src="<?=base_url();?>/writable/uploads/<?=$value["applicant_doc"];?>" style="width: 40px; height: 40px;"></a>
                                                                    <?php
                                                                    }
                                                                    ?>
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
                                                                <td></td>
                                                                <td></td>
                                                            </tr>
                                                <?php endforeach; ?>
                                                <?php endif; ?>
                                                <?php endif; ?>
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
                                                <td><?php echo $is_mobile_tower=='t'?'Yes':'No';?>
                                                <?php
                                                 if($is_mobile_tower=='t')
                                                 {
                                                    ?>
                                                    <br/>Tower Area: <?=$tower_area;?>
                                                    <br/>Tower Installation Date: <?=$tower_installation_date;?>
                                                    <?php
                                                 }
                                                 ?>
                                                </td>
                                                <td>Does Property Have Mobile Tower?</td>
                                                <td><b>:</b></td>
                                                <td><?php echo $vis_mobile_tower=='t'?'Yes':'No';?>
                                                <?php
                                                 if($vis_mobile_tower=='t')
                                                 {
                                                    ?>
                                                    <br/>Tower Area: <?=$vtower_area;?>
                                                    <br/>Tower Installation Date: <?=$vtower_installation_date;?>
                                                    <?php
                                                 }
                                                 ?>
                                                </td>
                                            </tr>
                                            <tr>
                                                <td>Does Property Have Hoarding Board?</td>
                                                <td><b>:</b></td>
                                                <td><?php echo $is_hoarding_board=='t'?'Yes':'No';?>
                                                <?php
                                                 if($is_hoarding_board=='t')
                                                 {
                                                    ?>
                                                    <br/>Hoarding Area: <?=$hoarding_area;?>
                                                    <br/>Hoarding Installation Date: <?=$hoarding_installation_date;?>
                                                    <?php
                                                 }
                                                 ?>
                                                </td>
                                                <td>Does Property Have Hoarding Board?</td>
                                                <td><b>:</b></td>
                                                <td><?php echo $vis_hoarding_board=='t'?'Yes':'No';?>
                                                <?php
                                                 if($vis_hoarding_board=='t')
                                                 {
                                                    ?>
                                                    <br/>Hoarding Area: <?=$vhoarding_area;?>
                                                    <br/>Hoarding Installation Date: <?=$vhoarding_installation_date;?>
                                                    <?php
                                                 }
                                                 ?>
                                                </td>
                                            </tr>
                                            <tr>
                                                <td>Is property have Petrol Pump ?</td>
                                                <td><b>:</b></td>
                                                <td><?php echo $is_petrol_pump=='t'?'Yes':'No';?>
                                                <?php
                                                 if($is_petrol_pump=='t')
                                                 {
                                                    ?>
                                                    <br/>Under Ground Area: <?=$under_ground_area;?>
                                                    <br/>Petrol Pump Completion Date: <?=$petrol_pump_completion_date;?>
                                                    <?php
                                                 }
                                                 ?>
                                                </td>
                                                <td>Is property have Petrol Pump ?</td>
                                                <td><b>:</b></td>
                                                <td><?php echo $vis_petrol_pump=='t'?'Yes':'No';?>
                                                <?php
                                                 if($vis_petrol_pump=='t')
                                                 {
                                                    ?>
                                                    <br/>Under Ground Area: <?=$vunder_ground_area;?>
                                                    <br/>Petrol Pump Completion Date: <?=$vpetrol_pump_completion_date;?>
                                                    <?php
                                                 }
                                                 ?>
                                                </td>
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

                    <form method="post" class="form-horizontal" action="">
             <!--------prop doc------------>
                            <div class="panel panel-bordered panel-dark">
                            <div class="panel-heading">
                                <h3 class="panel-title">Property Document</h3>

                            </div>
                            <div class="panel-body" style="padding-bottom: 0px;">
                                <div class="table-responsive">
                                    <table class="table table-bordered" style="font-family:Arial, Helvetica, sans-serif; font-size:12px;">
                                        <thead class="thead-light" style="background-color: blanchedalmond;">
                                            <tr>
                                                <th style="width:160px;">Document Name</th>
                                                <th style="width:160px;">Document Image</th>

                                            </tr>
                                        </thead>
                                        <tbody>

                                            <tr>
                                                <td><?=$prop_tr_mode_document['doc_name'];?></td>
                                                <?php
                                                                $exp_tr_doc=explode('.',$prop_tr_mode_document['doc_path']);
                                                                $exp_tr_doc_ext=$exp_tr_doc[1];
                                                                ?>
                                                                <td>
                                                                    <?php
                                                                    if($exp_tr_doc_ext=='pdf')
                                                                    {
                                                                    ?>
                                                                    <a href="<?=base_url();?>/writable/uploads/<?=$prop_tr_mode_document['doc_path'];?>" target="_blank" > <img id="imageresource" src="<?=base_url();?>/public/assets/img/pdf_logo.png" style="width: 40px; height: 40px;"></a>
                                                                    <?php
                                                                    }
                                                                    else
                                                                    {
                                                                    ?>
                                                                    <a href="#" class="pop"><img id="imageresource" src="<?=base_url();?>/writable/uploads/<?=$prop_tr_mode_document['doc_path'];?>" style="width: 40px; height: 40px;"></a>
                                                                    <?php
                                                                    }
                                                                    ?>
                                                </td>
                                                </tr>
                                            <tr>
                                                <td><?=$prop_pr_mode_document['doc_name'];?></td>
                                                <?php
                                                                $exp_pr_doc=explode('.',$prop_pr_mode_document['doc_path']);
                                                                $exp_pr_doc_ext=$exp_pr_doc[1];
                                                                ?>
                                                                <td>
                                                                    <?php
                                                                    if($exp_pr_doc_ext=='pdf')
                                                                    {
                                                                    ?>
                                                                    <a href="<?=base_url();?>/writable/uploads/<?=$prop_pr_mode_document['doc_path'];?>" target="_blank"> <img src="<?=base_url();?>/public/assets/img/pdf_logo.png" style="width: 40px; height: 40px;"/></a>
                                                                    <?php
                                                                    }
                                                                    else
                                                                    {
                                                                    ?>
                                                                    <a href="#" class="pop"><img id="imageresource" src="<?=base_url();?>/writable/uploads/<?=$prop_pr_mode_document['doc_path'];?>" style="width: 40px; height: 40px;"></a>
                                                                    <?php
                                                                    }
                                                                    ?>

                                        </td>
                                            </tr>
 					                    </tbody>
					                </table>
                                </div>
                </div>
            </div>
                            <!------------>



                        <div class="panel panel-bordered panel-dark">
                            <div class="panel-heading">
                                <h3 class="panel-title">Remarks</h3>

                            </div>
                            <div class="panel-body" style="padding-bottom: 0px;">
                                <div class="table-responsive">
                                    <table class="table table-bordered" style="font-family:Arial, Helvetica, sans-serif; font-size:12px;">
                                        <thead class="thead-light" style="background-color: blanchedalmond;">
                                            <tr>
                                                <th style="width:160px;">Dealing Assistant</th>
                                                <th style="width:160px;">ULB Tax Collector</th>
                                            </tr>
                                        </thead>
                                        <tbody>

                                            <tr>
                                                <td><?=$dl_remarks['remarks'];?></td>
                                                <td><?=$ulb_tc_remarks['remarks'];?></td>
                                                </tr>

 					                    </tbody>
					                </table>
                                </div>

                </div>
            </div>


                        </form>

                </div>
                <!--===================================================-->
                <!--End page content-->

            </div>
            <!--===================================================-->
            <!--END CONTENT CONTAINER-->
///////modal start
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

